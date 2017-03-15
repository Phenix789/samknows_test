<?php

namespace SamKnows\Reader;

use SamKnows\Model\Metric;

class JsonFileReader implements ReaderInterface
{
    /**
     * @var string
     */
    private $filename;

    /**
     * JsonFileReader constructor.
     *
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return \Generator
     *
     * @throws \Exception
     */
    public function get()
    {
        $data = $this->loadData();
        foreach ($data as $unit) {
            $id = $unit['unit_id'];
            foreach ($unit['metrics'] as $name => $metrics) {
                foreach ($metrics as $metric) {
                    //Normalize float value
                    $value = $metric['value'];
                    if (is_float($value)) {
                        $value = $value * 1000;
                    }

                    yield new Metric($id, $name, $value, $metric['timestamp']);
                }
            }
        }
    }

    /**
     * return array
     *
     * @throws \Exception
     */
    private function loadData()
    {
        $data = json_decode(file_get_contents($this->filename), true);
        if (null === $data && JSON_ERROR_NONE !== json_last_error()) {
            throw new \Exception(sprintf('Invalid json file %s : %s', $this->filename, json_last_error_msg()));
        }

        return $data;
    }
}
