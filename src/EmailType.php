<?php

namespace DynamicMailer;

abstract class EmailType
{
    protected $templatePath;
    protected $data;

    public function __construct($data = [])
    {
        $this->data = $data;
    }

    abstract public function content(): string;

    public function getData(): array
    {
        return $this->data;
    }

    public function getTemplatePath(): string
    {
        $config = require __DIR__ . '/config/email-config.php';
        $themeDir = $config['templates']['theme_override_path'];
        $pluginDir = __DIR__ . '/templates';

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
