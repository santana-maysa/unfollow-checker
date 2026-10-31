<?php

namespace App\Services;

class InstagramDataNormalizer
{
    public function normalize(array $usernames): array
    {
        $normalizedUsernames = [];

        foreach ($usernames as $username) {
            if (! is_string($username)) {
                continue;
            }

            $username = strtolower(trim($username));

            if ($username === '') {
                continue;
            }

            $normalizedUsernames[$username] = $username;
        }

        return array_values($normalizedUsernames);
    }
}