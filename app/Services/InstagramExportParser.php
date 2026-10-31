<?php

namespace App\Services;

use JsonException;
use InvalidArgumentException;

class InstagramExportParser
{
    public function parse(string $json): array
    {
        try {
            $data = json_decode(
                $json,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (JsonException $exception) {
            throw new InvalidArgumentException(
                'O arquivo enviado não contém um JSON válido.',
                previous: $exception
            );
        }

        if (! is_array($data)) {
            throw new InvalidArgumentException(
                'A estrutura do arquivo JSON é inválida.'
            );
        }

        if (array_is_list($data)) {
            $entries = $data;
        } elseif (
            isset($data['relationships_following'])
            && is_array($data['relationships_following'])
            && array_is_list($data['relationships_following'])
        ) {
            $entries = $data['relationships_following'];
        } else {
            throw new InvalidArgumentException(
                'A estrutura do arquivo JSON é inválida.'
            );
        }

        foreach ($entries as $entry) {
            if (
                ! is_array($entry)
                || ! array_key_exists('string_list_data', $entry)
                || ! is_array($entry['string_list_data'])
                || ! array_is_list($entry['string_list_data'])
            ) {
                throw new InvalidArgumentException(
                    'A estrutura do arquivo JSON é inválida.'
                );
            }
        }

        $usernames = [];

        foreach ($entries as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $stringListData = $entry['string_list_data'] ?? null;

            if (! is_array($stringListData)) {
                continue;
            }

            $entryUsernames = 0;

            foreach ($stringListData as $item) {
                if (! is_array($item)) {
                    continue;
                }

                $username = $this->extractUsername($item);

                if ($username !== null) {
                    $usernames[] = $username;
                    $entryUsernames++;
                }
            }
        }

        return $usernames;
    }

    private function extractUsername(array $item): ?string
    {
        $value = $item['value'] ?? null;

        if (is_string($value) && trim($value) !== '') {
            return trim($value);
        }

        $href = $item['href'] ?? null;

        if (! is_string($href)) {
            return null;
        }

        $parts = parse_url($href);

        if (
            ! is_array($parts)
            || strtolower($parts['scheme'] ?? '') !== 'https'
            || strtolower($parts['host'] ?? '') !== 'www.instagram.com'
            || isset($parts['query'])
            || isset($parts['fragment'])
        ) {
            return null;
        }

        $path = $parts['path'] ?? '';

        if (! preg_match(
            '#^/(?:_u/)?([A-Za-z0-9._]+)/?$#',
            $path,
            $matches
        )) {
            return null;
        }

        return $matches[1];
    }
}