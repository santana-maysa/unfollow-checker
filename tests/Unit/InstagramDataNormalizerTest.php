<?php

use App\Services\InstagramDataNormalizer;

beforeEach(function () {
    $this->normalizer = new InstagramDataNormalizer();
});

it('converts usernames to lowercase', function () {
    $result = $this->normalizer->normalize([
        'Maria',
        'JOAO',
        'Pedro',
    ]);

    expect($result)->toBe([
        'maria',
        'joao',
        'pedro',
    ]);
});

it('removes extra spaces', function () {
    $result = $this->normalizer->normalize([
        ' maria ',
        ' joao',
        'pedro ',
    ]);

    expect($result)->toBe([
        'maria',
        'joao',
        'pedro',
    ]);
});

it('removes duplicate usernames', function () {
    $result = $this->normalizer->normalize([
        'maria',
        'Maria',
        'MARIA',
        'joao',
    ]);

    expect($result)->toBe([
        'maria',
        'joao',
    ]);
});

it('ignores empty and invalid values', function () {
    $result = $this->normalizer->normalize([
        'maria',
        '',
        '   ',
        null,
        123,
    ]);

    expect($result)->toBe([
        'maria',
    ]);
});