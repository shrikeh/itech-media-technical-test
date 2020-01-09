<?php

declare(strict_types=1);

namespace TechTest\BusinessLogic\Twitter\Tweet\User;

use Psr\Http\Message\UriInterface;

final class User
{
    private string $screenName;

    private string $name;

    private UriInterface $profile;

    private UriInterface $profileImage;

    /**
     * User constructor.
     * @param UriInterface $profile
     * @param UriInterface $profileImage
     * @param string $name
     * @param string $screenName
     */
    public function __construct(UriInterface $profile, UriInterface $profileImage, string $name, string $screenName)
    {
        $this->profile = $profile;
        $this->profileImage = $profileImage;
        $this->name = $name;
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return UriInterface
     */
    public function getProfile(): UriInterface
    {
        return $this->profile;
    }

    /**
     * @return UriInterface
     */
    public function getProfileImage(): UriInterface
    {
        return $this->profileImage;
    }
}
