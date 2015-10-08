<?php

namespace Hostr\Contracts;

use Hostr\Core\HostsFile;

interface HostsFileRepositoryInterface
{
    public function getHostsFile();

    public function saveHostsFile(HostsFile $hostsFile);

    public function backup();

    public function restore();

    public function tidyUp();

    public function isHostsFileWritable();
}
