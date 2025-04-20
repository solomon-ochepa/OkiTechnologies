<?php

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Modules\Auth\app\Livewire\ForgotPassword;
use Modules\Auth\app\Livewire\ResetPassword;
use Modules\User\App\Models\User;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(ForgotPassword::class)
        ->set('username', $user->username)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(ForgotPassword::class)
        ->set('username', $user->username)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) {
        $response = $this->get('/reset-password/' . $notification->token);

        $response->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(ForgotPassword::class)
        ->set('username', $user->username)
        ->call('sendPasswordResetLink');

    Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
        $response = Livewire::test(ResetPassword::class, ['token' => $notification->token])
            ->set('email', $user->email)
            ->set('password', 'password')
            ->set('password_confirmation', 'password')
            ->call('resetPassword');

        $response
            ->assertHasNoErrors()
            ->assertRedirect(route('login', absolute: false));

        return true;
    });
});
