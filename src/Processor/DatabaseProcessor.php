<?php

namespace SamKnows\Processor;

use SamKnows\Reader\ReaderInterface;

class DatabaseProcessor implements ProcessorInterface
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var \PDOStatement
     */
    private $statement;

    /**
     * DatabaseProcessor constructor.
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @inheritdoc
     */
    public function process(ReaderInterface $reader)
    {
        $this->pdo->beginTransaction();

        $this->initialize();
        foreach ($reader->get() as $metric) {
            $this->statement->execute(
                [
                    'unit_id'      => $metric->unit,
                    'metric_name'  => $metric->name,
                    'metric_value' => $metric->value,
                    'metric_time'  => $metric->timestamp,
                ]
            );
        }
        $this->compute();

        $this->pdo->commit();
    }

    private function initialize()
    {
        //Create tables
        $this->pdo->exec(file_get_contents(__DIR__ . '/../Resources/sql/import_batch_metrics.create.sql'));
        $this->pdo->exec(file_get_contents(__DIR__ . '/../Resources/sql/metrics_aggregate.create.sql'));

        //Prepare insert statement
        $this->statement = $this->pdo->prepare(file_get_contents(__DIR__ . '/../Resources/sql/import_batch_metrics.insert.sql'));
    }

    private function compute()
    {
        $i = $this->pdo->exec(file_get_contents(__DIR__ . '/../Resources/sql/import_batch_metrics.process.sql'));
    }
}
