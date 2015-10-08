<?php

namespace Hostr\Core;

class HostsFileRecord
{
    private $ipAddress;
    private $hostname;
    private $aliases = [];

    /**
     * HostsFileRecord constructor.
     *
     * @param $ipAddress
     * @param $hostname
     * @param array $aliases
     */
    public function __construct($ipAddress, $hostname, array $aliases = [])
    {
        $this->ipAddress = $ipAddress;
        $this->hostname = $hostname;
        $this->aliases = $aliases;
    }

    /**
     * @return mixed
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @return mixed
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return $this->aliases;
    }

    /**
     * @param mixed $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @param mixed $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @param array $aliases
     */
    public function setAliases($aliases)
    {
        $this->aliases = $aliases;
    }
}
