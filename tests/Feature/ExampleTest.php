<?php

use function Pest\Laravel\getJson;

it('Should return status code 200', function () {
    getJson('/', ['Content-Type' => 'application/json'])
        ->assertStatus(200);
});
