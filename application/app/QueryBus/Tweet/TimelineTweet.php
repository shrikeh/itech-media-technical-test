<?php

declare(strict_types=1);

namespace App\QueryBus\Tweet;

use DateTimeImmutable;
use Psr\Http\Message\UriInterface;

final class TimelineTweet implements \JsonSerializable
{
    public const KEY_CREATED = 'created';
    public const KEY_TEXT = 'text';
    public const KEY_URI = 'uri';
    public const KEY_SCREEN_NAME = 'screen_name';

    private DateTimeImmutable $created;

    private string $text;

    private UriInterface $profileImageUri;
    /**
     * @var string
     */
    private $screenName;

    /**
     * TimelineTweet constructor.
     * @param DateTimeImmutable $created
     * @param UriInterface $profileImageUri
     * @param string $screenName
     * @param string $text
     */
    public function __construct(
        DateTimeImmutable $created,
        UriInterface $profileImageUri,
        string $screenName,
        string $text
    ) {
        $this->created = $created;
        $this->profileImageUri = $profileImageUri;
        $this->text = $text;
        $this->screenName = $screenName;
    }

    /**
     * @return string
     */
    public function getScreenName(): string
    {
        return $this->screenName;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getText();
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }

    /**
     * @return UriInterface
     */
    public function getProfileImageUri(): string
    {
        return (string) $this->profileImageUri;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return [
            self::KEY_CREATED => $this->getCreated()->format(DATE_ATOM),
            self::KEY_URI => $this->getProfileImageUri(),
            self::KEY_SCREEN_NAME => $this->getScreenName(),
            self::KEY_TEXT => $this->getText(),
        ];
    }
}
