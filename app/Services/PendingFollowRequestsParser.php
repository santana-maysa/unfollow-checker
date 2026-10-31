<?php

namespace App\Services;

use JsonException;
use InvalidArgumentException;

class PendingFollowRequestsParser
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
                'O arquivo de solicitações pendentes não contém um JSON válido.',
                previous: $exception
            );
        }

        if (! is_array($data)) {
            throw new InvalidArgumentException(
                'A estrutura do arquivo de solicitações pendentes é inválida.'
            );
        }

        $entries = array_is_list($data)
            ? $data
            : [$data];

        $usernames = [];

        foreach ($entries as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $labelValues = $entry['label_values'] ?? [];

            if (! is_array($labelValues)) {
                continue;
            }

            foreach ($labelValues as $item) {
                if (! is_array($item)) {
                    continue;
                }

               $label = $item['label'] ?? null;

                if (! is_string($label)) {
                    continue;
                }

                $label = mb_strtolower(trim($label));

                if (! in_array($label, [
                    'nome de usuário',
                    'nome de usuã¡rio',
                ], true)) {
                    continue;
                }

                $username = $item['value'] ?? null;

                if (is_string($username) && trim($username) !== '') {
                    $usernames[] = trim($username);
                }
            }
        }

        return $usernames;
    }
}
