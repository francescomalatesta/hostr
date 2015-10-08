<?php

namespace Hostr\Repositories;

use Hostr\Contracts\HostsFileRepositoryInterface;
use Hostr\Core\HostsFile;
use Hostr\Core\HostsFileComment;
use Hostr\Core\HostsFileRecord;
use Hostr\Exceptions\HostsFileRepositoryInputException;

class FileSystemHostsFileRepository implements HostsFileRepositoryInterface
{
    public function getHostsFile()
    {
        if (!file_exists($_ENV['HOSTS_FILE_PATH'])) {
            throw new HostsFileRepositoryInputException();
        }

        $fileContents = file($_ENV['HOSTS_FILE_PATH']);
        $hostsFile = new HostsFile();

        foreach ($fileContents as $line) {
            $line = trim($line);

            if ($line == '') {
                continue;
            }

            if ($line[0] == '#') {
                $hostsFile->addRecord(new HostsFileComment(substr($line, 1)));
                continue;
            }

            $line = preg_replace('!\s+!', ' ', $line);
            $line = explode(' ', $line);

            if (count($line) > 1) {
                $aliases = (count($line) > 2) ? array_splice($line, 2) : [];

                $hostsFile->addRecord(new HostsFileRecord($line[0], $line[1], $aliases));
            }
        }

        return $hostsFile;
    }

    public function saveHostsFile(HostsFile $hostsFile)
    {
        $items = $hostsFile->getAllItems();

        $contents = [];

        foreach ($items as $key => $item) {
            if (is_a($item, HostsFileComment::class)) {
                $contents[] = "\n".'# '.trim($item->getText());
                continue;
            }

            if (is_a($item, HostsFileRecord::class)) {
                $contents[] = $item->getIpAddress()."\t\t".$item->getHostname()."\t\t".implode("\t\t", $item->getAliases());
                continue;
            }
        }

        file_put_contents($_ENV['HOSTS_FILE_PATH'], implode("\n", $contents));
    }

    public function backup()
    {
        file_put_contents($_ENV['BACKUP_HOSTS_FILE_PATH'], file_get_contents($_ENV['HOSTS_FILE_PATH']));
    }

    public function restore()
    {
        file_put_contents($_ENV['HOSTS_FILE_PATH'], file_get_contents($_ENV['BACKUP_HOSTS_FILE_PATH']));
    }

    public function tidyUp()
    {
        $this->saveHostsFile($this->getHostsFile());
    }

    public function isHostsFileWritable()
    {
        if (!file_exists($_ENV['HOSTS_FILE_PATH'])) {
            throw new HostsFileRepositoryInputException();
        }

        return is_writable($_ENV['HOSTS_FILE_PATH']);
    }
}
