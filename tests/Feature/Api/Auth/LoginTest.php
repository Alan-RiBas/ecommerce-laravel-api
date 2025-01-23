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

describe('validations', function (){

    test('should require email', function () {
        postJson(route('auth.login', [
            'email' => 'aaa',
            'password' => Hash::make('abc@123')
            ]))
            ->assertStatus(400)
            ->assertJson(['email' => ['validation.email']]);
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

    test('should require password', function () {
        $password = 'abc@123';
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        postJson(route('auth.login', [
            'email' => $user->email,
            'password' => ''
            ]))
            ->assertStatus(400)
            ->assertJson(['password' => ['validation.required']]);
    });

});
