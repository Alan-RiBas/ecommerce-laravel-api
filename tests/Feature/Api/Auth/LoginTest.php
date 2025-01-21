<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\postJson;

test('should auth user', function () {

    $password = 'abc@123';
    $user = User::factory()->create([
        'password' => Hash::make($password),
    ]);

    $data = [
        'email' => $user->email,
        'password' => $password,
    ];

    postJson(route('auth.login', $data))
        ->assertOk()
        ->assertJsonStructure(['token']);
});

test('shold not auth user with wrong password', function () {

    $password = 'abc@123';
    $user = User::factory()->create([
        'password' => Hash::make($password),
    ]);

    $data = [
        'email' => $user->email,
        'password' => 'worng-password',
    ];


    postJson(route('auth.login', $data))
        ->assertStatus(401)
        ->assertJsonStructure(['message']);
});

test('shold not auth user with wrong email', function () {

    $email = 'joe@doe.com';
    $password = 'abc@123';
    $user = User::factory()->create([
        'password' => Hash::make($password),
    ]);

    $data = [
        'email' => $email,
        'password' => $user->password,
    ];

    postJson(route('auth.login', $data))
        ->assertStatus(401)
        ->assertJsonStructure(['message']);
});

describe('validations', function (){
    it('should require email', function () {
        postJson(route('auth.login', [
            'email' => '',
            'password' => Hash::make('abc@123')
            ]))
            ->assertStatus(400)
            ->assertJsonStructure(['message']);
    });
});
