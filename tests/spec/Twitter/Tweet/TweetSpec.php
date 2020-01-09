<?php

declare(strict_types=1);

namespace spec\TechTest\BusinessLogic\Twitter\Tweet;

use DateTimeImmutable;
use GuzzleHttp\Psr7\Uri;
use PhpSpec\ObjectBehavior;
use Psr\Http\Message\UriInterface;
use TechTest\BusinessLogic\Twitter\Tweet\User\User;

final class TweetSpec extends ObjectBehavior
{
    public function it_returns_the_created_date_time(UriInterface $orofile, UriInterface $image): void
    {
        $now = new DateTimeImmutable();
        $user = new User(
            $orofile->getWrappedObject(),
            $image->getWrappedObject(),
            'Barney Rubble',
            'flintstones'
        );
        $text = 'Foo bar baz';
        $this->beConstructedWith($now, $user, $text);

        $this->getCreated()->shouldReturn($now);
    }

    public function user(UriInterface $orofile, UriInterface $image): void
    {
        $now = new DateTimeImmutable();
        $user = new User(
            $orofile->getWrappedObject(),
            $image->getWrappedObject(),
            'Barney Rubble',
            'flintstones'
        );
        $text = 'Foo bar baz';
        $this->beConstructedWith($now, $user, $text);

        $this->getUser()->shouldReturn($user);
    }

    public function it_returns_the_text(UriInterface $orofile, UriInterface $image): void
    {
        $now = new DateTimeImmutable();
        $user = new User(
            $orofile->getWrappedObject(),
            $image->getWrappedObject(),
            'Barney Rubble',
            'flintstones'
        );
        $text = 'Foo bar baz';
        $this->beConstructedWith($now, $user, $text);

        $this->getText()->shouldReturn($text);
    }
}
