<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Authenticate a user and mark them as 2FA verified in the session.
     */
    protected function actingAsVerified(\App\Models\User $user, $driver = null)
    {
        return $this->actingAs($user, $driver)->withSession(['auth.two_factor_verified' => true]);
    }
}
