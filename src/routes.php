<?php

use DynamicMailer\Router;
use DynamicMailer\Mail;
use DynamicMailer\EmailTypes\PostUpdatedEmail;
use DynamicMailer\EmailManager;

$router = new Router();


$router->get('/', function () {
    return  [
        'Hello' => 'World'
    ];
});

$router->post('/api/send-post-update', function ($data) {
    $post = (object)[
        'title' => $data['title'] ?? 'Default Title',
        'author' => $data['author'] ?? 'Default Author',
        'content' => $data['content'] ?? 'Default Content',
        'date' => $data['date'] ?? date('d/m/Y'),
    ];

    try {
        $mail = new Mail(
            $data['subject'] ?? 'Post Updated',
            $data['to'],
            $data['cc'] ?? [],
            $data['bcc'] ?? [],
            new PostUpdatedEmail($post),
            new EmailManager()
        );

        $mail->send();

        return [
            'success' => true,
            'message' => 'Email sent successfully'
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
});

$router->get('/api/test-template', function ($data) {
    $post = (object)[
        'title' => 'Test Post',
        'author' => 'Test Author'
    ];

    $email = new PostUpdatedEmail($post);
    $rendered = $email->render();

    return [
        'success' => true,
        'template' => $rendered
    ];
});

$router->dispatch();
