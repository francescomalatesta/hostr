<?php

use Hostr\Core\HostsFile;
use Hostr\Core\HostsFileComment;
use Hostr\Core\HostsFileRecord;

class HostsFileTest extends PHPUnit_Framework_TestCase
{
    public function testCanCreateClass()
    {
        $hostsFile = new HostsFile();

        $this->assertInstanceOf(HostsFile::class, $hostsFile);
    }

    public function testCanAddRecords()
    {
        $hostsFile = new HostsFile();

        $this->assertCount(0, $hostsFile->getRecords());

        $hostsFile->addRecord(new HostsFileRecord('192.168.2.1', 'myproject.dev'));

        $this->assertCount(1, $hostsFile->getRecords());

        $hostsFile->addRecord(new HostsFileRecord('192.168.2.2', 'myproject2.dev', ['myproject2', 'alias2']));

        $this->assertCount(2, $hostsFile->getRecords());
    }

    public function testCanFindRecords()
    {
        $hostsFile = new HostsFile();

        $hostsFile->addRecord(new HostsFileRecord('192.168.2.1', 'myproject.dev'));
        $hostsFile->addRecord(new HostsFileComment('This is a test comment...'));
        $hostsFile->addRecord(new HostsFileRecord('192.168.2.1', 'myproject2.dev', ['myproject2', 'alias2']));

        $results = $hostsFile->findRecordsByIpAddress('192.168.2.1');
        $this->assertCount(2, $results);

        $results = $hostsFile->findRecordsByIpAddress('192.168.10.10');
        $this->assertCount(0, $results);

        $results = $hostsFile->findRecordsByHostname('idontexist.dev');
        $this->assertCount(0, $results);

        $results = $hostsFile->findRecords('192.168.2.1', 'myproject.dev');
        $this->assertCount(1, $results);

        $results = $hostsFile->findRecords('192.168.1.1', 'myproject.dev');
        $this->assertCount(0, $results);
    }

    public function testCanRemoveRecords()
    {
        $hostsFile = new HostsFile();

        $hostsFile->addRecord(new HostsFileRecord('192.168.2.1', 'myproject.dev'));
        $hostsFile->addRecord(new HostsFileRecord('192.168.2.1', 'myproject2.dev', ['myproject2', 'alias2']));

        $selectedItems = $hostsFile->findRecordsByHostname('myproject.dev');

        $this->assertCount(1, $selectedItems);

        $hostsFile->removeRecords($selectedItems);

        $this->assertCount(1, $hostsFile->getRecords());
        $this->assertEquals('myproject2.dev', end($hostsFile->getRecords())->getHostname());
    }

    public function testCanUpdateRecords()
    {
        $hostsFile = new HostsFile();
        $hostsFile->addRecord(new HostsFileRecord('192.168.2.1', 'myproject.dev'));

        $records = $hostsFile->findRecords('192.168.2.1', 'myproject.dev');
        reset($records);
        $originalRecordPosition = key($records);

        $hostsFile->updateRecord($originalRecordPosition, '192.168.2.2');

        $records = $hostsFile->findRecords('192.168.2.2', 'myproject.dev');
        reset($records);
        $modifiedRecordPosition = key($records);

        $this->assertEquals($originalRecordPosition, $modifiedRecordPosition);
    }
}
