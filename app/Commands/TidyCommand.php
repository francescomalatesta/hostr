<?php

namespace Hostr\Commands;

use Hostr\Contracts\HostsFileRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TidyCommand extends Command
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
            ->setName('tidy')
            ->setDescription('Tidies up your hosts file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');

        $this->hostsFileRepository->tidyUp();
        $output->writeln('<info>Your hosts file looks better now.</info>');

        $output->writeln('');
    }
}
