<?php

/**
 * Email helper stacked onto the separately licensed Vine Framework.
 * ---
 * @author   Tell Konkle
 * @date     2019-03-14
 */
class WscEmail extends Vine_Email
{
    /**
     * The path to directories where all email templates are stored.
     * ---
     * @var  string
     */
    private $tplPath = INC_PATH . 'emails/';

    /**
     * @see  Vine_Email_Interface::setTpl()
     */
    public function setTpl($path, $html = TRUE)
    {
        try {
            // Add .php to path if necessary
            $path = str_replace('.php', '', $path) . '.php';

            // Use root path that was set in class constructor (helps keep things simple)
            if (strlen($path) && is_file($this->tplPath . $path)) {
                parent::setTpl($this->tplPath . $path, $html);
            // Use template path as passed to method
            } elseif (is_file($path)) {
                parent::setTpl($path, $html);
            // Invalid template path
            } else {
                throw new LogicException('Email template "' . $path . '" not found.');
            }
        } catch (LogicException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Check to see if an email template exists using a specified file path.
     * ---
     * @param   string  Path to template.
     * @return  bool    TRUE if email template exists, FALSE otherwise.
     */
    public function tplExists($path)
    {
        // Add .php to path if necessary
        $path = str_replace('.php', '', $path) . '.php';

        // Template exists in root email template folder
        if (strlen($path) && is_file($this->tplPath . $path)) {
            return TRUE;
        // Template exists as specified (who knows where it's at)
        } elseif (is_file($path)) {
            return TRUE;
        // Template does not exist
        } else {
            return FALSE;
        }
    }

    /**
     * Get all of the email templates in the system.
     * ---
     * @return  array|bool  FALSE if no templates found, array otherwise.
     */
    public function getTpls()
    {
        // Get all files in the template directory
        $files = Vine_File::getFiles($this->tplPath, TRUE);
        $path  = rtrim(realpath($this->tplPath), '/\\');

        // No files found, stop here
        if ( ! $files) {
            return FALSE;
        }

        // Loop through results and remove templates
        foreach ($files as $k => $file) {
            // This is a template, exclude it from list results
            if (   FALSE !== (stripos($file, '.tpl'))      // Exclude files with .tpl
                || FALSE !== (stripos($file, 'templates')) // Exclude files in a templates folder
                || FALSE !== (stripos($file, 'header'))    // Exclude files with "header" in name
                || FALSE !== (stripos($file, 'footer'))    // Exclude files with "footer" in name
                || FALSE === (stripos($file, '.php'))      // Must be a PHP file
            ) {
                unset($files[$k]);
                continue;
            }

            // Remove root path from file name
            if (0 === strpos($file, $path)) {
                $files[$k] = str_replace("\\", "/", ltrim(substr($file, strlen($path)) . '', '/\\'));
            }
        }

        // (array|bool)
        return is_array($files) && ! empty($files) ? $files : FALSE;
    }

    /**
     * Send the email. Calls parent send() method. Updates log file for sent email.
     * ---
     * @return  bool
     */
    public function send()
    {
        try {
            // Log every email that gets sent out
            if (is_array($this->sendTo) && ! empty($this->sendTo)) {
                $tpl   = $this->tplUsed ? basename($this->tplUsed) : $this->subjectUsed;
                $email = implode(', ', array_keys($this->sendTo));
                Vine_Log::logEvent($tpl . ' was emailed to ' . $email);
            }

            // Send the email
            return parent::send();
        } catch (Exception $e) {
            // Handle exception but continue runtime execution
            Vine_Exception::handle($e);

            // Failed to send email
            return FALSE;
        }
    }

    /**
     * Email-client ready escaped message with line breaks.
     * ---
     * @param   string
     * @return  string
     */
    public static function message($input)
    {
        return nl2br(Vine_Html::output($input));
    }

    /**
     * Email-client ready escaped line without line breaks.
     * ---
     * @param   string
     * @return  string
     */
    public static function line($input)
    {
        return Vine_Html::output($input);
    }
}
