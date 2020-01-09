<?php

declare(strict_types=1);

namespace App\Repository\Parser;

use DateTimeImmutable;
use GuzzleHttp\Psr7\Uri;
use stdClass;
use TechTest\BusinessLogic\Twitter\Tweet\Tweet;
use TechTest\BusinessLogic\Twitter\Tweet\User\User;

final class ApiParser implements TweetParserInterface
{
    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function parse($content): iterable
    {
        foreach ($this->deserialize($content) as $tweet) {
            yield new Tweet(
                new DateTimeImmutable($tweet->created_at),
                $this->getUser($tweet),
                $tweet->text
            );
        }
    }

    /**
     * @param stdClass $tweet
     * @return User
     */
    private function getUser(stdClass $tweet): User
    {
        $user = $tweet->user;
        return new User(
            new Uri($user->url),
            new Uri($user->profile_background_image_url_https),
            $user->name,
            $user->screen_name
        );
    }

    /**
     * @param string $content
     * @return array
     */
    private function deserialize(string $content): array
    {
        $decoded = json_decode($content, false);

        return is_array($decoded) ? $decoded : [$decoded];
    }
}
