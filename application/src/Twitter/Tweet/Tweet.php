<?php

declare(strict_types=1);

namespace TechTest\BusinessLogic\Twitter\Tweet;

use DateTimeImmutable;
use Psr\Http\Message\UriInterface;
use TechTest\BusinessLogic\Twitter\Tweet\User\User;

final class Tweet
{
    /** @var DateTimeImmutable */
    private DateTimeImmutable $created;

    /**
     * @var User
     */
    private User $user;

    /**
     * @var string
     */
    private string $text;

    /**
     * Tweet constructor.
     * @param DateTimeImmutable $created
     * @param User $user
     * @param string $text
     */
    public function __construct(
        DateTimeImmutable $created,
        User $user,
        string $text
    ) {
        $this->created = $created;
        $this->user = $user;
        $this->text = $text;
    }

    /**
     * @return int
     */
    public function getCreated(): DateTimeImmutable
    {
        return $this->created;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
