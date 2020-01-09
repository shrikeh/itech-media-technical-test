<?php

declare(strict_types=1);

namespace spec\TechTest\BusinessLogic\Twitter\Tweet;

use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\UriInterface;
use TechTest\BusinessLogic\Twitter\Tweet\Tweet;
use TechTest\BusinessLogic\Twitter\Tweet\User\User;

final class TweetCollectionSpec extends ObjectBehavior
{
    public function it_returns_tweets(UriInterface $profile, UriInterface $image): void
    {
        $now = new DateTimeImmutable();
        $then = new DateTimeImmutable('yesterday');
        $user = new User(
            $profile->getWrappedObject(),
            $image->getWrappedObject(),
            'Barney Rubble',
            'flintstones'
        );

        $firstTweet = new Tweet($now, $user, 'foo');
        $secondTweet = new Tweet($then, $user, 'bar');
        $this->beConstructedWith([
            $firstTweet,
            $secondTweet,
        ]);

        $this->getTweets()->shouldIterateAs([
            $then->getTimestamp() => $secondTweet,
            $now->getTimestamp() => $firstTweet,
        ]);
    }
}
