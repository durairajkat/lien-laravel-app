<?php

/**
 * A helper class for resizing and cropping JPG, JPEG, PNG, and GIF images.
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Image
{
    /**
     * Resizing quality for JPEG images.
     * ---
     * @var  int
     */
    protected $jpegQuality = 80;

    /**
     * Input image file info.
     * ---
     * @var  mixed
     */
    protected $inputFilePath = NULL;
    protected $inputFileName = NULL;
    protected $inputFileSize = 0;
    protected $inputFileType = NULL;
    protected $inputMimeType = NULL;

    /**
     * I/O resources.
     * ---
     * @var  resource
     */
    protected $input  = NULL;
    protected $output = NULL;

    /**
     * Input image's original dimensions, in pixels.
     * ---
     * @var  int
     */
    protected $originalWidth  = 0;
    protected $originalHeight = 0;

    /**
     * Input image's dimensions and coordinates, in pixels.
     * ---
     * @var  int
     */
    protected $inputWidth  = 0;
    protected $inputHeight = 0;
    protected $inputLeft   = 0;
    protected $inputTop    = 0;

    /**
     * Output image's dimensions and coordinates, in pixels.
     * ---
     * @var  int
     */
    protected $outputWidth  = 0;
    protected $outputHeight = 0;
    protected $outputLeft   = 0;
    protected $outputTop    = 0;

    /**
     * Class constructor. Path to workspace image.
     * ---
     * @param   string  File path to image, or data string.
     * @return  void
     */
    public function __construct($image)
    {
        try {
            // Verify file exists
            if ( ! is_file($image)) {
                throw new VineMissingFileException('File "' . $image . '" could not be '
                        . 'found.');
            }

            // Save the file's info
            $this->inputFilePath = $image;
            $this->inputFileName = pathinfo($this->inputFilePath, PATHINFO_FILENAME);
            $this->inputFileSize = filesize($image);
            $this->inputFileType = Vine_File::getExtension($image);
            $this->inputMimeType = Vine_File::getMimeType($image);

            // Create input resource
            switch ($this->inputFileType) {
                case 'jpg':
                    $this->input = @imagecreatefromjpeg($image);
                    break;
                case 'jpeg':
                    $this->input = @imagecreatefromjpeg($image);
                    break;
                case 'png':
                    $this->input = @imagecreatefrompng($image);
                    break;
                case 'gif':
                    $this->input = @imagecreatefromgif($image);
                    break;
                default:
                    throw new VineBadFileException('Invalid image format: '
                            . $this->inputFileType);
                break;
            }

            // Image extension doesn't match mime-type of image is corrupted, try fallback
            if ( ! @imagesx($this->input) || ! $this->input) {
                // Use mime-type as a fallback
                switch ($this->inputMimeType) {
                    case 'image/jpeg':
                        $this->input = @imagecreatefromjpeg($image);
                        $this->inputFileType = 'jpg';
                        break;
                    case 'image/png':
                        $this->input = @imagecreatefrompng($image);
                        $this->inputFileType = 'png';
                        break;
                    case 'image/gif':
                        $this->input = @imagecreatefromgif($image);
                        $this->inputFileType = 'gif';
                        break;
                    default:
                        throw new VineBadFileException('Invalid image format: '
                                . $this->inputMimeType);
                    break;
                }
            }

            // Save input dimensions
            $this->inputWidth     = @imagesx($this->input);
            $this->inputHeight    = @imagesy($this->input);
            $this->originalWidth  = $this->inputWidth;
            $this->originalHeight = $this->inputHeight;

            // Don't type check (0 or boolean FALSE makes image invalid)
            if ( ! $this->inputWidth || ! $this->inputHeight) {
                throw new VineBadFileException('File "' . $image . '" is not a '
                        . 'valid image.');
            }
        // Fatal exception
        } catch (VineFileException $e) {
            Vine_Exception::handle($e); exit;
        }
    }

    /**
     * Free up memory resources that were created from resizing or cropping an image.
     * ---
     * @return  void
     */
    public function __destruct()
    {
        // Free up input image memory
        if (is_resource($this->input)) {
            imagedestroy($this->input);
        }

        // Free up output image memory
        if (is_resource($this->output)) {
            imagedestroy($this->output);
        }
    }

    /**
     * Do a typical image resize, while maintaining aspect ratio 100% of the time. Allows
     * for separate max width and max height settings. Max width and max height settings
     * are usually going to be the same (i.e. 500, 500), but may occasionally differ when
     * landscape or portrait are given resizing priority.
     * ---
     * @param   int|float  Max width of the image, in pixels.
     * @return  bool       TRUE if resize was successful.
     */
    public function doStandardResize($maxWidth, $maxHeight)
    {
        // Best when working with integers
        $maxWidth  = (int) $maxWidth  > 0 ? (int) $maxWidth  : 1;
        $maxHeight = (int) $maxHeight > 0 ? (int) $maxHeight : 1;

        // No need to resize image
        if ($this->inputWidth <= $maxWidth && $this->inputHeight <= $maxHeight) {
            // Just duplicate input image resource
            $this->output = $this->input;

            // Resize successful
            return TRUE;
        }

        // This is a landscape image
        if ($this->inputWidth >= $this->inputHeight) {
            // Calculate resizing dimensions while maintaining aspect ratio
            $this->outputWidth  = $maxWidth;
            $this->outputHeight = round(($maxWidth / $this->inputWidth)
                                * $this->inputHeight);

            // Image is still too tall, re-calculate the other direction
            if ($this->outputHeight > $maxHeight) {
                $this->outputHeight = $maxHeight;
                $this->outputWidth  = round(($maxHeight / $this->inputHeight)
                                    * $this->inputWidth);
            }
        // This is a portrait image
        } else {
            // Calculate resizing dimensions while maintaining aspect ratio
            $this->outputHeight = $maxHeight;
            $this->outputWidth  = round(($maxHeight / $this->inputHeight)
                                * $this->inputWidth);

            // Image is still too wide, re-calculate the other direction
            if ($this->outputWidth > $maxWidth) {
                $this->outputWidth  = $maxWidth;
                $this->outputHeight = round(($maxWidth / $this->inputWidth)
                                    * $this->inputHeight);
            }
        }

        // Sanitize output width
        if ($this->outputWidth < 1) {
            $this->outputWidth = 1;
        }

        // Sanitize output height
        if ($this->outputHeight < 1) {
            $this->outputHeight = 1;
        }

        // (bool) Attempt to resize/crop the image
        return $this->doResize();
    }

    /**
     * Do a fixed image resize. This method can optional use a basic "smart crop"
     * algorithm, which is useful when trying to generate images and thumbnails that will
     * fit inside a fixed width container element on an HTML page.
     * ---
     * @param  int   The fixed width of image.
     * @param  int   The fixed height of image.
     * @param  bool  Don't "squish" the image; instead make the largest possible crop.
     * @param  bool  If image is smaller than resize, stretch it?
     * @param  bool  TRUE if resize was successful.
     */
    public function doFixedResize($width, $height, $smartCrop = TRUE, $expandable = TRUE)
    {
        // Best when working with integers
        $width  = (int) $width  > 0 ? (int) $width  : 1;
        $height = (int) $height > 0 ? (int) $height : 1;

        // Sanitize booleans
        $smartCrop  = (bool) $smartCrop;
        $expandable = (bool) $expandable;

        // Force the image resize without cropping (may "squish" image if ratios differ)
        if (FALSE === $smartCrop) {
            // Set all dimensions and coordinates
            $this->outputWidth  = $width;
            $this->outputHeight = $height;
            $this->inputLeft    = 0;
            $this->inputTop     = 0;
            $this->outputLeft   = 0;
            $this->outputTop    = 0;

            // (bool) Attempt to resize/crop the image
            return $this->doResize();
        }

        // Image may not need resized or cropped
        if ($this->inputWidth <= $width && $this->inputHeight <= $height) {
            // Image does not need resized or cropped
            if (FALSE === $expandable) {
                // Just duplicate input image resource
                $this->output = $this->input;

                // Resize successful
                return TRUE;
            }
        }

        // The width to height ratio of the input and output results
        $ratio = $width / $height;

        // This is a landscape image
        if ($this->inputWidth >= $this->inputHeight) {
            // Calculate the largest crop that can be done in this aspect ratio
            $this->inputWidth  = round($this->originalHeight * $ratio);
            $this->inputHeight = $this->originalHeight;

            // Too wide, re-calculate the other direction
            if ($this->inputWidth > $this->originalWidth) {
                $this->inputWidth  = $this->originalWidth;
                $this->inputHeight = round($this->originalWidth / $ratio);
            }
        // This is a portrait image
        } else {
            // Calculate the largest crop that can be done in this aspect ratio
            $this->inputWidth  = $this->originalWidth;
            $this->inputHeight = round($this->originalWidth / $ratio);

            // Too tall, re-calculate the other direction
            if ($this->inputHeight > $this->originalHeight) {
                $this->inputWidth  = round($this->originalHeight * $ratio);
                $this->inputHeight = $this->originalHeight;
            }
        }

        // Output dimensions
        $this->outputWidth  = $width;
        $this->outputHeight = $height;

        // Coordinates
        $this->outputLeft = ($this->originalWidth - $this->inputWidth) / 2;
        $this->outputTop  = ($this->originalHeight - $this->inputHeight) / 2;

        // (bool) Attempt to resize/crop the image
        return $this->doResize();
    }

    /**
     * Manually crop an image. All image coordinates should be known and calulcated prior to
     * running this method.
     * ---
     * @param   int   Position of crop from left starting point.
     * @param   int   Position of cron from top starting point.
     * @param   int   Width of crop, beginning at left starting point.
     * @param   int   Height of crop, beginning at top starting point.
     * @return  bool  TRUE if crop was successful.
     */
    public function doCrop($left, $top, $width, $height)
    {
        // Save dimensions and coordinates
        $this->outputLeft   = (int) $left;
        $this->outputTop    = (int) $top;
        $this->outputWidth  = (int) $width;
        $this->outputHeight = (int) $height;

        // (bool) Attempt to resize/crop the image
        return $this->doResize();
    }

    /**
     * Save the image. This method is usually ran AFTER one of the resizing methods has
     * be successfully ran.
     * ---
     * @param   string  The path to save the image to.
     * @return  bool    TRUE if image saved successfully.
     */
    public function save($path)
    {
        // Use the input resource (no image resizing or cropping was apparently done)
        if ( ! is_resource($this->output)) {
            $src = $this->input;
        // Use the output resource
        } else {
            $src = $this->output;
        }

        // Save the image to specified path
        switch ($this->inputFileType) {
            case 'jpg':
                return imagejpeg($src, $path, $this->jpegQuality);
                break;
            case 'jpeg':
                return imagejpeg($src, $path, $this->jpegQuality);
                break;
            case 'png':
                return imagepng($src, $path);
                break;
            case 'gif':
                return imagegif($src, $path);
                break;
            default:
                return FALSE;
            break;
        }
    }

    /**
     * Get the filename extension of the image.
     * ---
     * @return  string
     */
    public function getExtension()
    {
        return strtolower(pathinfo($this->inputFilePath, PATHINFO_EXTENSION));
    }

    /**
     * Get the filename of the image without the extension appended on the end of it.
     * ---
     * @return  string
     */
    public function getNameNoExtension()
    {
        return pathinfo($this->inputFilePath, PATHINFO_FILENAME);
    }

    /**
     * Get the input (original) image's width, in pixels.
     * ---
     * @return  int
     */
    public function getInputWidth()
    {
        return $this->originalWidth;
    }

    /**
     * Get the input (original) image's height, in pixels.
     * ---
     * @return  int
     */
    public function getInputHeight()
    {
        return $this->originalHeight;
    }

    /**
     * Get the output (new) image's width, in pixels.
     * ---
     * @return  int
     */
    public function getOutputWidth()
    {
        return $this->outputWidth;
    }

    /**
     * Get the output (new) image's height, in pixels.
     * ---
     * @return  int
     */
    public function getOutputHeight()
    {
        return $this->outputHeight;
    }

    /**
     * Set the image quality for resized JPEG images. Only affects JPEG images.
     * ---
     * @param   int  Image quality. 0 = lowest quality, 100 = heighest quality.
     * @return  void
     */
    public function setJpegQuality($quality)
    {
        $this->jpegQuality = (int) $quality;
    }

    /**
     * @see  setJpegQuality()
     */
    public function setJpgQuality($quality)
    {
        $this->setJpegQuality($quality);
    }

    /**
     * Resize an image using dimensions and coordinates previously calculated using one of
     * the resize or crop methods.
     * ---
     * @return  bool  TRUE if image resized successfully.
     */
    protected function doResize()
    {
        try {
            // Not all versions of the GD libary define this function
            if (FALSE === function_exists('imagecreatetruecolor')) {
                throw new BadFunctionCallException('imagecreatetruecolor() function not '
                        . 'defined.');
            }

            // Create canvas
            $this->output = imagecreatetruecolor($this->outputWidth, $this->outputHeight);

            // Failed to create canvas
            if (FALSE === $this->output) {
                return FALSE;
            }

            // Resize image
            $result = imagecopyresampled(
                $this->output,       // Destination image link resource
                $this->input,        // Source image link resource
                $this->inputLeft,    // x-coordinate of destination point
                $this->inputTop,     // y-coordinate of destination point
                $this->outputLeft,   // x-coordinate of source point
                $this->outputTop,    // y-coordinate of source point
                $this->outputWidth,  // Destination width
                $this->outputHeight, // Destination height
                $this->inputWidth,   // Source width
                $this->inputHeight   // Source height
            );

            // Reset input dimensions and all coordinates
            if (TRUE === $result) {
                $this->inputWidth  = $this->originalWidth;
                $this->inputHeight = $this->originalHeight;
                $this->inputLeft   = 0;
                $this->inputTop    = 0;
                $this->outputLeft  = 0;
                $this->outputTop   = 0;
            }

            // (bool)
            return $result;
        // Fatal exception
        } catch (BadFunctionCallException $e) {
            Vine_Exception::handle($e); exit;
        }
    }
}
