<?php

namespace Tests\Feature\Admin;

use App\Skill;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FilterUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function filter_users_by_state_active()
    {
        $activeUser = User::factory()->create();
        $inactiveUser = User::factory()->inactive()->create();

        $response = $this->get('/usuarios?state=active');

        $response->assertViewCollection('users')
            ->contains($activeUser)
            ->notContains($inactiveUser);
    }

    /** @test */
    function filter_users_by_state_inactive()
    {
        $activeUser = User::factory()->create();
        $inactiveUser = User::factory()->inactive()->create();

        $response = $this->get('/usuarios?state=inactive');

        $response->assertViewCollection('users')
            ->contains($inactiveUser)
            ->notContains($activeUser);
    }

    /** @test */
    function filter_users_by_role_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->get('/usuarios?role=admin');

        $response->assertViewCollection('users')
            ->contains($admin)
            ->notContains($user);
    }

    /** @test */
    function filter_users_by_role_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->get('/usuarios?role=user');

        $response->assertStatus(200);

        $response->assertViewCollection('users')
            ->contains($user)
            ->notContains($admin);
    }

    /** @test */
    function filter_users_by_skills()
    {
        $php = Skill::factory()->create(['name' => 'php']);
        $css = Skill::factory()->create(['name' => 'css']);

        $backendDev = User::factory()->create();
        $backendDev->skills()->attach($php);

        $frontendDev = User::factory()->create();
        $frontendDev->skills()->attach($css);

        $fullendDev = User::factory()->create();
        $fullendDev->skills()->attach([$php->id, $css->id]);

        $response = $this->get("/usuarios?skills[0]={$php->id}&skills[1]={$css->id}");

        $response->assertStatus(200);

        $response->assertViewCollection('users')
            ->contains($fullendDev)
            ->notContains($backendDev)
            ->notContains($frontendDev);
    }

    /** @test */
    function filter_users_created_from_date()
    {
        $newestUser = User::factory()->create([
            'created_at' => '2020-10-02 12:00:00',
        ]);
        $oldestUser = User::factory()->create([
            'created_at' => '2020-09-29 12:00:00',
        ]);
        $newUser = User::factory()->create([
            'created_at' => '2020-10-01 00:00:00',
        ]);
        $oldUser = User::factory()->create([
            'created_at' => '2020-09-30 23:59:59',
        ]);

        $response = $this->get('/usuarios?from=01/10/2020');

        $response->assertOk();

        $response->assertViewCollection('users')
            ->contains($newUser)
            ->contains($newestUser)
            ->notContains($oldUser)
            ->notContains($oldestUser);
    }

    /** @test */
    function filter_users_created_to_date()
    {
        $newestUser = User::factory()->create([
            'created_at' => '2020-10-02 12:00:00',
        ]);
        $oldestUser = User::factory()->create([
            'created_at' => '2020-09-29 12:00:00',
        ]);
        $newUser = User::factory()->create([
            'created_at' => '2020-10-01 00:00:00',
        ]);
        $oldUser = User::factory()->create([
            'created_at' => '2020-09-30 23:59:59',
        ]);

        $response = $this->get('/usuarios?to=30/09/2020');

        $response->assertOk();

        $response->assertViewCollection('users')
            ->contains($oldestUser)
            ->contains($oldUser)
            ->notContains($newestUser)
            ->notContains($newUser);
    }
}
