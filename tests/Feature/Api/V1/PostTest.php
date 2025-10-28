<?php

use App\Models\Post;
use function Pest\Laravel\{getJson, postJson, patchJson, deleteJson};

it('can fetch all posts', function () {
    // 1. Arrange
    Post::factory(3)->create();

    // 2. Act
    $response = getJson('/api/v1/posts');

    // 3. Assert
    $response->assertStatus(200);
    $response->assertJsonCount(3, 'data');
});

it('can fetch a single post', function () {
    // 1. Arrange
    $post = Post::factory()->create();

    // 2. Act
    $response = getJson('/api/v1/posts/' . $post->id);

    // 3. Assert
    $response->assertStatus(200);
    $response->assertJson([
        'data' => [
            'id' => $post->id,
            'title' => $post->title,
            'body' => $post->body,
        ]
    ]);
});

it('can create a new post', function () {
    // 1. Arrange
    $postData = [
        'title' => 'My First Post',
        'body' => 'This is the body of my first post.',
    ];

    // 2. Act
    $response = postJson('/api/v1/posts', $postData);

    // 3. Assert
    $response->assertCreated(); // assertStatus(201)
    $response->assertJson([
        'data' => $postData
    ]);

    $this->assertDatabaseHas('posts', $postData);
});

it('can update a post', function () {
    // 1. Arrange
    $post = Post::factory()->create();

    $updatedData = [
        'title' => 'My Updated Title',
    ];

    // 2. Act
    $response = patchJson('/api/v1/posts/' . $post->id, $updatedData);

    // 3. Assert
    $response->assertOk(); // assertStatus(200)
    $response->assertJson([
        'data' => [
            'id' => $post->id,
            'title' => 'My Updated Title',
            'body' => $post->body,
        ]
    ]);

    $this->assertDatabaseHas('posts', [
        'id' => $post->id,
        'title' => 'My Updated Title',
    ]);
});

it('can delete a post', function () {
    // 1. Arrange
    $post = Post::factory()->create();

    // 2. Act
    $response = deleteJson('/api/v1/posts/' . $post->id);

    // 3. Assert (Перевірка)
    $response->assertNoContent(); // assertStatus(204)

    $this->assertDatabaseMissing('posts', [
        'id' => $post->id,
    ]);
});
