<?php

declare(strict_types=1);

namespace TechTest\BusinessLogic\Twitter\Repository;

use TechTest\BusinessLogic\Twitter\Tweet\TweetCollection;

interface TweetsRepositoryInterface
{
    /**
     * @return TweetCollection
     */
    public function fetch(): TweetCollection;
}
