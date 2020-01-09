<?php

declare(strict_types=1);

namespace spec\TechTest\BusinessLogic\Twitter;

use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\UriInterface;
use TechTest\BusinessLogic\Twitter\Repository\TweetsRepositoryInterface;
use TechTest\BusinessLogic\Twitter\Tweet\Tweet;
use TechTest\BusinessLogic\Twitter\Tweet\TweetCollection;
use TechTest\BusinessLogic\Twitter\Tweet\User\User;

final class TwitterServiceSpec extends ObjectBehavior
{
    public function it_returns_the_twitter_messages(
        TweetsRepositoryInterface $twitterRepository, UriInterface $profile, UriInterface $image): void
    {
        $now = new DateTimeImmutable();
        $user = new User(
            $profile->getWrappedObject(),
            $image->getWrappedObject(),
            'Barney Rubble',
            'flintstones'
        );
        $tweet = new Tweet($now, $user, 'foo');
        $collection = new TweetCollection([$tweet]);
        $this->beConstructedWith($twitterRepository);
        $twitterRepository->fetch()->willReturn($collection);

        $this->fetchTweets()->shouldIterateAs([$tweet]);
    }
}
