<?php

use Illuminate\Support\Facades\Blade;
use Modules\User\App\Models\User;

test('component renders correctly', function () {
    // Render the component
    $output = Blade::render('<x-header.notification-link />');

    // Assert that the output is not empty
    expect($output)->not->toBeEmpty();
    expect($output)->toContain('notification-link');
    expect($output)->toContain('notification-link-icon');
    expect($output)->toContain('notification-link-count');
});

test('component renders on dashboard correctly', function () {
    $response = $this->actingAs(User::factory()->create())->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('notification-link');
});
