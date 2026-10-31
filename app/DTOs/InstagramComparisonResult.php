<?php

namespace App\DTOs;

class InstagramComparisonResult
{
    public function __construct(
        public readonly array $followers,
        public readonly array $following,
        public readonly array $notFollowingBack,
        public readonly array $mutualFollowers,
        public readonly array $notFollowing,
    ) {}

    public function getStatistics(): array
    {
        return [
            'totalFollowers' => count($this->followers),
            'totalFollowing' => count($this->following),
            'notFollowingBack' => count($this->notFollowingBack),
            'mutualFollowers' => count($this->mutualFollowers),
            'notFollowing' => count($this->notFollowing),
        ];
    }
}