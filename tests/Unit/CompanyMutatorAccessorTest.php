<?php

namespace Tests\Unit;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyMutatorAccessorTest extends TestCase
{
    use RefreshDatabase;

    public function testGetCityAttribute()
    {
        $company = Company::factory()->create(['city' => 'test-city']);

        $this->assertEquals('TEST-CITY', $company->getAttribute('city'));
    }
    
    public function testSetCityAttribute()
    {
        $company = Company::factory()->create(['city' => 'test-city']);

        $this->assertEquals('TEST-CITY', $company->city);
    }
}
