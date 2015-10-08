<?php

namespace Hostr\Core;

class HostsFile
{
    private $records;

    public function __construct(array $records = [])
    {
        $this->records = $records;
    }

    public function getAllItems()
    {
        return $this->records;
    }

    public function getRecords()
    {
        $results = [];

        foreach ($this->records as $record) {
            if (is_a($record, HostsFileRecord::class)) {
                $results[] = $record;
            }
        }

        return $results;
    }

    public function addRecord($hostsFileRecord)
    {
        $this->records[] = $hostsFileRecord;
    }

    public function updateRecord($position, $ipAddress = null, $hostname = null, $aliases = null)
    {
        if ($ipAddress !== null) {
            $this->records[$position]->setIpAddress($ipAddress);
        }

        if ($hostname !== null) {
            $this->records[$position]->setHostname($hostname);
        }

        if ($aliases !== null) {
            $this->records[$position]->setAliases($aliases);
        }
    }

    public function removeRecords(array $selectedRecords)
    {
        foreach ($selectedRecords as $key => $record) {
            unset($this->records[$key]);
        }
    }

    public function findRecords($ipAddress, $hostname)
    {
        $results = [];

        for ($c = 0; $c < count($this->records); $c++) {
            $currentItem = $this->records[$c];

            if (is_a($currentItem, HostsFileComment::class)) {
                continue;
            }

            if ($hostname == '*' && $ipAddress == $currentItem->getIpAddress()) {
                $results[$c] = $currentItem;
                continue;
            }

            if ($ipAddress == '*' && $hostname == $currentItem->getHostname()) {
                $results[$c] = $currentItem;
                continue;
            }

            if ($ipAddress == $currentItem->getIpAddress() && $hostname == $currentItem->getHostname()) {
                $results[$c] = $currentItem;
                continue;
            }
        }

        return $results;
    }

    public function findRecordsByIpAddress($ipAddress)
    {
        return $this->findRecords($ipAddress, '*');
    }

    public function findRecordsByHostname($hostname)
    {
        return $this->findRecords('*', $hostname);
    }
}
