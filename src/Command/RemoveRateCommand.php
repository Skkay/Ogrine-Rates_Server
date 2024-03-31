<?php

namespace App\Command;

use App\Service\OgrineService;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:remove-rate',
    description: 'Remove a rate from the database',
)]
class RemoveRateCommand extends Command
{
    private OgrineService $ogrineService;

    public function __construct(OgrineService $ogrineService)
    {
        $this->ogrineService = $ogrineService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('date', InputArgument::REQUIRED, 'Date of the rate in the format YYYY-MM-DD')
            ->addArgument('rate', InputArgument::REQUIRED, 'Rate tenth (E.g. 715.8 Ogrines = 7158)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $datetime = \DateTime::createFromFormat('Y-m-d', $input->getArgument('date'))->setTime(0, 0, 0);
        $rate = $input->getArgument('rate');

        try {
            $this->ogrineService->deleteOgrineValue($datetime, $rate);

            $io->success(sprintf('Ogrine value successfully removed. [datetime => %s, rate => %s]', $datetime->format('c'), $rate));
        } catch (EntityNotFoundException $e) {
            $io->warning('Ogrine value was not found in the database');

            $io->text($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
