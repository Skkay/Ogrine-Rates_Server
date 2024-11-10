<?php

namespace App\Command;

use App\Repository\RealTimeOgrineRateRepository;
use App\Service\RealTimeOgrineService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fetch-real-time-rate',
    description: '',
)]
class FetchRealTimeRateCommand extends Command
{
    public function __construct(
        private RealTimeOgrineService $realTimeOgrineService,
        private RealTimeOgrineRateRepository $realTimeOgrineRateRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $fetchedOgrine = $this->realTimeOgrineService->fetchRealTimeOgrineValue();

        $this->realTimeOgrineRateRepository->insert($fetchedOgrine);

        $io->success(sprintf(
            'Successfully fetched and inserted current Ogrine value. [datetime => %s, currentRate => %s, numberOfOgrines => %s]',
            $fetchedOgrine->getFetchedAt()->format('Y-m-d H:i:s'),
            $fetchedOgrine->getCurrentRate(),
            $fetchedOgrine->getNumberOfOgrines(),
        ));

        return Command::SUCCESS;
    }
}
