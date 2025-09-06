<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */

    /** @test */
    public function it_displays_the_login_page(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertSee('login')
                    ->type('email', 'user@examples.com')
                    ->press('Login')
                    ->assertPathIS('/home');
        });
    }
}
