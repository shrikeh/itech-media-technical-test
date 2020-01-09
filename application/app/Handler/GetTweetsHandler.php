<?php

declare(strict_types=1);

namespace App\Handler;

use App\Message\GetTweetsMessage;
use App\QueryBus\Tweet\TimelineTweet;
use App\QueryBus\Tweet\TweetsList;
use Generator;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use TechTest\BusinessLogic\Twitter\Tweet\Tweet;
use TechTest\BusinessLogic\Twitter\TwitterService;

final class GetTweetsHandler implements MessageHandlerInterface
{
    /** @var TwitterService */
    private TwitterService $twitterService;

    /**
     * GetTweetsHandler constructor.
     * @param TwitterService $twitterService
     */
    public function __construct(TwitterService $twitterService)
    {
        $this->twitterService = $twitterService;
    }

    /**
     * @param GetTweetsMessage $message
     * @return TweetsList
     */
    public function __invoke(GetTweetsMessage $message): iterable
    {
        return $this->fetchTimelineTweets();
    }

    /**
     * @return Generator
     */
    private function fetchTimelineTweets(): Generator
    {
        /** @var Tweet $tweet */
        foreach ($this->twitterService->fetchTweets() as $tweet) {
            yield $this->createTimelineTweet($tweet);
        }
    }

    private function createTimelineTweet(Tweet $tweet): TimelineTweet
    {
        return new TimelineTweet(
            $tweet->getCreated(),
            $tweet->getUser()->getProfileImage(),
            $tweet->getUser()->getScreenName(),
            $tweet->getText()
        );
    }
}
