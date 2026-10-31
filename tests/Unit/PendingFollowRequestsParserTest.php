
<?php

use App\Services\PendingFollowRequestsParser;
use InvalidArgumentException;

beforeEach(function () {
    $this->parser = new PendingFollowRequestsParser();
});

it('extracts username from a single pending request object', function () {
    $json = json_encode([
        'timestamp' => 1788296960,
        'media' => [],
        'label_values' => [
            ['label' => 'URL', 'value' => ''],
            ['label' => 'Nome', 'value' => 'Arley Magalhães'],
            ['label' => 'Nome de usuário', 'value' => 'jsear.exe'],
        ],
    ]);

    $result = $this->parser->parse($json);

    expect($result)->toBe(['jsear.exe']);
});

it('extracts usernames from multiple pending requests', function () {
    $json = json_encode([
        [
            'label_values' => [
                ['label' => 'Nome de usuário', 'value' => 'usuario1'],
            ],
        ],
        [
            'label_values' => [
                ['label' => 'Nome de usuário', 'value' => 'usuario2'],
            ],
        ],
    ]);

    $result = $this->parser->parse($json);

    expect($result)->toBe(['usuario1', 'usuario2']);
});

it('ignores entries without a username', function () {
    $json = json_encode([
        'label_values' => [
            ['label' => 'Nome', 'value' => 'Nome de exibição'],
            ['label' => 'URL', 'value' => ''],
        ],
    ]);

    $result = $this->parser->parse($json);

    expect($result)->toBe([]);
});

it('ignores empty usernames', function () {
    $json = json_encode([
        'label_values' => [
            ['label' => 'Nome de usuário', 'value' => '   '],
        ],
    ]);

    $result = $this->parser->parse($json);

    expect($result)->toBe([]);
});

it('throws an exception when the JSON is invalid', function () {
    expect(fn () => $this->parser->parse('{json inválido'))
        ->toThrow(InvalidArgumentException::class);
});

it('throws an exception when the JSON structure is invalid', function () {
    expect(fn () => $this->parser->parse('"texto"'))
        ->toThrow(InvalidArgumentException::class);
});
