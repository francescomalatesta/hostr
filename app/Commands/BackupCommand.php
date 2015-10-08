<?php

namespace Hostr\Commands;

use Hostr\Contracts\HostsFileRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BackupCommand extends Command
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
            ->setName('backup')
            ->setDescription('Creates a backup version of your hosts file')
            ->addOption(
                'filename',
                'f',
                InputOption::VALUE_REQUIRED,
                'The desired name for your backup. If not specified, "'.$_ENV['BACKUP_HOSTS_FILE_PATH'].'" will be used.',
                $_ENV['BACKUP_HOSTS_FILE_PATH']
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('');

        if ($input->hasOption('filename')) {
            $_ENV['BACKUP_HOSTS_FILE_PATH'] = $input->getOption('filename');
        }

        $this->hostsFileRepository->backup();
        $output->writeln('<info>Backup file created successfully.</info>');

        $output->writeln('');
    }
}
