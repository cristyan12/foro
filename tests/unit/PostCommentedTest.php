<?php

use App\{Comment, User, Post};
use App\Notifications\PostCommented;
use Illuminate\Notifications\Messages\MailMessage;

class PostCommentedTest extends TestCase
{
    /** @test */
    function it_builds_a_mail_message()
    {
        // Arrange
        $post = new Post([
            'title' => 'Título del post',
        ]);

        $author = new User([
            'name' => 'Cristyan Valera'
        ]);

        $comment = new Comment();
        $comment->post = $post;
        $comment->user = $author;
            
        $notification = new PostCommented($comment);

        $subscriber = new User();

        // Act
        $message = $notification->toMail($subscriber);

        // Assert
        $this->assertInstanceOf(MailMessage::class, $message);

        $this->assertSame('Nuevo comentario en: Título del post', $message->subject);

        $this->assertSame(
            'Cristyan Valera escribió un comentario en: Título del post',
            $message->introLines[0]
        );

        $this->assertSame($comment->post->url, $message->actionUrl);
    }
}
