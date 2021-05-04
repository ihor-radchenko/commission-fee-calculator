#!/usr/bin/env php
<?php

use App\Command\CalculateCommissionFee;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

$app = new Application();

$command = new CalculateCommissionFee();

$app->add($command);
$app->setDefaultCommand($command->getName(), true);

$app->run();
