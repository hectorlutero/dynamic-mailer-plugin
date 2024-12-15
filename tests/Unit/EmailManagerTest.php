<?php

namespace Tests\Unit;

use DynamicMailer\EmailManager;
use PHPUnit\Framework\TestCase;

class EmailManagerTest extends TestCase
{
    private $emailManager;

    protected function setUp(): void
    {
        $this->emailManager = new EmailManager();
    }

    public function testCanSendEmail()
    {
        $to = 'test@example.com';
        $subject = 'Test Subject';
        $body = '<h1>Test Body</h1>';
        $attachments = [];

        $result = $this->emailManager->send($to, $subject, $body, $attachments);
        $this->assertTrue($result);
    }

    public function testCanSendEmailWithAttachments()
    {
        $to = 'hectorsimandev@gmail.com';
        $subject = 'Test Subject with Attachment';
        $body = '<h1>Test Body</h1>';
        $attachments = [__DIR__ . '/fixtures/test-attachment.txt'];

        $result = $this->emailManager->send($to, $subject, $body, $attachments);

        $this->assertTrue($result);
    }
}
