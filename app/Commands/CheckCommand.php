<?php

namespace Hostr\Commands;

use Hostr\Contracts\HostsFileRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
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
            ->setName('check')
            ->setDescription('Checks if your hosts file is writable');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');

        $isWritable = $this->hostsFileRepository->isHostsFileWritable();

        if ($isWritable) {
            $output->writeln('<info>Your hosts file is writable. Go on!</info>');
        } else {
            $output->writeln('<error>Your hosts file is not writable. Use sudo!</error>');
        }

        $output->writeln('');
    }
}
