<?php

namespace Hostr\Commands;

use Hostr\Contracts\HostsFileRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RestoreCommand extends Command
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
            ->setName('restore')
            ->setDescription('Restores a backup version of your hosts file on the original one')
            ->addOption(
                'filename',
                'f',
                InputOption::VALUE_REQUIRED,
                'The name you have chosen for your backup. If not specified, "'.$_ENV['BACKUP_HOSTS_FILE_PATH'].'" will be used.',
                $_ENV['BACKUP_HOSTS_FILE_PATH']
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');

        if ($input->hasOption('filename')) {
            $_ENV['BACKUP_HOSTS_FILE_PATH'] = $input->getOption('filename');
        }

        $this->hostsFileRepository->restore();
        $output->writeln('<info>Backup file restored successfully.</info>');

        $output->writeln('');
    }
}
