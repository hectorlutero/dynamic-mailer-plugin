<?php

namespace Tests\Integration;

use DynamicMailer\EmailManager;
use DynamicMailer\EmailTypes\PostUpdatedEmail;
use DynamicMailer\Mail;
use PHPUnit\Framework\TestCase;

class EmailIntegrationTest extends TestCase
{
    private $emailManager;

    protected function setUp(): void
    {
        $this->emailManager = new EmailManager();
    }

    public function testTemplateRendering()
    {
        $post = (object)[
            'title' => 'Template Test',
            'author' => 'Template Author',
            'content' => 'Template Content',
            'date' => date('Y-m-d')
        ];

        $emailType = new PostUpdatedEmail($post);
        $renderedContent = $emailType->render();

        $this->assertNotEmpty($renderedContent);
        $this->assertStringContainsString($post->title, $renderedContent);
    }

    public function testFullEmailFlow()
    {
        // Create test post data
        $post = (object)[
            'title' => 'Integration Test Post',
            'author' => 'Test Author',
            'content' => 'Test Content for Integration',
            'date' => date('Y-m-d')
        ];

        // Create email type
        $emailType = new PostUpdatedEmail($post);

        // Create mail instance
        $mail = new Mail(
            $emailType->getData()['subject'],
            'hectorsimandev@gmail.com',
            $emailType,
            $this->emailManager
        );
        $result = $mail->send();
        $this->assertNull($result);
        $this->assertStringContainsString($post->title, $emailType->render());
    }
}
