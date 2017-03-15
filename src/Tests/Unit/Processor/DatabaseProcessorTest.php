<?php

namespace SamKnows\Tests\Unit\Processor;

use SamKnows\Model\Metric;
use SamKnows\Processor\DatabaseProcessor;
use SamKnows\Reader\ReaderInterface;

class DatabaseProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testProcessor()
    {
        $pdo = $this->createPDOMock();
        $reader = $this->createReaderMock();
        $processor = new DatabaseProcessor($pdo);

        $processor->process($reader);
    }

    /**
     * @param int $called
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|\PDO
     */
    private function createPDOMock(int $called = 10)
    {
        $statement = $this->getMock(\PDOStatement::class);
        $statement->expects($this->exactly($called))->method('execute');

        $pdo = $this->getMock(\PDO::class, ['prepare', 'beginTransaction', 'commit', 'exec'], [], '', false);
        $pdo->method('prepare')->willReturn($statement);
        $pdo->expects($this->once())->method('beginTransaction');
        $pdo->expects($this->once())->method('commit');
        $pdo->expects($this->exactly(3))->method('exec'); //Create tables x2 and process x1

        return $pdo;
    }

    /**
     * @param int $count
     *
     * @return \PHPUnit_Framework_MockObject_MockObject|ReaderInterface
     */
    private function createReaderMock(int $count = 10)
    {
        $metrics = [];
        for ($i = 0; $i < $count; ++$i) {
            $metrics[] = new Metric(1, 'download', 1, '2017-01-01 00:00:00');
        }

        $reader = $this->getMock(ReaderInterface::class, ['get']);
        $reader->method('get')->willReturn($metrics);

        return $reader;
    }
}
