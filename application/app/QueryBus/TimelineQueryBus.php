<?php

declare(strict_types=1);

namespace App\QueryBus;

use App\Message\GetTweetsMessage;
use App\QueryBus\Tweet\TweetsList;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class TimelineQueryBus implements TweetQueryBusInterface
{
    use HandleTrait;

    /**
     * VendorDeliveriesQueryBus constructor.
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param GetTweetsMessage $message
     * @return TweetsList
     */
    public function queryTimeline(GetTweetsMessage $message): TweetsList
    {
        return new TweetsList($this->handle($message));
    }
}
