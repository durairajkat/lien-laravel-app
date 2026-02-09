<?php

/**
 * SwiftMailer Wrapper
 * ---
 * @author     Tell Konkle <tellkonkle@gmail.com>
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
class Vine_Email_SwiftMailer implements Vine_Email_Interface
{
    /**
     * Preview email only?
     * ---
     * @var  bool
     */
    protected $previewMode = FALSE;

    /**
     * Preview email data.
     * ---
     * @var  array
     */
    protected $previewData = [];

    /**
     * Email recipients.
     * ---
     * ) [
     * )     'email' => 'name',
     * )     'email' => 'name',
     * )     'email' => 'name',
     * ) ];
     * ---
     * @var  array
     */
    protected $to   = [];
    protected $cc   = [];
    protected $bcc  = [];
    protected $real = ['to' => [], 'cc' => [], 'bcc' => []];

    /**
     * Email source and sender (usually the same).
     * ---
     * ['email', 'name'];
     * ---
     * @var  array
     */
    protected $from    = [];
    protected $replyTo = [];

    /**
     * Email body and subject.
     * ---
     * @var  string
     */
    protected $body    = NULL;
    protected $subject = NULL;

    /**
     * Data to use in file-based email template.
     * ---
     * @var  array
     */
    protected $data = [];

    /**
     * Email attachments.
     * ---
     * ) [
     * )     'path' => 'name',
     * )     'path' => 'name',
     * )     'path' => 'name',
     * ) ];
     * ---
     * @var  array
     */
    protected $attachments = [];

    /**
     * Inline image attachments.
     * ---
     * @var  array
     */
    protected $images = [];

    /**
     * Is email HTML?
     * ---
     * @var  bool
     */
    protected $isHtml = TRUE;

    /**
     * Is email using file-based template?
     * ---
     * @var  bool
     */
    protected $isTpl  = FALSE;

    /**
     * Path to email template when not setting the message body directly.
     * ---
     * @var  string
     */
    protected $tplPath = NULL;

    /**
     * Unique message ID.
     * ---
     * @var  string
     */
    protected $messageId = NULL;

    /**
     * Configuration array.
     * ---
     * @var  array
     */
    protected $config = [];

    /**
     * Instance of Swift_Mailer.
     * ---
     * @var  object
     */
    protected $mailer = NULL;

    /**
     * Class constructor. Load configuration array.
     * ---
     * @param   array
     * @return  void
     */
    public function __construct(array $config)
    {
        try {
            // Verify libary path
            if ( ! is_file($config['library-path'])) {
                throw new VineMissingFileException('Path to SwiftMailer autoloader is '
                        . 'not valid: ' . $config['library-path']);
            }

            // Initialize SwiftMailer autoloader
            require_once $config['library-path'];

            // Save configuration and get Swift_Mailer instance
            $this->config = $config;
            $this->mailer = $this->getMailer();
        // Fatal error
        } catch (VineFileException $e) {
            Vine_Exception::handle($e); exit;
        }
    }

    /**
     * Get a list of all of the intended recipients for this email.
     * ---
     * @return  array  ['email' => 'name'] format.
     */
    public function getRecipients()
    {
        return $this->real;
    }

    /**
     * @see  Vine_Email_Inteface::inPreviewMode()
     */
    public function inPreviewMode()
    {
        return (bool) $this->previewMode;
    }

    /**
     * @see  Vine_Email_Interface::setPreviewMode()
     */
    public function setPreviewMode($preview = TRUE)
    {
        $this->previewMode = (bool) $preview;
    }

    /**
     * @see  Vine_Email_Interface::setPreviewData()
     */
    public function setPreviewData(array $data)
    {
        $this->previewData = $data;
    }

    /**
     * @see  Vine_Email_Interface::clear()
     */
    public function clear($recipients = TRUE, $message = TRUE, $attachments = TRUE)
    {
        // Reset unique message ID
        $this->messageId = NULL;

        // Clear email's recipients
        if ($recipients) {
            $this->to   = [];
            $this->cc   = [];
            $this->bcc  = [];
            $this->real = ['to' => [], 'cc' => [], 'bcc' => []];
        }

        // Clear email's body, subject, & data
        if ($message) {
            $this->body    = NULL;
            $this->subject = NULL;
            $this->data    = [];
            $this->isHtml  = TRUE;
            $this->isTpl   = FALSE;
        }

        // Clear email's attachments
        if ($attachments) {
            $this->attachments = [];
            $this->images      = [];
        }
    }

    /**
     * @see  Vine_Email_Interface::setTo()
     */
    public function setTo($email, $name = NULL)
    {
        return $this->to = Vine_Email::toRecipients($email, $name, $this->to);
    }

    /**
     * @see  Vine_Email_Interface::setCc()
     */
    public function setCc($email, $name = NULL)
    {
        return $this->cc = Vine_Email::toRecipients($email, $name, $this->cc);
    }

    /**
     * @see  Vine_Email_Interface::setBcc()
     */
    public function setBcc($email, $name = NULL)
    {
        return $this->bcc = Vine_Email::toRecipients($email, $name, $this->bcc);
    }

    /**
     * @see  Vine_Email_Interface::setFrom()
     */
    public function setFrom($email, $name = NULL)
    {
        $this->from = [$email, $name];
    }

    /**
     * @see  Vine_Email_Interface::setReplyTo()
     */
    public function setReplyTo($email, $name = NULL)
    {
        $this->replyTo = [$email, $name];
    }

    /**
     * @see  Vine_Email_Interface::setSubject()
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @see  Vine_Email_Interface::setBody()
     */
    public function setBody($body, $html = TRUE)
    {
        try {
            // Verify info
            if ( ! is_string($body)) {
                throw new InvalidArgumentException('Argument 1 must be a string.');
            }

            // Save info, reset template info
            $this->body    = $body;
            $this->isHtml  = (bool) $html;
            $this->isTpl   = FALSE;
            $this->tplPath = NULL;
        } catch (InvalidArgumentException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * @see  Vine_Email_Interface::setData()
     */
    public function setData(array $data, $merge = FALSE)
    {
        // (default) Replace existing data
        if (FALSE === $merge) {
            $this->data = $data;
        // (optional) Merge with existing data
        } else {
            $this->data = Vine_Array::extend(TRUE, $this->data, $data);
        }
    }

    /**
     * @see  Vine_Email_Interface::getData()
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @see  Vine_Email_Interface::get()
     */
    public function get($field, $escape = FALSE)
    {
        if ($this->inPreviewMode()) {
            $prev = Vine_Array::getKey($this->previewData, $field);
            $real = Vine_Array::getKey($this->data, $field);
            $data = strlen($real) ? $real : $prev;
            return $escape ? Vine_Html::output($data) : $data;
        } else {
            $data = Vine_Array::getKey($this->data, $field);
            return $escape ? Vine_Html::output($data) : $data;
        }
    }

    /**
     * @see  Vine_Email_Interface::setTpl()
     */
    public function setTpl($path, $html = TRUE)
    {
        try {
            // Get absolute file path
            $path = realpath($path);

            // Verify template exists and is readable
            if ( ! is_file($path) || ! is_readable($path)) {
                throw new VineFileException('Email template path invalid or not '
                        . 'readable: ' . $path);
            }

            // Save info, reset body info
            $this->tplPath = $path;
            $this->isHtml  = (bool) $html;
            $this->isTpl   = TRUE;
            $this->body    = NULL;
        // Fatal exception
        } catch (VineFileException $e) {
            Vine_Exception::handle($e); exit;
        }
    }

    /**
     * @see  Vine_Email_Interface::setAttachment()
     */
    public function setAttachment($path, $name = NULL)
    {
        $this->attachments[$path] = $name;
    }

    /**
     * @see  Vine_Email_Interface::setImage()
     */
    public function setImage($path, $id)
    {
        $this->images[$id] = $path;
    }

    /**
     * @see  Vine_Email_Interface::render()
     */
    public function render($message = NULL)
    {
        // Use string message body set via setBody()
        if (FALSE === $this->isTpl) {
            return $this->body;
        // Use template message body set with setTpl()
        } else {
            return $this->parseTpl($message);
        }
    }

    /**
     * @see  Vine_Email_Interface::send()
     */
    public function send()
    {
        try {
            // Save intended recipients (so they can be seen in test mode)
            $this->real = [
                'to'  => $this->to,
                'cc'  => $this->cc,
                'bcc' => $this->bcc,
                'all' => array_merge($this->to, $this->cc, $this->bcc),
            ];

            // Reset all recipients and send email to test email account
            if ($this->config['test-mode'] && strlen($this->config['test-email'])) {
                $this->to   = [$this->config['test-email'] => 'Test Account'];
                $this->cc   = [];
                $this->bcc  = [];
            }

            // Compile email message
            $message = Swift_Message::newInstance();
            $message->setSubject($this->subject);
            $message->setBody($this->render($message), $this->getContentType(), Vine::UNICODE);
            $message->addPart($this->renderPlainText(), 'text/plain');

            // Use default "from" address
            if (empty($this->from)) {
                $message->setFrom(
                    $this->config['from-email'],
                    $this->config['from-name']
                );
            // Use developer specified "from" address
            } else {
                $message->setFrom($this->from[0], $this->from[1]);
            }

            // Set the reply-to address if applicable
            if ( ! empty($this->replyTo)) {
                $message->setReplyTo($this->replyTo[0], $this->replyTo[1]);
            }

            // Add primary email recipients
            foreach ($this->to as $email => $name) {
                $message->addTo($email, $name);
            }

            // Add carbon recipients
            if ( ! empty($this->cc)) {
                foreach ($this->cc as $email => $name) {
                    $message->addCc($email, $name);
                }
            }

            // Add blind carbon recipients
            if ( ! empty($this->bcc)) {
                foreach ($this->bcc as $email => $name) {
                    $message->addBcc($email, $name);
                }
            }

            // Add file attachments
            if ( ! empty($this->attachments)) {
                // Loop through each file attachment
                foreach ($this->attachments as $path => $name) {
                    // Compile attachment with custom filename
                    if (strlen($name)) {
                        $file = Swift_Attachment::fromPath($path)->setFilename($name);
                    // Compile attachmnt with filename as it appears in path
                    } else {
                        $file = Swift_Attachment::fromPath($path);
                    }

                    // Add attachment
                    $message->attach($file);
                }
            }

            // (string) Save message ID (for tracking)
            $this->messageId = $message->getHeaders()->get('Message-ID')->__toString();

            // (bool) Send the email
            if ( ! empty($this->real['all'])) {
                return (bool) $this->mailer->send($message);
            // (bool) No recipients were set
            } else {
                return FALSE;
            }
        } catch(Exception $e) {
            // Handle exception but continue runtime execution
            Vine_Exception::handle($e);

            // Failed to send email
            return FALSE;
        }
    }

    /**
     * Generate applicable embed ID for a specified inline image attachment.
     * ---
     * @param   scalar  Embed ID.
     * @param   object  Instance of Swift_Message.
     * @return  string  Embed code.
     */
    protected function image($id, $message = NULL)
    {
        // Image request but nothing provided
        if ( ! ($path = (isset($this->images[$id]) ? $this->images[$id] : NULL))) {
            return;
        }

        // Get absolute path to file
        $path = Vine_Registry::getSetting('images-clients') . $path;

        // Production mode
        if ( ! $this->inPreviewMode() && $message) {
            return $message->embed(Swift_Image::fromPath($path));
        }

        // Test mode
        if ($this->inPreviewMode()) {
            return 'data:image/jpg;base64,' . base64_encode(file_get_contents($path));
        }
    }

    /**
     * Should emails be sent as test mode to test email?
     * ---
     * @return  bool
     */
    protected function isTestMode()
    {
        return $this->config['test-mode'] && strlen($this->config['test-email']);
    }

    /**
     * Get email's content type.
     * ---
     * @return  string
     */
    private function getContentType()
    {
        // Email is plain text
        if (FALSE === $this->isHtml) {
            return 'text/plain';
        // Email is HTML content
        } else {
            return 'text/html';
        }
    }

    /**
     * Get Swift_Mailer instance with applicable transporter instance.
     * ---
     * @return  object  Instance of Swift_Mailer.
     */
    private function getMailer()
    {
        try {
            return Swift_Mailer::newInstance($this->getTransporter());
        } catch (Swift_SwiftException $e) {
            Vine_Exception::handle($e);
        }
    }

    /**
     * Get the appropriate SwiftMailer component for sending emails.
     * ---
     * @return  object  Instance of Swift_SmtpTransport or Swift_MailTransport.
     */
    private function getTransporter()
    {
        // Send emails via SMTP using Swift_SmtpTransport
        if (strlen($this->config['smtp-host'])) {
            // Initialize Swift's SMTP transport component
            $transporter = Swift_SmtpTransport::newInstance(
                $this->config['smtp-host'],
                (int) $this->config['smtp-port']
                //$this->config['smtp-security']
            );

            // Not all SMTP hosts will use a username
            if (strlen($this->config['smtp-user'])) {
                $transporter->setUsername($this->config['smtp-user']);
            }

            // Not all SMTP hosts will require a password
            if (strlen($this->config['smtp-pass'])) {
                $transporter->setPassword($this->config['smtp-pass']);
            }

            // Losen security
            if ('tls' === $this->config['smtp-security']
                && method_exists($transporter, 'setStreamOptions')
            ) {
                $transporter->setStreamOptions([
                    'ssl' => [
                        'allow_self_signed' => TRUE,
                        'verify_peer'       => FALSE,
                        'verify_peer_name'  => FALSE,
                    ]
                ]);
            }

            // (object) Swift_SmtpTransport
            return $transporter;
        }

        // (object) Swift_MailTransport, send emails via mail()
        return Swift_MailTransport::newInstance();
    }

    /**
     * Parse an HTML or plaintext template, using PHP's output buffer to allow template to
     * act like a "view" or "page."
     * ---
     * @param   object  [optional] Message object.
     * @return  string  Compiled message.
     */
    private function parseTpl($message = NULL)
    {
        // Start a new output buffer
        ob_start();

        // Load the template to generate the email body
        include $this->tplPath;

        // Return the output buffer result
        return ob_get_clean();
    }

    /**
     * Render a plain text version of the email.
     * ---
     * @param   object  [optional] Message object.
     * @return  string  Compiled plain text message.
     */
    private function renderPlainText($message = NULL)
    {
        // Use string message body set via setBody()
        if (FALSE === $this->isTpl) {
            $body = $this->body;
        // Use template message body set with setTpl()
        } else {
            $body = $this->parseTpl($message);
        }

        // (string) Convert HTML to text
        $html = new Vine_Html();
        $body = $html->htmlToText($body);
        return $body;
    }
}
