<?php

use Hostr\Core\HostsFile;
use Hostr\Core\HostsFileRecord;
use Hostr\Repositories\FileSystemHostsFileRepository;

class FileSystemHostsFileRepositoryTest extends PHPUnit_Framework_TestCase
{
    private $hostsFileRepository;

    public function __construct()
    {
        $this->hostsFileRepository = new FileSystemHostsFileRepository();

        parent::__construct();
    }

    public function prepare()
    {
        $_ENV['HOSTS_FILE_PATH'] = __DIR__.'/sandbox/hosts';
    }

    public function testCanReadFromTheHostsFile()
    {
        $this->prepare();

        $hostsFile = $this->hostsFileRepository->getHostsFile();

        $this->assertInstanceOf(HostsFile::class, $hostsFile);
        $this->assertCount(2, $hostsFile->getRecords());
        $this->assertCount(3, $hostsFile->getAllItems());
    }

    public function testCanSaveTheHostsFile()
    {
        $this->prepare();

        $hostsFile = $this->hostsFileRepository->getHostsFile();

        $hostsFile->addRecord(new HostsFileRecord('192.168.9.9', 'hostname.test', ['alias1', 'alias2']));

        $_ENV['HOSTS_FILE_PATH'] = __DIR__.'/sandbox/result_1';

        $this->hostsFileRepository->saveHostsFile($hostsFile);

        $this->assertEquals(file_get_contents(__DIR__.'/sandbox/expectation_1'), file_get_contents(__DIR__.'/sandbox/result_1'));

        unlink(__DIR__.'/sandbox/result_1');
    }

    /**
     * @expectedException Hostr\Exceptions\HostsFileRepositoryInputException
     */
    public function testThrowsInputException()
    {
        $this->prepare();

        $_ENV['HOSTS_FILE_PATH'] = __DIR__.'/sandbox/idontexist';

        $this->hostsFileRepository->getHostsFile();
    }
}
