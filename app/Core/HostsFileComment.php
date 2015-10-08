<?php

namespace Hostr\Core;

class HostsFileComment
{
    private $text;

    /**
     * HostsFileComment constructor.
     *
     * @param $text
     */
    public function __construct($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }
}
