<?php

return [
    \Hostr\Contracts\HostsFileRepositoryInterface::class => DI\object(\Hostr\Repositories\FileSystemHostsFileRepository::class),
];
