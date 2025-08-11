<?php
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class OpenApiSnapshotTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testSpecStructure()
    {
        $res = $this->get('/openapi.json');
        $res->assertStatus(200);
        $spec = json_decode($res->getJSON(), true);
        $this->assertEquals('3.0.0', $spec['openapi']);
        $this->assertArrayHasKey('/auth/login', $spec['paths']);
        $this->assertArrayHasKey('/media/item/{id}', $spec['paths']);
        $this->assertArrayHasKey('components', $spec);
        $this->assertArrayHasKey('schemas', $spec['components']);
        $this->assertArrayHasKey('User', $spec['components']['schemas']);
        $this->assertArrayHasKey('MediaItem', $spec['components']['schemas']);
    }
}
