<?php

namespace Hostr\Commands;

use Hostr\Contracts\HostsFileRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RemoveCommand extends Command
{
    private $hostsFileRepository;

    public function __construct(HostsFileRepositoryInterface $hostsFileRepository)
    {
        $this->hostsFileRepository = $hostsFileRepository;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('remove')
            ->setDescription('Removes specific records from the hosts file, given its/their IP or hostname')
            ->addArgument(
                'term',
                InputArgument::REQUIRED,
                'If you want to delete a record given its IP address, use "ip". Otherwise, use "hostname"'
            )
            ->addArgument(
                'value',
                InputArgument::OPTIONAL,
                'The value of the element you want to remove.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');

        try {
            $hostsFile = $this->hostsFileRepository->getHostsFile();
        } catch (HostsFileRepositoryInputException $e) {
            $output->writeln('<error>Some errors occurred while reading the file. Try again and check the path.</error>');

            return;
        }

        if (is_numeric($input->getArgument('term'))) {
            // TODO: delete by position, as shown in "show" command
        }

        switch ($input->getArgument('term')) {
            case 'ip':
                $searchResults = $hostsFile->findRecordsByIpAddress($input->getArgument('value'));

                if (count($searchResults) > 0) {
                    $hostsFile->removeRecords($searchResults);
                    $output->writeln('<info>'.count($searchResults).' records removed successfully.</info>');
                }
                break;

            case 'hostname':
                $searchResults = $hostsFile->findRecordsByHostname($input->getArgument('value'));

                if (count($searchResults) > 0) {
                    $hostsFile->removeRecords($searchResults);
                    $output->writeln('<info>'.count($searchResults).' records removed successfully.</info>');
                }
                break;

            default:
                $output->writeln('<error>Your argument is invalid.</error>');
                break;
        }

        $this->hostsFileRepository->saveHostsFile($hostsFile);

        $output->writeln('');
    }
}
