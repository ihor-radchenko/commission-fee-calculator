<?php

namespace App\Command;

use App\Factory\DataProviderFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CalculateCommissionFee extends Command
{
    protected const SUCCESS = 0;
    protected const ERROR = 1;

    protected static $defaultName = 'calculate:commission-fee';

    protected function configure(): void
    {
        $this->addArgument('csv_path', InputArgument::REQUIRED, 'Path to CSV file with data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!file_exists($input->getArgument('csv_path'))) {
            $output->writeln('The file provided does not exist.');

            return self::ERROR;
        }

        $dataProvider = DataProviderFactory::create([$input->getArgument('csv_path')]);

        foreach ($dataProvider as $data) {
            var_dump($data);
        }

        return self::SUCCESS;
    }
}
