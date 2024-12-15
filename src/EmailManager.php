<?php

namespace DynamicMailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

class EmailManager
{
    private $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/config/email-config.php';
    }

    public function send($to, $subject, $body, $attachments = [])
    {
        // print_r([$to, $subject, $body, $attachments, $this->config]);
        // die;
        $mail = new PHPMailer(true);
        try {
            // SMTP settings
            $mail->isSMTP();
            error_log("Setting up SMTP connection to host: {$this->config['host']}");
            $mail->Host = $this->config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $this->config['username'];
            $mail->Password = $this->config['password'];
            $mail->SMTPSecure = $this->config['encryption'];
            $mail->Port = $this->config['port'];

            // Sender and recipient
            error_log("Setting sender: {$this->config['from']['address']} and recipient: {$to}");
            $mail->setFrom($this->config['from']['address'], $this->config['from']['name']);
            $mail->addAddress($to);

            // Content
            error_log("Preparing email content with subject: {$subject}");
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            // Attachments
            if (!empty($attachments)) {
                error_log("Processing " . count($attachments) . " attachments");
                foreach ($attachments as $file) {
                    error_log("Adding attachment: {$file}");
                    $mail->addAttachment($file);
                }
            }

            error_log("Attempting to send email...");
            $mail->send();
            error_log("Email sent successfully");

            return true;
        } catch (Exception $e) {
            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
