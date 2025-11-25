<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AuthTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_register_then_login_and_logout()
    {
        $email = 'user_' . time() . '@example.com';
        $password = 'password123456';

        $this->browse(function (Browser $browser) use ($email, $password) {
            // 1. Kunjungi halaman utama dan tunggu tombol muncul
            $browser->visit('/')
                ->waitFor('@login-button', 10) // ✅ TUNGGU SAMPAI ELEMEN ADA
                ->waitFor('@register-button', 10)
                ->assertSee('INVENTORY SPARE PARTS')
                ->click('@register-button')
                ->assertPathIs('/register');

            // 2. Isi form register
            $browser->type('email', $email)
                ->type('password', $password)
                ->type('password_confirmation', $password)
                ->press('Register')
                ->waitForLocation('/login', 10) // ✅ TUNGGU REDIRECT
                ->assertPathIs('/login');

            // 3. Login
            $browser->type('email', $email)
                ->type('password', $password)
                ->press('Login')
                ->waitForLocation('/inventory', 10) // ✅ TUNGGU REDIRECT
                ->assertPathIs('/inventory')
                ->assertSee('SparePart Inventory');

            // 4. Logout — tunggu tombol logout muncul
            $browser->waitFor('@logout-button', 10)
                ->click('@logout-button')
                ->waitForLocation('/', 10)
                ->assertPathIs('/')
                ->waitFor('@login-button', 10); // pastikan kembali ke halaman awal
        });
    }

    public function test_registered_user_can_login_directly()
    {
        \App\Models\User::factory()->create([
            'email' => 'existing@example.com',
            'password' => bcrypt('password123456'),
        ]);

        $this->browse(function (Browser $browser) {
            // 1. Kunjungi halaman utama
            $browser->visit('/')
                ->waitFor('@login-button', 10) // ✅ TUNGGU
                ->click('@login-button')
                ->waitForLocation('/login', 10);

            // 2. Login
            $browser->type('email', 'existing@example.com')
                ->type('password', 'password123456')
                ->press('Login')
                ->waitForLocation('/inventory', 10)
                ->assertPathIs('/inventory')
                ->assertSee('SparePart Inventory');

            // 3. Logout
            $browser->waitFor('@logout-button', 10)
                ->click('@logout-button')
                ->waitForLocation('/', 10)
                ->waitFor('@login-button', 10);
        });
    }
}
