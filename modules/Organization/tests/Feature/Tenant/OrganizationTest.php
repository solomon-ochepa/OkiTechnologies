<?php

// test('redirects guest to login', function () {
//     $response = $this->get(route('admin.organization.index'));

//     $response->assertRedirect(route('login'));
// });

// test('redirects user to dashboard', function () {
//     $user = User::factory()->create();
//     $this->actingAs($user);

//     $response = $this->get(route('admin.organization.index'));

//     $response->assertRedirect(route('dashboard'));
// });

// test('admin can access organization index', function () {
//     $user = User::factory()->create();
//     $this->actingAs($user);

//     $response = $this->get(route('admin.organization.index'));

//     $response->assertOk();
// });

// test('admin can create organization', function () {
//     $user = User::factory()->create();
//     $this->actingAs($user);

//     $response = $this->get(route('admin.organization.create'));

//     $response->assertOk();
// });

// test('admin can edit organization', function () {
//     $user = User::factory()->create();
//     $this->actingAs($user);

//     $response = $this->get(route('admin.organization.edit', ['organization' => 1]));

//     $response->assertOk();
// });

// test('admin can view an organization', function () {
//     $user = User::factory()->create();
//     $this->actingAs($user);

//     $response = $this->get(route('admin.organization.show', ['organization' => 1]));

//     $response->assertOk();
// });

// test('admin can delete organization', function () {
//     $user = User::factory()->create();
//     $this->actingAs($user);

//     $response = $this->get(route('admin.organization.delete', ['organization' => 1]));

//     $response->assertOk();
// });

// test('admin can restore organization', function () {
//     $user = User::factory()->create();
//     $this->actingAs($user);

//     $response = $this->get(route('admin.organization.restore', ['organization' => 1]));

//     $response->assertOk();
// });

// test('admin can force delete organization', function () {
//     $user = User::factory()->create();
//     $this->actingAs($user);

//     $response = $this->get(route('admin.organization.forceDelete', ['organization' => 1]));

//     $response->assertOk();
// });
