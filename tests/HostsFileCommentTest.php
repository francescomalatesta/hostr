<?php

use Hostr\Core\HostsFileComment;

class HostsFileCommentTest extends PHPUnit_Framework_TestCase
{
    public function testCanCreate()
    {
        $hostsFileComment = new HostsFileComment('This is a test comment.');

        $this->assertInstanceOf(HostsFileComment::class, $hostsFileComment);
        $this->assertEquals('This is a test comment.', $hostsFileComment->getText());
    }
}
