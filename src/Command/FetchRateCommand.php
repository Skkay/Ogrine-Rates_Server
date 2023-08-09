<?php

namespace App\Command;

use App\Service\DiscordNotificationService;
use App\Service\OgrineService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
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
    private DiscordNotificationService $discordNotificationService;

    public function __construct(OgrineService $ogrineService, DiscordNotificationService $discordNotificationService)
    {
        $this->ogrineService = $ogrineService;
        $this->discordNotificationService = $discordNotificationService;

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
            $io->success(sprintf('Latest fetched Ogrine value was successfully inserted. [datetime => %s, rate => %s]',
                $ogrineRate->getDatetime()->format('c'),
                $ogrineRate->getRate()
            ));

            $io->info('Sending Discord notifications...');
            $responses = $this->discordNotificationService->sendToAll($fetchedOgrine);

            $io->success('Discord notifications sent. See below for details.');

            $table = new Table($output);
            $table->setHeaders(['URL', 'Res. code', 'Discord response']);
            foreach ($responses as $response) {
                $table->addRow([
                    substr($response['webhook_url'], 0, 70) . '...',
                    $response['discord_response_status_code'],
                    $response['discord_response'],
                ]);
            }
            $table->render();

        } catch (UniqueConstraintViolationException $e) {
            $io->warning(sprintf('Latest fetched Ogrine value was already in the database. [timestamp => %s, rate => %s]',
                $fetchedOgrine->getCurrentRateTimestamp(),
                $fetchedOgrine->getCurrentRate()
            ));

            $io->text($e->getMessage());
        }

        return Command::SUCCESS;
    }
}
