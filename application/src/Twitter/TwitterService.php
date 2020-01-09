<?php

declare(strict_types=1);

namespace TechTest\BusinessLogic\Twitter;

use Generator;
use TechTest\BusinessLogic\Twitter\Repository\TweetsRepositoryInterface;

final class TwitterService
{
    /**
     * @var TweetsRepositoryInterface
     */
    private TweetsRepositoryInterface $tweetsRepository;

    /**
     * TwitterService constructor.
     * @param TweetsRepositoryInterface $tweetsRepository
     */
    public function __construct(TweetsRepositoryInterface $tweetsRepository)
    {
        $this->tweetsRepository = $tweetsRepository;
    }

    /**
     * @return iterable
     */
    public function fetchTweets(): Generator
    {
        $tweets = $this->tweetsRepository->fetch();

        foreach ($tweets->getTweets() as $tweet) {
            yield $tweet;
        }
    }
}
