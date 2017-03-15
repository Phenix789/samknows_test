<?php

namespace SamKnows\Model;

class Metric
{
    /**
     * @var int
     */
    public $unit;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $value;

    /**
     * @var string
     */
    public $timestamp;

    /**
     * Metric constructor.
     *
     * @param int    $unit
     * @param string $name
     * @param int    $value
     * @param string $timestamp
     */
    public function __construct(int $unit, string $name, int $value, string $timestamp)
    {
        $this->unit      = $unit;
        $this->name      = $name;
        $this->value     = $value;
        $this->timestamp = $timestamp;
    }
}
