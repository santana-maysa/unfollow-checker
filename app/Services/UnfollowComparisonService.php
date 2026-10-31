<?php

namespace App\Services;

use App\DTOs\InstagramComparisonResult;

class UnfollowComparisonService
{
    public function __construct(
        private InstagramDataNormalizer $normalizer
    ) {}

    public function compare(
        array $followers,
        array $following
    ): InstagramComparisonResult {

        $followers = $this->normalizer->normalize($followers);
        $following = $this->normalizer->normalize($following);

        $notFollowingBack = array_values(
            array_diff($following, $followers)
        );

        $mutualFollowers = array_values(
            array_intersect($followers, $following)
        );

        $notFollowing = array_values(
            array_diff($followers, $following)
        );

        return new InstagramComparisonResult(
            followers: $followers,
            following: $following,
            notFollowingBack: $notFollowingBack,
            mutualFollowers: $mutualFollowers,
            notFollowing: $notFollowing,
        );
    }
}