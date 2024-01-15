<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
  use CreatesApplication;

  /**
   * @param array<string> $permissions
   */
  public function setUpUser(): void
  {
    $user = User::factory()->create();
    $this->actingAs($user, 'web');
  }
}
