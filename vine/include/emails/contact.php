<?php

/**
 * Contact Form
 * ---
 * @author   Tell Konkle
 * @updated  2016-04-11
 */

// When email is in preview mode
if ($this->inPreviewMode()) {
    // Preview data
    $preview = [
        'first_name' => '{First Name}',
        'last_name'  => '{Last Name}',
        'subject'    => '{Subject Line}',
        'email'      => '{Email Address}',
        'phone'      => '{Phone Number}',
        'message'    => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. '
                     .  'Pellentesque maximus nunc vel magna ultrices, auctor efficitur '
                     .  'dolor gravida. Curabitur ut interdum orci, eget vestibulum '
                     .  'quam. Donec a pulvinar velit. Sed ac tristique nunc. Fusce '
                     .  'elementum ultrices tellus, non hendrerit lacus laoreet et.',
    ];

    // Set preview data (this data will be used if real data can't be found)
    $this->setPreviewData($preview);
}

?>

<?php require INC_PATH . 'emails/templates/header.tpl.php'; ?>

You have been contacted through the contact form at {domain.com}. Below
is the message you have been sent.

<br />

<table style="margin:15px 0 0;font-size:12px;">
    <tr>
        <td width="25%"><b>First Name:</b></td>
        <td style="padding:0 10px;">
            <?php echo WscEmail::line($this->get('first_name')); ?>
        </td>
    </tr>
    <tr>
        <td width="25%"><b>Last Name:</b></td>
        <td style="padding:0 10px;">
            <?php echo WscEmail::line($this->get('last_name')); ?>
        </td>
    </tr>
    <tr>
        <td width="25%"><b>Email Address:</b></td>
        <td style="padding:0 10px;">
            <?php echo WscEmail::line($this->get('email')); ?>
        </td>
    </tr>
    <tr>
        <td width="25%"><b>Phone Number:</b></td>
        <td style="padding:0 10px;">
            <?php echo WscEmail::line($this->get('phone')); ?>
        </td>
    </tr>
    <tr>
        <td width="25%"><b>Subject:</b></td>
        <td style="padding:0 10px;">
            <?php echo WscEmail::line($this->get('subject')); ?>
        </td>
    </tr>
    <tr>
        <td width="25%"><b>Message:</b></td>
        <td style="padding:0 10px;">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">
            <br />
            <?php echo nl2br(Vine_Html::output($this->get('message'))); ?>
        </td>
    </tr>
</table>

<?php require INC_PATH . 'emails/templates/footer.tpl.php'; ?>