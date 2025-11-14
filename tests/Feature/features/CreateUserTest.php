<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('crea un usuario y comprueba que se almacena en la base de datos', function () {
    $user = User::factory()->create([
        'email' => 'testuser@example.com',
        'name' => 'Test User'
    ]);

    $exists = DB::table('users')->where('email', 'testuser@example.com')->where('name', 'Test User')->exists();
    expect($exists)->toBeTrue();
});
