<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use App\Models\UrlShortner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */


    public function test_user_can_be_created()
    {

        $user = \App\Models\User::factory()->create();

        $this->assertDatabaseHas('users', [
            'email' => $user->email,
        ]);
    }

    public function test_superadmin_cannot_create_short_url()
    {
        $company = Company::factory()->create();

        $user = User::factory()->create([
            'company_id' => $company->id,
            'roles' => 'superadmin'
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/admin/urls/generate', [
                'url' => 'https://google.com'
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_create_short_url()
    {
        $company = Company::factory()->create();

        $user = User::factory()->create([
            'company_id' => $company->id,
            'roles' => 'admin'
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/admin/urls/generate', [
                'url' => 'https://google.com'
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('url_shortners', [
            'created_by' => $user->id
        ]);
    }

    public function test_member_can_create_short_url()
    {
        $company = Company::factory()->create();

        $user = User::factory()->create([
            'company_id' => $company->id,
            'roles' => 'member'
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/admin/urls/generate', [
                'url' => 'https://example.com'
            ]);

        $response->assertStatus(200);
    }
}
