<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\CLI\Commands; // placeholder

class MediaPurgeCommandTest extends CIUnitTestCase
{
    public function testCommandClassExists()
    {
        $this->assertTrue(class_exists(\App\Commands\Media\MediaPurge::class));
    }
}
