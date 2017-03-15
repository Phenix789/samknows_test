<?php

namespace SamKnows\Tests\Unit\Reader;

use SamKnows\Model\Metric;
use SamKnows\Reader\JsonFileReader;

class JsonFileReaderTest extends \PHPUnit_Framework_TestCase
{
    public function testReader()
    {
        $content  = [
            new Metric(1, 'download', 1, '2017-01-01 00:00:00'),
            new Metric(1, 'download', 1, '2017-01-01 00:00:00'),
            new Metric(1, 'download', 1, '2017-01-01 00:00:00'),
            new Metric(1, 'download', 1, '2017-01-01 00:00:00'),
        ];
        $filename = $this->createJsonFileFromMetrics($content);

        $reader  = new JsonFileReader($filename);
        $metrics = iterator_to_array($reader->get());

        $this->assertCount(4, $metrics);
        $this->assertEquals($content, $metrics);
    }

    /**
     * @dataProvider dataInvalidJsonFile
     * @expectedException \Exception
     */
    public function testInvalidJsonFile(string $content)
    {
        $filename = $this->createJsonFile($content);
        $reader   = new JsonFileReader($filename);
        iterator_to_array($reader->get());
    }

    /**
     * @return array
     */
    public function dataInvalidJsonFile()
    {
        return [
            [''],
            ['{{{'],
        ];
    }

    /**
     * @param Metric[] $metrics
     *
     * @return string
     */
    private function createJsonFileFromMetrics(array $metrics)
    {
        $data = [];
        foreach ($metrics as $metric) {
            $data[] = [
                'unit_id' => $metric->unit,
                'metrics' => [
                    $metric->name => [
                        [
                            'timestamp' => $metric->timestamp,
                            'value'     => $metric->value,
                        ],
                    ],
                ],
            ];
        }

        return $this->createJsonFile(json_encode($data));
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function createJsonFile(string $content)
    {
        $filename = '/tmp/phpunit.jsonfilereader.json.' . random_int(0, 1000);
        $stream   = fopen($filename, 'w+');
        fwrite($stream, $content);
        fseek($stream, 0);
        fclose($stream);

        return $filename;
    }
}
