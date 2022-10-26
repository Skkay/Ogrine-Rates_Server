<?php

namespace App\Command;

use App\Service\OgrineService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fetch-rate',
    description: '',
)]
class FetchRateCommand extends Command
{

    private OgrineService $ogrineService;

    public function __construct(OgrineService $ogrineService)
    {
        $this->ogrineService = $ogrineService;

        parent::__construct();
    }

    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $fetchedOgrine = $this->ogrineService->fetchLatestOgrineValue();

        try {
            $ogrineRate = $this->ogrineService->insertLatestOgrineValue($fetchedOgrine);
            $io->success(sprintf('Latest fetched Ogrine value was successfully inserted [datetime => %s, rate => %s]',
                $ogrineRate->getDatetime()->format('c'),
                $ogrineRate->getRate()
            ));
        } catch (UniqueConstraintViolationException $e) {
            $io->warning(sprintf('Latest fetched Ogrine value was already in the database [timestamp => %s, rate => %s]',
                $fetchedOgrine->getCurrentRateTimestamp(),
                $fetchedOgrine->getCurrentRate()
            ));

            $io->text($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
