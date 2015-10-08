<?php

use Hostr\Core\HostsFileRecord;

class HostsFileRecordTest extends PHPUnit_Framework_TestCase
{
    public function testCanCreateClass()
    {
        $hostsFileRecord = new HostsFileRecord('127.0.0.1', 'localhost');
        $hostsFileRecord2 = new HostsFileRecord('127.0.0.1', 'localhost', ['home', 'local']);

        $this->assertInstanceOf(HostsFileRecord::class, $hostsFileRecord);
        $this->assertInstanceOf(HostsFileRecord::class, $hostsFileRecord2);
    }

    public function testCanUpdateRecord()
    {
        $hostsFileRecord = new HostsFileRecord('127.0.0.1', 'localhost');

        $hostsFileRecord->setIpAddress('192.168.10.10');
        $hostsFileRecord->setHostname('modified.dev');
        $hostsFileRecord->setAliases(['alias1', 'alias2']);

        $this->assertEquals('192.168.10.10', $hostsFileRecord->getIpAddress());
        $this->assertEquals('modified.dev', $hostsFileRecord->getHostname());
        $this->assertEquals(['alias1', 'alias2'], $hostsFileRecord->getAliases());
    }
}
