<?php

namespace SamKnows\Reader;

use SamKnows\Model\Metric;

interface ReaderInterface
{
    /**
     * Return bunch of metrics
     *
     * @return \Traversable|Metric[]
     */
    public function get();
}
