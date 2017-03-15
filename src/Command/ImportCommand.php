<?php

namespace SamKnows\Command;

use SamKnows\Processor\DatabaseProcessor;
use SamKnows\Reader\ReaderFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('samknows:import');
        $this->setDescription('Import a bunch of metric and aggregate them');

        $this->addArgument('filename', InputArgument::REQUIRED, 'Filename to import');

        $this->addOption('host', 'h', InputOption::VALUE_OPTIONAL, 'database host', 'localhost');
        $this->addOption('dbname', 'db', InputOption::VALUE_OPTIONAL, 'database name', 'samknows');
        $this->addOption('username', 'u', InputOption::VALUE_OPTIONAL, 'database username', 'root');
        $this->addOption('password', 'p', InputOption::VALUE_OPTIONAL, 'database password', 'root');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename   = $input->getArgument('filename');
        $dbHost     = $input->getOption('host');
        $dbName     = $input->getOption('dbname');
        $dbUsername = $input->getOption('username');
        $dbPassword = $input->getOption('password');

        $pdo       = $this->createPDO($dbHost, $dbName, $dbUsername, $dbPassword);
        $reader    = ReaderFactory::create($filename);
        $processor = new DatabaseProcessor($pdo);

        $processor->process($reader);
    }

    /**
     * @param string $host
     * @param string $dbname
     * @param string $username
     * @param string $password
     *
     * @return \PDO
     */
    private function createPDO(string $host, string $dbname, string $username, string $password)
    {
        return new \PDO(
            'mysql:host=' . $host . ';dbname=' . $dbname,
            $username,
            $password,
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]
        );
    }
}
