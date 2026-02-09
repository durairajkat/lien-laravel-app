<?php

/**
 * HTML Helper
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Html
{
    /**
     * Make HTML tag attributes based on the keys and values of an array. Each array
     * key is an attribute name, with the key's value being the attribute value.
     * ---
     * @param   array
     * @return  string
     */
    public static function attributes($attr)
    {
        // Start with an empty string
        $markup = '';

        // Valid workspace
        if (is_array($attr) && ! empty($attr)) {
            // Loop through each attribute
            foreach ($attr as $name => $value) {
                $markup .= $name . '="' . self::output($value) . '" ';
            }
        }

        // Return the result, and always add an extra space to the end
        return rtrim($markup) . ' ';
    }

    /**
     * Detect whether or not a string contains HTML characters.
     * ---
     * @param   string
     * @return  bool
     */
    public static function isHtml($str)
    {
        return $str != strip_tags($str);
    }

    /**
     * Generate an HTML <option> tag.
     * ---
     * @param   mixed
     * @param   mixed
     * @param   bool
     * @return  string
     */
    public static function option($value, $text, $selected = FALSE)
    {
        // Option should be selected
        if (FALSE === $selected) {
            return '<option value="' . self::output($value) . '">'
                 . self::output($text)
                 . "</option>\n";
        // Option should not be selected
        } else {
            return '<option value="' . self::output($value). '" '
                 . 'selected="selected">' . self::output($text)
                 . "</option>\n";
        }
    }

    /**
     * Prepare a string, or recursivley prepare an array, for output on an HTML page or
     * an HTML email. XSS safe, since Vine uses UTF-8 as the default output headers
     * throughout.
     * ---
     * @param   mixed
     * @return  mixed
     */
    public static function output($data)
    {
        // Data array, escape all data
        if (is_array($data) && ! empty($data)) {
            // (recursion) Loop through all data and escape output
            foreach ($data as $key => $value) {
                $data[$key] = self::output($value);
            }
        // Basic string (no need to escape int|float|bool, etc)
        } elseif (is_string($data)) {
            $data = htmlspecialchars($data, ENT_QUOTES, Vine::UNICODE);
        }

        // HTML ready result
        return $data;
    }

    /**
     * Automatically wrap <a> tags around valid links in a string of text.
     * ---
     * @param   string  The string of text to work with.
     * @param   array   Custom HTML attributes to add to each generated <a> tag.
     * @return  string
     */
    public static function autoLink($str, $attr = NULL)
    {
        // Generate HTML attributes
        $attr = self::attributes($attr);

        // Add whitespace to beginning of string
        $str = ' ' . $str;

        // Wrap link tags around valid URLs
        $str = preg_replace(
            '`([^"=\'>])((http|https|ftp)://[^\s<]+[^\s<\.)])`i',
            '$1<a href="$2"' . $attr . '>$2</a>',
            $str
        );

        // (string) Remove whitespace added at beginning of string
        return substr($str, 1);
    }

    /**
     * Truncate text to a fixed length.
     * ---
     * @param  string  The string to truncate.
     * @param  int     The max length of the string.
     * @param  bool    If true, only truncate on complete words.
     * @param  bool    If true, add "..." to the end of the string.
     * @return string  Truncated text.
     */
    public static function truncate($text, $length, $break = TRUE, $ellipses = TRUE)
    {
        // No text, stop here
        if ( ! $text) {
            return '';
        }

        // Multi-byte strings mess up non mb_ functions
        if ( ! Vine_Unicode::isAscii($text)) {
            $text = Vine_Unicode::toAscii($text);
        }

        // Break on words
        if ($break) {
            // Words
            $textParts = explode(' ', $text);

            // Don't break on words
            if (strlen($textParts[0]) >= $length) {
                $newText = $textParts[0];
            // Break on words
            } else {
                // First word
                $newText = $textParts[0];

                // Total words
                $textPartsCount = count($textParts);

                // Each word
                for ($i = 1; $i < $textPartsCount; $i++)  {
                    // Concatenate word to $newText
                    $testText = $newText . ' ' . $textParts[$i];

                    // We're done concatenating
                    if (strlen($testText) > $length) {
                        break;
                    }

                    // Apply concatenation
                    $newText = $testText;
                }

                // Nothing needed truncated
                if ( ! $newText || ($newText == '')) {
                    $newText = $text;
                }
            }

            // Remove whitespace
            $newText = trim($newText);
        // Don't break on words
        } else {
            $newText = substr($text, 0, $length);
        }

        // Add ... if needed
        if ($ellipses && strlen($text) > strlen($newText)) {
            $newText .= '...';
        }

        // (string) Truncated result
        return $newText;
    }

    /**
     * Convert HTML code to human-readable plain text. Preserve as much of the intended
     * formatting as possible.
     * ---
     * @param   string  The HTML input.
     * @return  string  The plain text output.
     */
    public function htmlToText($html) {
        // Standardize new lines
        $html = $this->fixNewLines($html);

        // Load HTML into PHP's DOMDocument (for DOM traversal)
        $doc = new DOMDocument();
        @$doc->loadHTML($html);

        // Traverse DOM and convert to plain text
        $output = $this->goOverNode($doc);

        // Remove duplicate new lines
        $output = preg_replace("/[ \t]*\n[ \t]*/im", "\n", $output);

        // Remove leading and trailing whitespace
        $output = trim($output);

        // Plain text result
        return $output;
    }

    /**
     * Support function. Standardize new lines.
     * ---
     * @param   string
     * @return  string
     */
    private function fixNewLines($text) {
        $text = str_replace("\r\n", "\n", $text);
        $text = str_replace("\r", "\n", $text);
        return $text;
    }

    /**
     * Support function. Recursive.
     * ---
     * @param   object
     * @return  string
     */
    private function goOverNode($node) {
        // Remove duplicate whitespace
        if ($node instanceof DOMText) {
            return preg_replace("/\\s+/im", ' ', $node->wholeText);
        }

        // Remove the DOCTYPE
        if ($node instanceof DOMDocumentType) {
            return "";
        }

        // The next child in this node, the last child in this node, this node's name
        $nextName = $this->nextChild($node);
        $prevName = $this->prevChild($node);
        $name     = strtolower($node->nodeName);

        // Handle whitespace
        switch ($name) {
            // <hr /> to ------
            case 'hr':
                return "------\n";

            // Ignore these
            case 'style':
            case 'head"':
            case 'title':
            case 'meta':
            case 'script':
                return '';
                break;

            // Double new lines for <h#> tags
            case 'h1':
            case 'h2':
            case 'h3':
            case 'h4':
            case 'h5':
            case 'h6':
                $output = "\n";
                break;

            // One new line for these tags
            case 'p':
            case 'div':
                $output = "\n";
                break;

            // Unknown tags
            default:
                $output = '';
            break;
        }

        // (Recursion) Handle whitespace in all child nodes
        for ($i = 0; $i < $node->childNodes->length; $i++) {
            $n       = $node->childNodes->item($i);
            $text    = $this->goOverNode($n);
            $output .= $text;
        }

        // Each HTML tag gets handled differently
        switch ($name) {
            // Ignore these tags
            case 'style':
            case 'head':
            case 'title':
            case 'meta':
            case 'script':
                return '';
                break;

            // Convert these tags to new lines
            case 'h1':
            case 'h2':
            case 'h3':
            case 'h4':
            case 'h5':
            case 'h6':
                $output .= "\n";
                break;

            // Convert these tags to new lines so long as a <div> doesn't follow
            case 'p':
            case 'br':
                if ('div' != $nextName) {
                    $output .= "\n";
                }

                break;

            // Add one line if the next child isn't a div
            case 'div':
                if ('div' != $nextName && NULL != $nextName) {
                    $output .= "\n";
                }

                break;

            // Preserve links
            case 'a':
                // Links are returned in [text](link) format
                $href = $node->getAttribute('href');

                // Invalid link
                if ($href == NULL) {
                    if (NULL != $node->getAttribute('name')) {
                        $output = "[$output]";
                    }
                } else {
                    // Link matches output, don't use [text](link) format
                    if ($href == $output) {
                        $output;
                    // Use [text](link) format
                    } else {
                        $output = "[$output]($href)";
                    }
                }

                // Does the next node require additional whitespace?
                switch ($nextName) {
                    case 'h1':
                    case 'h2':
                    case 'h3':
                    case 'h4':
                    case 'h5':
                    case 'h6':
                        $output .= "\n";
                        break;
                }

            // Do nothing
            default:
        }

        // (string) Plain text result
        return $output;
    }

    /**
     * Support function.
     * ---
     * @param   object
     * @return  string
     */
    private function nextChild($node) {
        $nextNode = $node->nextSibling;

        while (NULL != $nextNode) {
            if ($nextNode instanceof DOMElement) {
                break;
            }

            $nextNode = $nextNode->nextSibling;
        }

        $nextName = NULL;

        if ($nextNode instanceof DOMElement && NULL != $nextNode) {
            $nextName = strtolower($nextNode->nodeName);
        }

        return $nextName;
    }

    /**
     * Support function.
     * ---
     * @param   object
     * @return  string
     */
    private function prevChild($node) {
        $nextNode = $node->previousSibling;

        while (NULL != $nextNode) {
            if ($nextNode instanceof DOMElement) {
                break;
            }

            $nextNode = $nextNode->previousSibling;
        }

        $nextName = NULL;

        if ($nextNode instanceof DOMElement && NULL != $nextNode) {
            $nextName = strtolower($nextNode->nodeName);
        }

        return $nextName;
    }
}
