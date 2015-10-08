<?php

namespace Hostr\Commands;

use Hostr\Contracts\HostsFileRepositoryInterface;
use Hostr\Core\HostsFileRecord;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class AddCommand extends Command
{
    private $hostsFileRepository;

    /**
     * AddCommand constructor.
     *
     * @param $hostsFileRepository
     */
    public function __construct(HostsFileRepositoryInterface $hostsFileRepository)
    {
        $this->hostsFileRepository = $hostsFileRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('add')
            ->setDescription('Adds a new record to the hosts file')
            ->addArgument(
                'ip',
                InputArgument::REQUIRED,
                "The new record's IP address."
            )
            ->addArgument(
                'hostname',
                InputArgument::REQUIRED,
                "The new record's hostname."
            )
            ->addOption(
                'aliases',
                'a',
                InputOption::VALUE_REQUIRED,
                'Optional: specify aliases, separated by commas (e.g. "alias1,alias2,alias3")',
                ''
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getHelper('question');

        try {
            $hostsFile = $this->hostsFileRepository->getHostsFile();
        } catch (HostsFileRepositoryInputException $e) {
            $output->writeln('<error>Some errors occurred while reading the file. Try again and check the path.</error>');

            return;
        }

        $output->writeln('');

        $records = $hostsFile->findRecords($input->getArgument('ip'), $input->getArgument('hostname'));
        if (count($records) > 0) {
            $record = reset($records);
            $position = key($records);

            $positiveAliasesDelta = array_diff(explode(',', $input->getOption('aliases')), $record->getAliases());
            $negativeAliasesDelta = array_diff($record->getAliases(), explode(',', $input->getOption('aliases')));

            if (count($negativeAliasesDelta) > 0 || count($positiveAliasesDelta) > 0) {
                $question = new Question('The record you typed already exists, but I found different aliases. Do you want to update the actual record? (y/N) ', 'n');
                $answer = $questionHelper->ask($input, $output, $question);

                $newAliases = explode(',', $input->getOption('aliases'));

                if ($answer == 'y') {
                    $hostsFile->updateRecord($position, null, null, $newAliases);
                    $this->hostsFileRepository->saveHostsFile($hostsFile);

                    $output->writeln('<info>Aliases added successfully.</info>');
                }

                return;
            }

            $output->writeln('<info>The record you typed already exists.</info>');
        }

        $ask = '';

        $searchResults = $hostsFile->findRecordsByHostname($input->getArgument('hostname'));

        if (count($searchResults) > 0) {
            $output->writeln('There is another record with the same Hostname in the hosts file.');
            $ask = 'hostname';
        } else {
            $searchResults = $hostsFile->findRecordsByIpAddress($input->getArgument('hostname'));

            if (count($searchResults) > 0) {
                $output->writeln('There is another record with the same IP address in the hosts file.');
                $ask = 'ip';
            }
        }

        if ($ask != '') {
            $answer = 'o';

            switch ($ask) {
                case 'hostname':
                    $question = new Question('What do you want to do? (a)dd a new record, (o)verwrite this one\'s IP, or (c)ancel? Default is (o): ', 'o');
                    $answer = $questionHelper->ask($input, $output, $question);
                    break;

                case 'ip':
                    $question = new Question('What do you want to do? (a)dd a new record, (o)verwrite this one\'s hostname, or (c)ancel? Default is (o): ', 'o');
                    $answer = $questionHelper->ask($input, $output, $question);
                    break;
            }

            switch ($answer) {
                case 'a':
                    $hostsFile->addRecord(
                        new HostsFileRecord($input->getArgument('ip'), $input->getArgument('hostname'), explode(',', $input->getOption('aliases')))
                    );
                    break;

                case 'o':
                    reset($searchResults);
                    $position = key($searchResults);

                    $hostsFile->updateRecord($position, $input->getArgument('ip'), $input->getArgument('hostname'), explode(',', $input->getOption('aliases')));
                    break;
            }

            $this->hostsFileRepository->saveHostsFile($hostsFile);

            return;
        }

        $hostsFile->addRecord(
            new HostsFileRecord($input->getArgument('ip'), $input->getArgument('hostname'), explode(',', $input->getOption('aliases')))
        );

        $this->hostsFileRepository->saveHostsFile($hostsFile);
        $output->writeln('<info>Record successfully added to the hosts file.</info>');

        $output->writeln('');
    }
}
