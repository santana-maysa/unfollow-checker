<?php

use Illuminate\Http\UploadedFile;

it('displays the application homepage', function () {
    $response = $this->get('/');

    $response->assertOk()
        ->assertViewIs('unfollow.index')
        ->assertSee('Unfollow Checker')
        ->assertSee('Descubra quem não segue você de volta.')
        ->assertSee('Arquivos de seguidores')
        ->assertSee('Arquivo de seguindo');
});

it('processes Instagram export files successfully', function () {
    $followers = UploadedFile::fake()->createWithContent(
        'followers_1.json',
        json_encode([
            [
                'string_list_data' => [
                    ['value' => 'ana'],
                ],
            ],
            [
                'string_list_data' => [
                    ['value' => 'maria'],
                ],
            ],
        ])
    );

    $following = UploadedFile::fake()->createWithContent(
        'following.json',
        json_encode([
            'relationships_following' => [
                [
                    'string_list_data' => [
                        ['value' => 'ana'],
                    ],
                ],
                [
                    'string_list_data' => [
                        ['value' => 'pedro'],
                    ],
                ],
            ],
        ])
    );

    $response = $this->post(route('unfollow.analyze'), [
        'followers' => [$followers],
        'following' => $following,
    ]);

    $response->assertOk()
        ->assertViewIs('unfollow.results')
        ->assertViewHas('statistics', [
            'totalFollowers' => 2,
            'totalFollowing' => 2,
            'notFollowingBack' => 1,
            'mutualFollowers' => 1,
            'notFollowing' => 1,
        ]);
});

it('processes multiple followers files', function () {
    $followers1 = UploadedFile::fake()->createWithContent(
        'followers_1.json',
        json_encode([
            [
                'string_list_data' => [
                    ['value' => 'ana'],
                ],
            ],
        ])
    );

    $followers2 = UploadedFile::fake()->createWithContent(
        'followers_2.json',
        json_encode([
            [
                'string_list_data' => [
                    ['value' => 'maria'],
                ],
            ],
            [
                'string_list_data' => [
                    ['value' => 'joao'],
                ],
            ],
        ])
    );

    $following = UploadedFile::fake()->createWithContent(
        'following.json',
        json_encode([
            'relationships_following' => [
                [
                    'string_list_data' => [
                        ['value' => 'ana'],
                    ],
                ],
                [
                    'string_list_data' => [
                        ['value' => 'pedro'],
                    ],
                ],
            ],
        ])
    );

    $response = $this->post(route('unfollow.analyze'), [
        'followers' => [$followers1, $followers2],
        'following' => $following,
    ]);

    $response->assertOk()
        ->assertViewHas('statistics', [
            'totalFollowers' => 3,
            'totalFollowing' => 2,
            'notFollowingBack' => 1,
            'mutualFollowers' => 1,
            'notFollowing' => 2,
        ]);
});

it('rejects invalid JSON content', function () {
    $followers = UploadedFile::fake()->createWithContent(
        'followers_1.json',
        '{ json inválido'
    );

    $following = UploadedFile::fake()->createWithContent(
        'following.json',
        json_encode([
            'relationships_following' => [],
        ])
    );

    $response = $this->post(route('unfollow.analyze'), [
        'followers' => [$followers],
        'following' => $following,
    ]);

    $response->assertRedirect()
        ->assertSessionHasErrors('files');
});

it('rejects requests without followers files', function () {
    $following = UploadedFile::fake()->createWithContent(
        'following.json',
        json_encode([
            'relationships_following' => [],
        ])
    );

    $response = $this->post(route('unfollow.analyze'), [
        'following' => $following,
    ]);

    $response->assertRedirect()
        ->assertSessionHasErrors('followers');
});

it('rejects files larger than 10 MB', function () {
    $followers = UploadedFile::fake()->createWithContent(
        'followers_1.json',
        json_encode([
            [
                'string_list_data' => [
                    ['value' => 'ana'],
                ],
            ],
        ])
    );

    $following = UploadedFile::fake()->createWithContent(
        'following.json',
        json_encode([
            'relationships_following' => [],
        ])
    );

    $largeFile = UploadedFile::fake()->create(
        'followers_2.json',
        10241,
        'application/json'
    );

    $response = $this->post(route('unfollow.analyze'), [
        'followers' => [$followers, $largeFile],
        'following' => $following,
    ]);

    $response->assertRedirect()
        ->assertSessionHasErrors('followers.1');
});

it('rejects files with an extension other than json', function () {
    $followers = UploadedFile::fake()->createWithContent(
        'followers_1.txt',
        json_encode([
            [
                'string_list_data' => [
                    ['value' => 'ana'],
                ],
            ],
        ])
    );

    $following = UploadedFile::fake()->createWithContent(
        'following.json',
        json_encode([
            'relationships_following' => [],
        ])
    );

    $response = $this->post(route('unfollow.analyze'), [
        'followers' => [$followers],
        'following' => $following,
    ]);

    $response->assertRedirect()
        ->assertSessionHasErrors('followers.0');
});

it('rejects more than 20 followers files', function () {
    $followers = [];

    for ($i = 1; $i <= 21; $i++) {
        $followers[] = UploadedFile::fake()->createWithContent(
            "followers_{$i}.json",
            json_encode([])
        );
    }

    $following = UploadedFile::fake()->createWithContent(
        'following.json',
        json_encode([
            'relationships_following' => [],
        ])
    );

    $response = $this->post(route('unfollow.analyze'), [
        'followers' => $followers,
        'following' => $following,
    ]);

    $response->assertRedirect()
        ->assertSessionHasErrors('followers');
});