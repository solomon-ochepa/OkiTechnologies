<?php

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Livewire\Livewire;
use Modules\Auth\app\Livewire\Login;
use Modules\User\App\Models\User;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertOk();
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = Livewire::test(Login::class)
        ->set('username', $user->email)
        ->set('password', 'password')
        ->call('login');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = Livewire::test(Login::class)
        ->set('username', $user->email)
        ->set('password', 'wrong-password')
        ->call('login');

    $response->assertHasErrors('username');

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->withoutMiddleware(ValidateCsrfToken::class)->post('/logout');

    $this->assertGuest();
});
