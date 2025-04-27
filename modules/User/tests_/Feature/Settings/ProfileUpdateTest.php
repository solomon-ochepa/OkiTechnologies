<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use Modules\User\app\Livewire\Settings\Profile;
use Modules\User\App\Models\User;

test('profile page is displayed', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/settings/profile')->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();
    $phone = fake()->phoneNumber();

    $this->actingAs($user);

    $response = Livewire::test(Profile::class)
        ->set('form.first_name', 'Test')
        ->set('form.last_name', 'User')
        ->set('form.username', 'test')
        ->set('form.phone', $phone)
        ->set('form.email', 'test@example.com')
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    $user->refresh();

    expect($user->name)->toEqual('Test User');
    expect($user->first_name)->toEqual('Test');
    expect($user->last_name)->toEqual('User');
    expect($user->username)->toEqual('test');
    expect($user->phone)->toEqual($phone);
    expect($user->email)->toEqual('test@example.com');
    expect($user->email_verified_at)->toBeNull();
});

test('email verification status is unchanged when email address is unchanged', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test(Profile::class)
        ->set('form.first_name', 'Test')
        ->set('form.last_name', 'User')
        ->set('form.username', 'test')
        ->set('form.email', $user->email)
        ->call('updateProfileInformation');

    $response->assertHasNoErrors();

    expect($user->refresh()->email_verified_at)->not->toBeNull();
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('settings.delete-user-form')
        ->set('password', 'password')
        ->call('deleteUser');

    $response
        ->assertHasNoErrors()
        ->assertRedirect('/');

    expect($user->fresh())->toBeNull();
    expect(Auth::check())->toBeFalse();
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = Livewire::test('settings.delete-user-form')
        ->set('password', 'wrong-password')
        ->call('deleteUser');

    $response->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});
