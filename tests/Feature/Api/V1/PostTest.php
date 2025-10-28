<?php

use function Pest\Laravel\getJson;

it('can fetch all posts', function () {
    $response = getJson('/api/v1/posts');

    $response->assertStatus(200);

    $response->assertJsonStructure([
        'data' => [],
    ]);
});
