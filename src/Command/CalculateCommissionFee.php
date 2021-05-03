<?php

namespace App\Command;

use App\Entity\Operation;
use App\Factory\OperationRepositoryFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculateCommissionFee extends Command
{
    protected const SUCCESS = 0;
    protected const FAILURE = 1;

    protected static $defaultName = 'calculate:commission-fee';

    protected function configure(): void
    {
        $this->addArgument('csv_path', InputArgument::REQUIRED, 'Path to CSV file with data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!file_exists($input->getArgument('csv_path'))) {
            $output->writeln('The file provided does not exist.');

            return self::FAILURE;
        }

        $operations = OperationRepositoryFactory::create($input->getArguments());

        $operations->each(static function (Operation $operation) use ($output) {
            $output->writeln($operation->getCommission());
        });

        return self::SUCCESS;
    }
}
