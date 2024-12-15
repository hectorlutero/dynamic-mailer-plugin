<?php

namespace DynamicMailer;

use DynamicMailer\EmailManager;
use DynamicMailer\EmailType;

class Mail
{
    protected $subject = [];
    protected $to = [];
    protected $emailType;
    protected $emailManager;

    public function __construct($subject, $to, EmailType $emailType, EmailManager $emailManager)
    {
        $this->subject = $subject;
        $this->to = $to;
        $this->emailType = $emailType;
        $this->emailManager = $emailManager;
    }



    public function send()
    {
        $content = $this->emailType->render();
        $this->emailManager->send($this->to, $this->subject, $content);
    }

    // protected function buildHeaders()
    // {
    //     $headers = [];
    //     if (!empty($this->cc)) {
    //         $headers[] = 'Cc: ' . implode(',', $this->cc);
    //     }
    //     if (!empty($this->bcc)) {
    //         $headers[] = 'Bcc: ' . implode(',', $this->bcc);
    //     }
    //     return $headers;
    // }
}
