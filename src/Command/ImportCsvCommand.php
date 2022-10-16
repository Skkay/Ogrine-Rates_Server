<?php

namespace App\Command;

use App\Entity\OgrineRate;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use League\Csv\Reader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-csv',
    description: 'Read the CSV from the input stream and import its data to the database. E.g. `cat data.csv | php bin/console app:import-csv`',
)]
class ImportCsvCommand extends Command
{
    private ObjectManager $om;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->om = $doctrine->getManager();

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('dateHeaderName', null, InputOption::VALUE_REQUIRED, 'Name of the date header')
            ->addOption('rateHeaderName', null, InputOption::VALUE_REQUIRED, 'Name of the rate header')
            ->addOption('dateFormat', null, InputOption::VALUE_REQUIRED, 'Date format used in the CSV [timestamp_ms, timestamp]', 'timestamp_ms')
            ->addOption('csvDelimiter', null, InputOption::VALUE_REQUIRED, 'CSV field delimiter', ',')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $dateHeaderName = $input->getOption('dateHeaderName');
        if ($dateHeaderName === null) {
            $io->error('Missing dateHeaderName option');

            return Command::INVALID;
        }

        $rateHeaderName = $input->getOption('rateHeaderName');
        if ($rateHeaderName === null) {
            $io->error('Missing rateHeaderName option');

            return Command::INVALID;
        }

        $dateFormat = $input->getOption('dateFormat');
        $csvDelimiter = $input->getOption('csvDelimiter');

        $inputStream = stream_get_contents(STDIN);

        $csv = Reader::createFromString($inputStream);
        $csv->setHeaderOffset(0);
        $csv->setDelimiter($csvDelimiter);

        $records = $csv->getRecords();

        $io->info('Start of import...');

        $io->progressStart(iterator_count($records));

        foreach ($records as $record) {
            $ogrineRate = new OgrineRate();
            $ogrineRate->setRateTenth($record[$rateHeaderName] * 10);

            switch ($dateFormat) {
                case 'timestamp_ms':
                    $ogrineRate->setDatetime((new \DateTime())->setTimestamp(substr($record[$dateHeaderName], 0, -3)));
                    break;
                case 'timestamp':
                    $ogrineRate->setDatetime((new \DateTime())->setTimestamp($record[$dateHeaderName]));
                    break;

                default:
                    $io->error('Unknown date format');
                    return Command::INVALID;
            }

            $this->om->persist($ogrineRate);

            $io->progressAdvance();
        }

        $io->progressFinish();

        $io->info('Flushing...');
        $this->om->flush();

        $io->success('Importation done !');

        return Command::SUCCESS;
    }
}
