<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

use \App\Http\Controllers\SiteController;

class SiteIndexTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $siteIndex = new SiteController();
        $this->assertTrue($siteIndex->ins());
    }
}
