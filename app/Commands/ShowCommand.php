<?php

namespace Hostr\Commands;

use Hostr\Contracts\HostsFileRepositoryInterface;
use Hostr\Exceptions\HostsFileRepositoryInputException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowCommand extends Command
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
            ->setName('show')
            ->setDescription('Returns a list of all the records on the hosts file');
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

        $items = $hostsFile->getRecords();

        $table = new Table($output);

        switch (count($items)) {
            case 0:
                $output->writeln('Hosts File has no items... wait wat!');
            break;

            case 1:
                $output->writeln('Hosts File has a single item.');
            break;

            default:
                $output->writeln('Hosts File has '.count($items).' items.');
            break;
        }

        $rows = [];
        foreach ($items as $item) {
            $rows[] = [
                $item->getIpAddress(),
                $item->getHostname(),
                implode(', ', $item->getAliases()),
            ];
        }

        $table
            ->setHeaders(['IP Address', 'Hostname', 'Aliases'])
            ->setRows($rows)
            ->render();

        $output->writeln('');
    }
}
