<?php
/*
Plugin Name: DynamicMailer
Description: A customizable email notification plugin
Version: 1.0.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/src/EmailType.php';
require_once __DIR__ . '/src/EmailManager.php';
require_once __DIR__ . '/src/Mail.php';
