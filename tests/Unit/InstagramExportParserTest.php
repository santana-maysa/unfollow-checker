<?php

use App\Services\InstagramExportParser;

beforeEach(function () {
    $this->parser = new InstagramExportParser();
});

it('extracts usernames from a followers JSON list', function () {
    $json = json_encode([
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
    ]);

    $result = $this->parser->parse($json);

    expect($result)->toBe(['maria', 'joao']);
});

it('extracts usernames from the relationships following structure', function () {
    $json = json_encode([
        'relationships_following' => [
            [
                'string_list_data' => [
                    ['value' => 'maria'],
                ],
            ],
            [
                'string_list_data' => [
                    ['value' => 'pedro'],
                ],
            ],
        ],
    ]);

    $result = $this->parser->parse($json);

    expect($result)->toBe(['maria', 'pedro']);
});

it('uses the Instagram profile URL when value is unavailable', function () {
    $json = json_encode([
        [
            'string_list_data' => [
                ['href' => 'https://www.instagram.com/maria/'],
            ],
        ],
    ]);

    $result = $this->parser->parse($json);

    expect($result)->toBe(['maria']);
});

it('rejects invalid JSON', function () {
    expect(fn () => $this->parser->parse('{json inválido'))
        ->toThrow(InvalidArgumentException::class);
});

it('rejects an incompatible JSON structure', function () {
    $json = json_encode([
        'dados' => [],
    ]);

    expect(fn () => $this->parser->parse($json))
        ->toThrow(InvalidArgumentException::class);
});

it('rejects a list with an incompatible entry structure', function () {
    $json = json_encode([
        [
            'nome' => 'Maria',
        ],
    ]);

    expect(fn () => $this->parser->parse($json))
        ->toThrow(InvalidArgumentException::class);
});

it('accepts a valid empty followers list', function () {
    $json = json_encode([]);

    $result = $this->parser->parse($json);

    expect($result)->toBe([]);
});

it('extracts usernames from Instagram following URLs with _u path', function () {
    $json = json_encode([
        'relationships_following' => [
            [
                'title' => 'werissiriano',
                'string_list_data' => [
                    [
                        'href' => 'https://www.instagram.com/_u/werissiriano',
                    ],
                ],
            ],
        ],
    ]);

    $result = $this->parser->parse($json);

    expect($result)->toBe(['werissiriano']);
});
