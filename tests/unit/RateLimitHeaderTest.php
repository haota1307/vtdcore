<?php
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;

class RateLimitHeaderTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testRateLimitHeadersPresent()
    {
        $result = $this->call('get', '/');
        $this->assertTrue($result->response()->hasHeader('X-RateLimit-Limit'));
        $this->assertTrue($result->response()->hasHeader('X-RateLimit-Remaining'));
    }
}
