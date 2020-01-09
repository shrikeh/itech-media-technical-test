<?php

declare(strict_types=1);

namespace App\QueryBus;

use App\Message\GetTweetsMessage;
use App\QueryBus\Tweet\TweetsList;

interface TweetQueryBusInterface
{
    /**
     * @param GetTweetsMessage $message
     * @return TweetsList
     */
    public function queryTimeline(GetTweetsMessage $message): TweetsList;
}
