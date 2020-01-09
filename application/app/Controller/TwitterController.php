<?php

declare(strict_types=1);

namespace App\Controller;

use App\Message\GetTweetsMessage;
use App\QueryBus\TweetQueryBusInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class TwitterController
{
    /**
     * @var TweetQueryBusInterface
     */
    private TweetQueryBusInterface $queryBus;

    /**
     * TwitterController constructor.
     * @param TweetQueryBusInterface $queryBus
     */
    public function __construct(TweetQueryBusInterface $queryBus)
    {
        $this->queryBus = $queryBus;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/twitter")
     * @Method({"GET", "HEAD"})
     */
    public function __invoke(Request $request): JsonResponse
    {
        $tweetsList = $this->queryBus->queryTimeline(new GetTweetsMessage());

        return new JsonResponse($tweetsList, 200);
    }
}
