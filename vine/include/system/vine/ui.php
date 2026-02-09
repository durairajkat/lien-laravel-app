<?php

/**
 * VineUI2 Front-End Extension
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Ui
{
    /**
     * DOM class names for error and success messages.
     */
    const SUCCESS_CLASS = 'alert success';
    const ERROR_CLASS   = 'alert failure';

    /**
     * Get error or success messages.
     * ---
     * @param   bool    [optional] Clear messages once retrieved? Default = TRUE.
     * @return  string  Empty string if no messages found.
     */
    public static function getMessages($clear = TRUE)
    {
        // Get error/success messages and error details
        $message = Vine_Session::getMessage($clear);
        $errors  = Vine_Session::getErrors($clear);

        // No messages, return an empty string
        if ( ! is_array($message) && empty($errors)) {
            return '';
        }

        // Success message
        if (TRUE === $message['status']) {
            return '<div class="' . self::SUCCESS_CLASS . '">'
                  . $message['message']
                  . "</div>\n";
        }

        // Begin error message
        $output = '<div class="' . self::ERROR_CLASS . '">' . "\n";

        // There was an error message
        if (is_array($message)) {
            $output .= $message['message'] . "\n";
        }

        // There are error details
        if ( ! empty($errors)) {
            // Begin an unordered list
            $output .= "<ul>\n";

            // Loop through each error we have
            foreach ($errors as $error) {
                $output .= '<li>' . $error . "</li>\n";
            }

            // End unordered list
            $output .= "</ul>\n";
        }

        // End error message
        $output .= "</div>\n";

        // Return a browser-ready HTML result
        return $output;
    }

    /**
     * @see  Vine_Session::setErrors()
     */
    public static function setErrors(array $errors)
    {
        Vine_Session::setErrors($errors);
    }

    /**
     * @see  Vine_Session::setMessage()
     */
    public static function setMessage($status, $message)
    {
        Vine_Session::setMessage($status, $message);
    }

    /**
     * See if an error or success message exists and needs to be displayed.
     * ---
     * @return  bool  TRUE if an error or success message is ready to be displayed.
     */
    public static function messageExists()
    {
        return Vine_Session::messageExists();
    }
}
