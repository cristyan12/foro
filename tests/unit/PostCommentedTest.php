<?php

use App\{Comment, User, Post};
use App\Notifications\PostCommented;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostCommentedTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    function it_builds_a_mail_message()
    {
        // Arrange
        $post = factory(Post::class)->create([
            'title' => 'Título del post',
        ]);

        $author = factory(User::class)->create([
            'name' => 'Cristyan Valera'
        ]);

        $comment = factory(Comment::class)->create([
            'post_id' => $post->id,
            'user_id' => $author->id
        ]);
            
        $notification = new PostCommented($comment);

        $subscriber = factory(User::class)->create();

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
