<?php

class SupportMarkdownTest extends FeatureTestCase
{
    /** @test */
    function the_post_content_support_markdown()
    {
        $importantText = 'Un texto muy importante';

        $post = $this->createPost([
            'content' => "La primera parte del texto. **$importantText**. La última parte del texto"
        ]);

        $this->visit($post->url)
            ->seeInElement('strong', $importantText);
    }

    /** @test */
    function the_code_in_the_post_is_scaped()
    {
        $xssAttack = "<script>alert('Sharing code')</script>";

        $post = $this->createPost([
            'content' => "`$xssAttack`. Texto normal"
        ]);

        $this->visit($post->url)
            ->dontSee($xssAttack)
            ->seeText('Texto normal')
            ->seeText($xssAttack);
    }

    /** @test */
    function xss_attack()
    {
        $xssAttack = "<script>alert('Malicious JS!')</script>";

        $post = $this->createPost([
            'content' => "$xssAttack. Texto normal"
        ]);

        $this->visit($post->url)
            ->dontSee($xssAttack)
            ->seeText('Texto normal');
    }

    /** @test */
    function xss_attack_with_html()
    {
        $xssAttackHtml = "<img src=img.jpg>";

        $post = $this->createPost([
            'content' => "$xssAttackHtml. Texto normal"
        ]);

        $this->visit($post->url)
            ->seeText($xssAttackHtml);
    }
}
