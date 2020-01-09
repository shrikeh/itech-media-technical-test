<?php

namespace App\Repository\Parser;

interface TweetParserInterface
{
    /**
     * @param mixed $content
     * @return iterable
     */
    public function parse($content): iterable;
}
