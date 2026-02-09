<?php

/**
 * Email Interface
 * ---
 * @author     Tell Konkle <tellkonkle@gmail.com>
 * @copyright  (c) 2011-2019 Tell Konkle. All rights reserved.
 */
interface Vine_Email_Interface
{
    /**
     * See if in preview mode (used for setting default preview data in templates).
     * ---
     * @return  bool
     */
    public function inPreviewMode();

    /**
     * Set email in preview mode (used for setting default preview data in templates).
     * ---
     * @param   bool
     * @return  void
     */
    public function setPreviewMode($preview = TRUE);

    /**
     * Set preview email data. When preview data is set, the data will be used in place
     * of the regular data if the applicable fields for the regular data has not been
     * provided.
     * ---
     * @param   array
     * @return  void
     */
    public function setPreviewData(array $data);

    /**
     * Clear email recipients, message body/data, and attachments.
     * ---
     * @param   bool
     * @param   bool
     * @param   bool
     * @return  void
     */
    public function clear($recipients = TRUE, $message = TRUE, $attachments = TRUE);

    /**
     * Add a primary recipient.
     * ---
     * @param   string
     * @param   string
     * @return  array
     */
    public function setTo($email, $name = NULL);

    /**
     * Add a carbon copy recipient.
     * ---
     * @param   string
     * @param   string
     * @return  array
     */
    public function setCc($email, $name = NULL);

    /**
     * Add a blind carbon copy recipient.
     * ---
     * @param   string
     * @param   string
     * @return  array
     */
    public function setBcc($email, $name = NULL);

    /**
     * The address email is being sent from. For some external SMTP hosts, like Rackspace
     * SMTP, the "from" address must be the SMTP username (i.e. noreply@domain.com).
     * ---
     * @param   string
     * @param   string
     * @return  void
     */
    public function setFrom($email, $name = NULL);

    /**
     * Set the reply-to address.
     * ---
     * @param   string
     * @param   string
     * @return  void
     */
    public function setReplyTo($email, $name = NULL);

    /**
     * Set the subject of the email.
     * ---
     * @param   string
     * @return  void
     */
    public function setSubject($subject);

    /**
     * Set the email body.
     * ---
     * @param   string
     * @param   bool
     * @return  void
     */
    public function setBody($body, $html = TRUE);

    /**
     * Set the email's dynamic data.
     * ---
     * @param   array  Dataset.
     * @param   bool   Merge with previous dataset? TRUE = Merge, FALSE = Replace.
     * @return  void
     */
    public function setData(array $data, $merge = FALSE);

    /**
     * Get the email's dynamic data.
     * ---
     * @return  array
     */
    public function getData();

    /**
     * Get a specific field from the email's dynamic data.
     * ---
     * @param   string  The name of the field whose data to get.
     * @param   bool    [optional] Escape data for HTML output?
     * @return  mixed
     */
    public function get($field, $escape = FALSE);

    /**
     * Use an HTML or plaintext template as the email body. Uses PHP's output buffer to
     * allow template to act like a "view" or "page."
     * ---
     * @param   string  Path to template.
     * @param   bool    [optional] Is this an HTML template? Default = TRUE.
     * @return  void
     */
    public function setTpl($path, $html = TRUE);

    /**
     * Add a file attachment.
     * ---
     * @param   string  Path to file.
     * @param   string  [optional] Attachment name.
     * @return  void
     */
    public function setAttachment($path, $name = NULL);

    /**
     * Add an embeddable image.
     * ---
     * @param   string  Path to image.
     * @param   scalar  Reference ID (i.e. 'profile_image').
     * @return  void
     */
    public function setImage($path, $id);

    /**
     * Render and return email's body content.
     * ---
     * @param   object  [optional] Message object.
     * @return  string  Compiled message.
     */
    public function render($message = NULL);

    /**
     * Send the email.
     * ---
     * @return  bool
     */
    public function send();
}
