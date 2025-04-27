<?php

use Illuminate\Support\Facades\Blade;
use Modules\User\App\Models\User;

test('component renders correctly', function () {
    // Render the component
    $output = Blade::render('<x-header.user-link />');

    // Assert that the output is not empty
    expect($output)->not->toBeEmpty();
    expect($output)->toContain('user-link');
    expect($output)->toContain('user-link-icon');
    expect($output)->toContain('user-link-count');
});

test('component renders on dashboard correctly', function () {
    $response = $this->actingAs(User::factory()->create())->get(route('dashboard'));

    $response->assertStatus(200);
    $response->assertSee('user-link');
});
