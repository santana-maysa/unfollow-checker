<?php

use App\DTOs\InstagramComparisonResult;
use App\Services\InstagramDataNormalizer;
use App\Services\UnfollowComparisonService;

beforeEach(function () {
    $this->service = new UnfollowComparisonService(
        new InstagramDataNormalizer()
    );
});

it('identifies accounts that do not follow back', function () {
    $result = $this->service->compare(
        followers: ['ana', 'maria', 'joao'],
        following: ['ana', 'pedro', 'carlos']
    );

    expect($result->notFollowingBack)->toBe([
        'pedro',
        'carlos',
    ]);
});

it('identifies mutual followers', function () {
    $result = $this->service->compare(
        followers: ['ana', 'maria', 'joao'],
        following: ['ana', 'pedro', 'joao']
    );

    expect($result->mutualFollowers)->toBe([
        'ana',
        'joao',
    ]);
});

it('identifies followers that the user does not follow back', function () {
    $result = $this->service->compare(
        followers: ['ana', 'maria', 'joao'],
        following: ['ana']
    );

    expect($result->notFollowing)->toBe([
        'maria',
        'joao',
    ]);
});

it('normalizes usernames before comparing', function () {
    $result = $this->service->compare(
        followers: ['Maria', ' JOAO '],
        following: ['maria', 'Pedro']
    );

    expect($result->notFollowingBack)->toBe(['pedro'])
        ->and($result->mutualFollowers)->toBe(['maria'])
        ->and($result->notFollowing)->toBe(['joao']);
});

it('returns the correct statistics', function () {
    $result = $this->service->compare(
        followers: ['ana', 'maria', 'joao'],
        following: ['ana', 'pedro']
    );

    expect($result)->toBeInstanceOf(InstagramComparisonResult::class)
        ->and($result->getStatistics())->toBe([
            'totalFollowers' => 3,
            'totalFollowing' => 2,
            'notFollowingBack' => 1,
            'mutualFollowers' => 1,
            'notFollowing' => 2,
        ]);
});

it('handles empty lists', function () {
    $result = $this->service->compare(
        followers: [],
        following: []
    );

    expect($result->notFollowingBack)->toBe([])
        ->and($result->mutualFollowers)->toBe([])
        ->and($result->notFollowing)->toBe([])
        ->and($result->getStatistics())->toBe([
            'totalFollowers' => 0,
            'totalFollowing' => 0,
            'notFollowingBack' => 0,
            'mutualFollowers' => 0,
            'notFollowing' => 0,
        ]);
});