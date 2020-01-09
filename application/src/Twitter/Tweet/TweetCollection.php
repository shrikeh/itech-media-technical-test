<?php

declare(strict_types=1);

namespace TechTest\BusinessLogic\Twitter\Tweet;

use Ds\Map;

final class TweetCollection
{
    /**
     * @var Map
     */
    private Map $tweets;

    /**
     * TweetCollection constructor.
     * @param iterable $tweets
     */
    public function __construct(iterable $tweets)
    {
        $this->tweets = new Map();

        foreach ($tweets as $tweet) {
            $this->addTweet($tweet);
        }
    }

    /**
     * @return iterable
     */
    public function getTweets(): iterable
    {
        foreach ($this->tweets->ksorted() as $timestamp => $tweet) {
            yield $timestamp => $tweet;
        }
    }

    /**
     * @param Tweet $tweet
     */
    private function addTweet(Tweet $tweet): void
    {
        $this->tweets->put($tweet->getCreated()->getTimestamp(), $tweet);
    }
}
