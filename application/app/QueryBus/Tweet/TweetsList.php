<?php

declare(strict_types=1);

namespace App\QueryBus\Tweet;

use Ds\Set;
use JsonSerializable;

final class TweetsList implements JsonSerializable
{
    /** @var Set */
    private Set $tweets;

    /**
     * TweetsList constructor.
     * @param iterable $tweets
     */
    public function __construct(iterable $tweets)
    {
        $this->tweets = new Set();

        foreach ($tweets as $tweet) {
            $this->addTweet($tweet);
        }
    }

    /**
     * @param TimelineTweet $tweet
     */
    private function addTweet(TimelineTweet $tweet): void
    {
        $this->tweets->add($tweet);
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return $this->tweets->toArray();
    }
}
