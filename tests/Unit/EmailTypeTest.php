<?php

namespace Tests\Unit;

use DynamicMailer\EmailTypes\PostUpdatedEmail;
use PHPUnit\Framework\TestCase;

class EmailTypeTest extends TestCase
{
    public function testCanCreateEmailType()
    {
        $post = (object)[
            'title' => 'Test Post',
            'author' => 'John Doe',
            'content' => 'This is a test post content.',
            'date' => '2023-09-21'
        ];

        $email = new PostUpdatedEmail($post);

        $templatePath = $email->getTemplatePath();

        $this->assertFileExists($templatePath);
    }

    public function testTemplateResolution()
    {
        $post = (object)[
            'title' => 'Test Post',
            'author' => 'John Doe',
            'content' => 'Test content',
            'date' => date('d/m/Y')
        ];

        $email = new PostUpdatedEmail($post);
        $templatePath = $email->getTemplatePath();

        $this->assertFileExists($templatePath);
    }
}
