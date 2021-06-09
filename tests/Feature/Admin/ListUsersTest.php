<?php

namespace Tests\Feature\Admin;

use App\Login;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function it_shows_the_users_list()
    {
        User::factory()->create([
            'first_name' => 'Joel',
        ]);

        User::factory()->create([
            'first_name' => 'Ellie',
        ]);

        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSee(trans('users.title.index'))
            ->assertSee('Joel')
            ->assertSee('Ellie');

        $this->assertNotRepeatedQueries();
    }

    /** @test */
    function it_shows_a_default_message_if_the_users_list_is_empty()
    {
        $this->get('/usuarios?empty')
            ->assertStatus(200)
            ->assertSee(trans('users.title.index'))
            ->assertSee('No hay usuarios registrados');
    }

    /** @test */
    function it_shows_the_deleted_users()
    {
        User::factory()->create([
            'first_name' => 'Joel',
            'deleted_at' => now()
        ]);

        User::factory()->create([
            'first_name' => 'Ellie',
        ]);

        $this->get('/usuarios/papelera')
            ->assertStatus(200)
            ->assertSee(trans('users.title.trash'))
            ->assertSee('Joel')
            ->assertDontSee('Ellie');
    }

    /** @test */
    function it_paginates_the_users()
    {
        User::factory()->create([
            'first_name' => 'Tercer usuario',
            'created_at' => now()->subDays(5),
        ]);
        User::factory()->times(12)->create([
            'created_at' => now()->subDays(4),
        ]);
        User::factory()->create([
            'first_name' => 'Decimoséptimo usuario',
            'created_at' => now()->subDays(2),
        ]);
        User::factory()->create([
            'first_name' => 'Segundo usuario',
            'created_at' => now()->subDays(6),
        ]);
        User::factory()->create([
            'first_name' => 'Primer usuario',
            'created_at' => now()->subWeek(),
        ]);
        User::factory()->create([
            'first_name' => 'Decimosexto usuario',
            'created_at' => now()->subDays(3),
        ]);


        $this->get('usuarios')
            ->assertStatus(200)
            ->assertSeeInOrder([
                'Decimoséptimo usuario',
                'Decimosexto usuario'
            ])
            ->assertDontSee('Segundo usuario')
            ->assertDontSee('Primer usuario');

        $this->get('usuarios?page=2')
            ->assertSeeInOrder([
                'Segundo usuario',
                'Primer usuario'
            ])
            ->assertDontSee('Tercer usuario');
    }

    /** @test */
    function users_are_ordered_by_name()
    {
        User::factory()->create(['first_name' => 'John Doe']);
        User::factory()->create(['first_name' => 'Richard Roe']);
        User::factory()->create(['first_name' => 'Jane Doe']);

        $this->get('/usuarios?order=first_name')
            ->assertSeeInOrder([
                'Jane Doe',
                'John Doe',
                'Richard Roe',
            ]);

        $this->get('/usuarios?order=first_name-desc')
            ->assertSeeInOrder([
                'Richard Roe',
                'John Doe',
                'Jane Doe',
            ]);
    }

    /** @test */
    function users_are_ordered_by_email()
    {
        User::factory()->create(['email' => 'john.doe@example.com']);
        User::factory()->create(['email' => 'richard.roe@example.com']);
        User::factory()->create(['email' => 'jane.doe@example.com']);

        $this->get('/usuarios?order=email')
            ->assertSeeInOrder([
                'jane.doe@example.com',
                'john.doe@example.com',
                'richard.roe@example.com',
            ]);

        $this->get('/usuarios?order=email-desc')
            ->assertSeeInOrder([
                'richard.roe@example.com',
                'john.doe@example.com',
                'jane.doe@example.com',
            ]);
    }

    /** @test */
    function users_are_ordered_by_date()
    {
        User::factory()->create(['first_name' => 'John Doe', 'created_at' => now()->subDays(2)]);
        User::factory()->create(['first_name' => 'Richard Roe', 'created_at' => now()->subDays(3)]);
        User::factory()->create(['first_name' => 'Jane Doe', 'created_at' => now()->subDays(5)]);

        $this->get('/usuarios?order=date')
            ->assertSeeInOrder([
                'Jane Doe',
                'Richard Roe',
                'John Doe',
            ]);

        $this->get('/usuarios?order=date-desc')
            ->assertSeeInOrder([
                'John Doe',
                'Richard Roe',
                'Jane Doe',
            ]);
    }

    /** @test */
    function invalid_order_query_data_is_ignored_and_default_order_is_used_instead()
    {
        User::factory()->create(['first_name' => 'John Doe', 'created_at' => now()->subDays(2)]);
        User::factory()->create(['first_name' => 'Jane Doe', 'created_at' => now()->subDays(5)]);
        User::factory()->create(['first_name' => 'Richard Roe', 'created_at' => now()->subDays(3)]);

        $this->get('/usuarios?order=id')
            ->assertOk()
            ->assertSeeInOrder([
                'John Doe',
                'Richard Roe',
                'Jane Doe',
            ]);

        $this->get('/usuarios?order=invalid_column')
            ->assertOk()
            ->assertSeeInOrder([
                'John Doe',
                'Richard Roe',
                'Jane Doe',
            ]);

        $this->get('/usuarios?order=first_name-descendent')
            ->assertSeeInOrder([
                'John Doe',
                'Richard Roe',
                'Jane Doe',
            ]);

        $this->get('/usuarios?order=asc-first_name')
            ->assertSeeInOrder([
                'John Doe',
                'Richard Roe',
                'Jane Doe',
            ]);
    }

    /** @test */
    function users_are_ordered_by_login_date()
    {
        Login::factory()->create([
            'created_at' => now()->subDays(3),
            'user_id' => User::factory()->create(['first_name' => 'John Doe']),
        ]);
        Login::factory()->create([
            'created_at' => now()->subDays(),
            'user_id' => User::factory()->create(['first_name' => 'Jane Doe']),
        ]);
        Login::factory()->create([
            'created_at' => now()->subDays(2),
            'user_id' => User::factory()->create(['first_name' => 'Richard Roe']),
        ]);

        $this->get('/usuarios?order=login')
            ->assertSeeInOrder([
                'John Doe',
                'Richard Roe',
                'Jane Doe',
            ]);

        $this->get('/usuarios?order=login-desc')
            ->assertSeeInOrder([
                'Jane Doe',
                'Richard Roe',
                'John Doe',
            ]);
    }
}
