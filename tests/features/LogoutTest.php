<?php

class LogoutTest extends FeatureTestCase
{
    /** @test */
    function a_user_can_logout()
    {
        $this->actingAs($user = $this->defaultUser());

        $response = $this->post(route('logout'))
            ->dontSeeIsAuthenticated();
    }
}
