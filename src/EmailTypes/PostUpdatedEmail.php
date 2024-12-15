<?php

namespace DynamicMailer\EmailTypes;

use DynamicMailer\EmailType;

class PostUpdatedEmail extends EmailType
{
    public function __construct($post)
    {

        parent::__construct([
            'subject' => 'Post Updated: ' . $post->title,
            'postTitle' => $post->title,
            'postAuthor' => $post->author,
            'postContent' => $post->content,
            'postDate' => $post->date ?? ''
        ]);
    }

    public function content(): string
    {
        return 'mail-template.posts.update';
    }
}
