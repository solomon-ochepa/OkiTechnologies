<?php

use Illuminate\Support\Facades\Blade;
use Modules\User\App\Models\User;

test('component renders correctly', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $output = Blade::render('<x-sidebar.profile />');

    expect($output)->not->toBeEmpty();
});
