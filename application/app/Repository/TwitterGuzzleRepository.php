<?php

declare(strict_types=1);

namespace App\Repository;

use App\Repository\Parser\TweetParserInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use TechTest\BusinessLogic\Twitter\Repository\TweetsRepositoryInterface;
use TechTest\BusinessLogic\Twitter\Tweet\TweetCollection;

final class TwitterGuzzleRepository implements TweetsRepositoryInterface
{
    public const ENDPOINT = 'statuses/home_timeline.json';

    /**
     * @var ClientInterface
     */
    private ClientInterface $client;
    /**
     * @var TweetParserInterface
     */
    private TweetParserInterface $parser;

    /**
     * TwitterGuzzleRepository constructor.
     * @param ClientInterface $client
     * @param TweetParserInterface $parser
     */
    public function __construct(
        ClientInterface $client,
        TweetParserInterface $parser
    ) {
        $this->client = $client;
        $this->parser = $parser;
    }

    /**
     * @return TweetCollection
     * @throws GuzzleException
     * @throws \Exception
     */
    public function fetch(): TweetCollection
    {
        $response = $this->client->send($this->createRequest());

        if ($tweets = $response->getBody()) {
            return new TweetCollection($this->parser->parse($tweets->getContents()));
        }
    }

    /**
     * @return RequestInterface
     * @throws \Exception
     */
    private function createRequest(): RequestInterface
    {
        return new Request('GET', self::ENDPOINT);
    }
}
