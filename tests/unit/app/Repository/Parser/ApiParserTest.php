<?php

declare(strict_types=1);

namespace Tests\Unit\App\Repository\Parser;

use App\QueryBus\Tweet\TimelineTweet;
use App\Repository\Parser\ApiParser;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use TechTest\BusinessLogic\Twitter\Tweet\Tweet;

final class ApiParserTest extends TestCase
{
    /**
     * @test
     */
    public function itParsesTweets(): void
    {
        $responseBody = file_get_contents(FIXTURES_DIR . '/timeline.json');

        $parser = new ApiParser();

        $tweets = iterator_to_array($parser->parse($responseBody));

        /** @var Tweet $tweet */
        foreach ($tweets as $tweet) {
            $this->assertInstanceOf(Tweet::class, $tweet);
            $dateTime = new DateTimeImmutable('Wed Oct 10 20:19:24 +0000 2018');
            $text = 'To make room for more expression, we will now count all emojis as equal—including those with gender‍‍‍ and skin t… https://t.co/MkGjXf9aXm';
            $this->assertSame($dateTime->format(DATE_ATOM), $tweet->getCreated()->format(DATE_ATOM));
            $this->assertSame($text, $tweet->getText());
            $this->assertSame('https://t.co/8IkCzCDr19', (string) $tweet->getUser()->getProfile());
        }
    }
}
