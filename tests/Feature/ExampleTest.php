<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response
            ->assertOk()
            ->assertSee('Kopisthetics')
            ->assertSee('id="home"', false)
            ->assertSee('id="menu"', false)
            ->assertSee('id="jelajahi"', false)
            ->assertSee('id="galeri"', false)
            ->assertSee('id="kontak"', false)
            ->assertSee('assets/css/global.css', false)
            ->assertSee('assets/img/main-1.jpg', false);
    }
}
