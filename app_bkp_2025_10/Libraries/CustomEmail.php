<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;

class CustomEmail extends Email
{
    private $loggedRecipients = [];
    private $loggedSubject = '';
    private $loggedMessage = '';

    public function __construct($config = null)
    {
        if ($config === null) {
            $config = config('Email');
        }
        parent::__construct($config);
        if (!is_dir(WRITEPATH . 'email-logs')) {
            mkdir(WRITEPATH . 'email-logs', 0755, true);
        }
    }

    // Override the setTo() method to log recipients
    public function setTo($to)
    {
        // Call the parent method
        parent::setTo($to);

        // Store recipients in the custom property
        if (is_array($to)) {
            $this->loggedRecipients = $to;
        } else {
            $this->loggedRecipients = [$to];
        }

        return $this;  // Maintain method chaining
    }

    // Override the setSubject() method to log the subject
    public function setSubject($subject)
    {
        // Call the parent method
        parent::setSubject($subject);

        // Store subject in the custom property
        $this->loggedSubject = $subject;

        return $this;  // Maintain method chaining
    }

    // Override the setMessage() method to log the message
    public function setMessage($message)
    {
        // Call the parent method
        parent::setMessage($message);

        // Store message in the custom property
        $this->loggedMessage = $message;

        return $this;  // Maintain method chaining
    }

    #Override the send() method to add logging
    public function send($autoClear = true): bool
    {
        $isSent = parent::send($autoClear);

        if (!$isSent) {
            log_message('error', $this->printDebugger(['headers']));
        }

        $this->logEmail($isSent);
        return $isSent;
    }

    #Function to log the email details
    private function logEmail($isSent)
    {
        $logFilePath = WRITEPATH . 'email-logs/email-log.log';

        $to = is_array($this->loggedRecipients) ? implode(', ', $this->loggedRecipients) : 'No recipients';
        $subject = $this->loggedSubject ?? 'No subject';
        $message = $this->loggedMessage ?? 'No message';
        $status = $isSent ? 'Success' : 'Failure';

        $logMessage = sprintf(
            "[%s] Email to: %s\nSubject: %s\nStatus: %s\nMessage:\n%s\n",
            date('Y-m-d H:i:s'),
            $to,
            $subject,
            $status,
            $message
        );



        file_put_contents($logFilePath, $logMessage, FILE_APPEND);
    }
}
