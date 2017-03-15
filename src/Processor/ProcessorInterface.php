<?php

namespace SamKnows\Processor;

use SamKnows\Reader\ReaderInterface;

interface ProcessorInterface
{
    /**
     * @param ReaderInterface $reader
     *
     * @throws \Exception
     *
     * @return void
     */
    public function process(ReaderInterface $reader);
}
