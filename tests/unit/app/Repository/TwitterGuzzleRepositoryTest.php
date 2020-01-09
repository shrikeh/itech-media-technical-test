<?php

declare(strict_types=1);

namespace Tests\Unit\App\Repository;

use App\Repository\Parser\TweetParserInterface;
use App\Repository\TwitterGuzzleRepository;
use DateTimeImmutable;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Teapot\StatusCode;
use TechTest\BusinessLogic\Twitter\Tweet\Tweet;
use TechTest\BusinessLogic\Twitter\Tweet\User\User;

final class TwitterGuzzleRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsATweetCollection(): void
    {
        /** @var ClientInterface $client */
        $client = $this->prophesize(ClientInterface::class);
        /** @var TweetParserInterface $parser */
        $parser = $this->prophesize(TweetParserInterface::class);

        $body = 'foo';

        $response = new Response(
            StatusCode::OK,
            [],
            $body
        );

        $profile = $this->prophesize(UriInterface::class);
        $image = $this->prophesize(UriInterface::class);

        $user = new User(
            $profile->reveal(),
            $image->reveal(),
            'Barney Rubble',
            'flintstones'
        );

        $now = new DateTimeImmutable();

        $tweet = new Tweet($now, $user, 'foo');

        $client->send(Argument::type(RequestInterface::class))->willReturn($response);
        $parser->parse($body)->will(function () use ($tweet) {
            yield $tweet;
        });

        $repository = new TwitterGuzzleRepository(
            $client->reveal(),
            $parser->reveal()
        );

        $this->assertSame(
            [$now->getTimestamp() => $tweet],
            iterator_to_array($repository->fetch()->getTweets())
        );
    }
}
