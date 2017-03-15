<?php

namespace SamKnows\Reader;

class ReaderFactory
{
    /**
     * @param mixed $arg
     *
     * @return ReaderInterface
     *
     * @throws \Exception
     */
    public static function create($arg)
    {
        if (is_string($arg) && pathinfo($arg, PATHINFO_EXTENSION) === 'json') {
            return new JsonFileReader($arg);
        }

        throw new \Exception('Unable to find a reader');
    }
}
