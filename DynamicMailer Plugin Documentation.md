
## Plugin Overview

**Plugin Name**: DynamicMailer

**Author**: Hector Siman

DynamicMailer is a customizable email notification plugin designed for PHP projects. It allows developers to create dynamic email flows, override email types and templates through themes, and maintain a scalable, flexible system for sending notifications.

---

## Features

- **Custom Email Types**: Define new email types with specific data and templates.
- **Theme Overrides**: Customize email types and templates by adding them to the theme folder.
- **Dynamic Template Resolution**: Automatically resolves email templates from the theme or plugin directory.
- **Scalability**: Easily extend the system with new flows and templates.
- **Fluent Interface**: Supports chaining methods to define recipients and send emails seamlessly.

---

## Folder Structure

### Plugin Folder

dynamic-mailer/
├── src/
│   ├── EmailManager.php
│   ├── EmailType.php (base class)
│   ├── Mail.php (fluent mail class)
│   └── email-types/ (default email type classes)
│       └── PostUpdatedEmail.php
├── templates/
│   └── mail-template/
│       ├── posts/
│       │   └── update.php
├── email-config.php
└── my-plugin.php

### Theme Folder (Overrides)

your-theme/
└── email-types-templates/
    ├── PostUpdatedEmail.php (custom email type class)
    └── mail-template/
        ├── posts/
        │   └── update.php (custom email template)

---

## Key Components

### 1. EmailType Base Class

The `EmailType` class is the foundation for all email flows. It defines the data, template path, and rendering logic.

#### Example

namespace DynamicMailer

abstract class EmailType
{
    protected $templatePath;
    protected $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    abstract public function content(): string;

    public function getTemplatePath(): string
    {
        $themeDir = get_template_directory() . '/email-types-templates';
        $pluginDir = __DIR__ . '/../templates';

        $parts = explode('.', $this->content());
        $filePath = implode(DIRECTORY_SEPARATOR, $parts) . '.php';

        if (file_exists("$themeDir/$filePath")) {
            return "$themeDir/$filePath";
        }

        return "$pluginDir/$filePath";
    }

    public function render(): string
    {
        $template = $this->getTemplatePath();
        extract($this->data);

        ob_start();
        include $template;
        return ob_get_clean();
    }
}

### 2. Example Email Type: `PostUpdatedEmail`

This class defines an email type for notifying users about post updates.

#### Example

namespace DynamicMailer\EmailTypes;

use DynamicMailer\EmailType;

class PostUpdatedEmail extends EmailType
{
    public function __construct(public $post)
    {
        parent::__construct([
            'postTitle' => $this->post->title,
            'postAuthor' => $this->post->author,
        ]);
    }

    public function content(): string
    {
        return 'mail-template.posts.update';
    }
}

### 3. Fluent Mail Class

The `Mail` class provides a fluent interface for setting recipients and sending emails.

#### Example

namespace DynamicMailer;

use DynamicMailer\EmailType;

class Mail
{
    protected $to = [];
    protected $cc = [];
    protected $bcc = [];
    protected $emailType;

    public static function to($recipients)
    {
        $instance = new static();
        $instance->to = (array) $recipients;
        return $instance;
    }

    public function cc($recipients)
    {
        $this->cc = (array) $recipients;
        return $this;
    }

    public function bcc($recipients)
    {
        $this->bcc = (array) $recipients;
        return $this;
    }

    public function send(EmailType $emailType)
    {
        $this->emailType = $emailType;

        // Build the email with recipients and render the content
        $content = $emailType->render();

        // Call a mailer (e.g., PHP's mail function or a library like PHPMailer)
        $this->deliver($content);

        return true;
    }

    protected function deliver($content)
    {
        // Example: Use PHP's mail function or another mailer library
        mail(implode(',', $this->to), 'Subject', $content, $this->buildHeaders());
    }

    protected function buildHeaders()
    {
        $headers = [];
        if (!empty($this->cc)) {
            $headers[] = 'Cc: ' . implode(',', $this->cc);
        }
        if (!empty($this->bcc)) {
            $headers[] = 'Bcc: ' . implode(',', $this->bcc);
        }
        return implode("\r\n", $headers);
    }
}

---

## Integration

### Sending an Email in a Controller

Example: Sending an email when a post is updated.

#### Example Code

use DynamicMailer\Mail;
use DynamicMailer\EmailTypes\PostUpdatedEmail;

class PostController
{
    public function update(Request $request, $postId)
    {
        $post = Post::find($postId);
        $post->update($request->all());

        Mail::to($request->user()->email)
            ->cc(['cc@example.com'])
            ->bcc(['bcc@example.com'])
            ->send(new PostUpdatedEmail($post));
    }
}

---

## Dynamic Template Resolution

1. **Define the Template Path**: Each `EmailType` class specifies a path (e.g., `mail-template.posts.update`).
2. **Theme First**: The plugin checks if the file exists in the theme directory.
3. **Fallback**: If no theme override exists, it uses the default plugin template.

---

## Customization Workflow

1. **Create a Custom Email Type**:
    - Add a new class in the theme’s `email-types-templates` folder.
    - Extend the base `EmailType` or a plugin-defined email type.
2. **Customize Templates**:
    - Add templates in the theme’s `mail-template` folder.
    - Use the same structure and naming convention as the plugin.

---

## Summary

DynamicMailer is designed to provide developers with a powerful, extendable email system. By leveraging the `EmailType` class, `Mail` class, and theme overrides, you can customize email flows without altering the core plugin files. This approach ensures flexibility, scalability, and easy maintenance.