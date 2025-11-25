<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;

class InventoryTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function authenticated_user_can_manage_inventory()
    {
        // Buat user
        $user = User::factory()->create([
            'email' => 'admin_' . time() . '@example.com', // ✅ email unik tiap test
            'password' => bcrypt('password123456'),
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            // 1. Kunjungi halaman login dan tunggu hingga siap
            $browser->visit('/login')
                ->waitForText('Login', 10) // ✅ tunggu teks muncul → pastikan halaman termuat
                ->type('email', $user->email)
                ->type('password', 'password123456')
                ->press('Login')
                ->waitForLocation('/inventory', 10) // ✅ tunggu redirect selesai
                ->assertPathIs('/inventory')
                ->assertSee('SparePart Inventory');

            // 2. Tambah barang baru
            $browser->type('nama_barang', 'Oli Mesin')
                ->type('stok', '10')
                ->select('kategori', 'Aftermarket')
                // ->attach('gambar', __DIR__ . '/../fixtures/sample.jpg') // ❌ nonaktifkan dulu jika belum siap
                ->press('Tambah')
                ->waitForText('Oli Mesin', 10); // ✅ tunggu sampai muncul di daftar

            // 3. Edit barang (inline)
            $browser->type('@edit-nama-1', 'Oli Mesin Premium')
                ->select('@edit-kategori-1', 'Mekanis')
                ->type('@edit-stok-1', '15')
                ->press('@update-button-1')
                ->waitForText('Oli Mesin Premium', 10)
                ->assertSee('Mekanis')
                ->assertSee('15');

            // 4. Hapus barang
            $browser->press('@delete-button-1')
                ->acceptDialog() // karena ada confirm()
                ->waitUntilMissingText('Oli Mesin Premium', 10);
        });
    }
}
