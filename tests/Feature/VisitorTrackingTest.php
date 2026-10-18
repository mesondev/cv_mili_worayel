<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class VisitorTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_page_views_are_recorded_with_anonymized_ip_and_no_referrer_or_user_agent(): void
    {
        $this->withServerVariables(['REMOTE_ADDR' => '192.168.15.42'])
            ->get('/')
            ->assertOk();

        $visit = DB::table('visitor_logs')->first();
        $this->assertNotNull($visit);
        $this->assertSame('192.168.15.0', $visit->ip_address);
        $this->assertSame('home', $visit->path);
        $this->assertNull($visit->referrer);
        $this->assertNull($visit->user_agent);
    }
}
