<?php

use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;

class RouteDisableTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testRouteUnavailableWhenModuleDisabled(): void
    {
        $manager = service('modules');
        // Ensure demo enabled first
        $manager->setEnabled('demo', true);
        $result = $this->get('/demo');
        $result->assertStatus(200);
        // Disable and re-hit
        $manager->setEnabled('demo', false);
        $result2 = $this->get('/demo');
        $result2->assertStatus(404);
        // Re-enable for other tests
        $manager->setEnabled('demo', true);
    }
}
