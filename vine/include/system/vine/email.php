<?php

/**
 * Email Handler
 * ---
 * @author     Tell Konkle
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Email implements Vine_Email_Interface
{
    /**
     * Email configuration.
     * ---
     * @var  array
     */
    protected $config = [
        'from-email'    => NULL,
        'from-name'     => NULL,
        'library-name'  => NULL,
        'library-path'  => NULL,
        'smtp-host'     => NULL,
        'smtp-username' => NULL,
        'smtp-password' => NULL,
        'smtp-port'     => 25,
        'smtp-security' => NULL,
        'test-mode'     => FALSE,
        'test-email'    => NULL,
    ];

    /**
     * The addresses that this email is being sent to. Useful for logs.
     * ---
     * @var  array
     */
    protected $sendTo = [];

    /**
     * The email template used to send email. Useful for logs.
     * ---
     * @var  string
     */
    protected $tplUsed = NULL;

    /**
     * The subject line used to send email. Useful for logs.
     * ---
     * @var  string
     */
    protected $subjectUsed = NULL;

    /**
     * Class constructor.
     * ---
     * @param   array
     * @return  void
     */
    public function __construct($config = NULL)
    {
        // Load email config from registry
        if (NULL === $config || ! is_array($config) || empty($config)) {
            $config = Vine_Registry::getConfig(Vine::CONFIG_EMAILS);
        // Load custom email config
        } else {
            $config = array_merge($this->config, $config);
        }

        // Standardize library name
        if (NULL !== $config['library-name']) {
            $config['library-name'] = str_replace(' ', '', $config['library-name']);
            $config['library-name'] = strtolower($config['library-name']);
        }

        // Use SwiftMailer library to send emails
        if ('swiftmailer' === $config['library-name']) {
            $this->handle = new Vine_Email_SwiftMailer($config);
        // Use PHPMailer library to send emails
        } elseif ('phpmailer' === $config['library-name']) {
            $this->handle = new Vine_Email_PhpMailer($config);
        // Use PHP's native mail() function to send emails
        } else {
            $this->handle = new Vine_Email_Native($config);
        }
    }

    /**
     * Call a driver method.
     * ---
     * @param   string  Method name.
     * @param   array   Method arguments.
     * @return  mixed   Method result.
     */
    public function __call($name, $args)
    {
        return $this->handle->$name($args);
    }

    /**
     * @see  Vine_Email_Inteface::inPreviewMode()
     */
    public function inPreviewMode()
    {
        return $this->handle->inPreviewMode();
    }

    /**
     * @see  Vine_Email_Interface::setPreviewMode()
     */
    public function setPreviewMode($preview = TRUE)
    {
        $this->handle->setPreviewMode($preview);
    }

    /**
     * @see  Vine_Email_Interface::setPreviewData()
     */
    public function setPreviewData(array $data)
    {
        $this->handle->setPreviewData($data);
    }

    /**
     * @see  Vine_Email_Interface::clear()
     */
    public function clear($recipients = TRUE, $message = TRUE, $attachments = TRUE)
    {
        $this->handle->clear($recipients, $message, $attachments);
    }

    /**
     * @see  Vine_Email_Interface::setTo()
     */
    public function setTo($email, $name = NULL)
    {
        // Pass data to driver
        $this->handle->setTo($email, $name);

        // Compile recipient(s) list (used for event logs)
        return $this->sendTo = self::toRecipients($email, $name, $this->sendTo);
    }

    /**
     * @see  Vine_Email_Interface::setCc()
     */
    public function setCc($email, $name = NULL)
    {
        // Pass data to driver
        $this->handle->setCc($email, $name);

        // Compile recipient(s) list (used for event logs)
        return $this->sendTo = self::toRecipients($email, $name, $this->sendTo);
    }

    /**
     * @see  Vine_Email_Interface::setBcc()
     */
    public function setBcc($email, $name = NULL)
    {
        // Pass data to driver
        $this->handle->setBcc($email, $name);

        // Compile recipient(s) list (used for event logs)
        return $this->sendTo = self::toRecipients($email, $name, $this->sendTo);
    }

    /**
     * @see  Vine_Email_Interface::setFrom()
     */
    public function setFrom($email, $name = NULL)
    {
        $this->handle->setFrom($email, $name);
    }

    /**
     * @see  Vine_Email_Interface::setReplyTo()
     */
    public function setReplyTo($email, $name = NULL)
    {
        $this->handle->setReplyTo($email, $name);
    }

    /**
     * @see  Vine_Email_Interface::setSubject()
     */
    public function setSubject($subject)
    {
        $this->subjectUsed = $subject;
        $this->handle->setSubject($subject);
    }

    /**
     * @see  Vine_Email_Interface::setBody()
     */
    public function setBody($body, $html = TRUE)
    {
        $this->handle->setBody($body, $html);
    }

    /**
     * @see  Vine_Email_Interface::setData()
     */
    public function setData(array $data, $merge = FALSE)
    {
        $this->handle->setData($data, $merge);
    }

    /**
     * @see  Vine_Email_Interface::getData()
     */
    public function getData()
    {
        return $this->handle->getData();
    }

    /**
     * @see  Vine_Email_Interface::get()
     */
    public function get($field, $escape = FALSE)
    {
        return $this->handle->get($field, $escape);
    }

    /**
     * @see  Vine_Email_Interface::setTpl()
     */
    public function setTpl($path, $html = TRUE)
    {
        $this->tplUsed = $path;
        $this->handle->setTpl($path, $html);
    }

    /**
     * @see  Vine_Email_Interface::setAttachment()
     */
    public function setAttachment($path, $name = NULL)
    {
        $this->handle->setAttachment($path, $name);
    }

    /**
     * @see  Vine_Email_Interface::setImage()
     */
    public function setImage($path, $id)
    {
        $this->handle->setImage($path, $id);
    }

    /**
     * @see  Vine_Email_Interface::render()
     */
    public function render($message = NULL)
    {
        return $this->handle->render($message);
    }

    /**
     * @see  Vine_Email_Interface::send()
     */
    public function send()
    {
        return $this->handle->send();
    }

    /**
     * Apply a string or array of emails to a supplied recipients list.
     * ---
     * @param   string|array  Recipient(s) to append to supplied list.
     * @param   string        Recipient name. NULL if name is in array value in first argument.
     * @param   array         [reference] List to append recipients to.
     * @return  array         Updated array (also passed by reference.
     */
    public static function toRecipients($from, $name, array &$to)
    {
        // Set multiple email addresses
        if (is_array($from)) {
            foreach ($from as $k => $v) {
                // Email is flagged to be ignored (FALSE)
                if (FALSE === $v) {
                    continue;
                // [0 => 'email@foobar.com']
                } elseif (is_numeric($k)) {
                    $to[$v] = $name ? $name : NULL;
                // ['email@foobar.com' => 'Bob Smith']
                } elseif (is_string($k)) {
                    $to[$k] = $v;
                }
            }
        // Set a single address
        } elseif (FALSE !== $name) {
            $to[$from] = $name;
        }

        // Updated array (also passed by reference)
        return $to;
    }
}
