+<?php
use App\User;
use App\Notifications\PostCommented;
use Illuminate\Support\Facades\Notification;

class NotifyUsersTest extends FeatureTestCase
{
    /** @test */
    function the_subscribes_receive_a_notification_when_post_is_commented()
    {
        Notification::fake();

        // Arrange
        $post = $this->createPost();

        $subscriber = factory(User::class)->create();
        $subscriber->subscribeTo($post);

        $writer = factory(User::class)->create();
        $writer->subscribeTo($post);

        $comment = $writer->comment($post, 'Un comentario cualquiera');

        // Assert
        Notification::assertSentTo(
            $subscriber, PostCommented::class, function ($notification) use ($comment) {
                return $notification->comment->id == $comment->id;
        });

        // The author of the comment shouldn't be notified even if is a subscriber
        Notification::assertNotSentTo($writer, PostCommented::class);
    }
}
