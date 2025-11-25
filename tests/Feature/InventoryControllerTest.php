<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authUser(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $this->actingAs($user);
    }

    /** @test */
    public function it_can_show_inventory_index()
    {
        $this->authUser();

        $response = $this->get(route('inventory.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_inventory_data_with_image()
    {
        $this->authUser();

        Storage::fake('public');

        // gunakan create() supaya GD extension TIDAK diperlukan
        $file = UploadedFile::fake()->create('barang.jpg', 100);

        $response = $this->post(route('inventory.store'), [
            'nama_barang' => 'Laptop Lenovo',
            'stok'        => 5,
            'kategori'    => 'Elektrikal',
            'gambar'      => $file,
        ]);

        $response->assertRedirect();

        // database check
        $this->assertDatabaseHas('inventories', [
            'nama_barang' => 'Laptop Lenovo',
            'stok'        => 5,
        ]);

        // storage check — aman tanpa GD
        $this->assertTrue(
            Storage::disk('public')->exists('uploads/' . $file->hashName()),
            "File tidak ditemukan di storage/public/uploads/"
        );
    }

    /** @test */
    public function it_can_update_inventory_data_with_new_image()
    {
        $this->authUser();

        Storage::fake('public');

        $inventory = Inventory::factory()->create();

        $file = UploadedFile::fake()->create('update.jpg', 120);

        $response = $this->put(route('inventory.update', $inventory->id), [
            'nama_barang' => 'Monitor LG',
            'stok'        => 10,
            'kategori'    => 'Elektrikal',
            'gambar'      => $file,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('inventories', [
            'id'          => $inventory->id,
            'nama_barang' => 'Monitor LG',
            'stok'        => 10,
        ]);

        // cek file tersimpan
        $this->assertTrue(
            Storage::disk('public')->exists('uploads/' . $file->hashName()),
            "File update tidak ditemukan di storage/public/uploads/"
        );
    }

    /** @test */
    public function it_can_delete_inventory_data()
    {
        $this->authUser();

        $inventory = Inventory::factory()->create();

        $response = $this->delete(route('inventory.delete', $inventory->id));

        $response->assertRedirect();

        $this->assertDatabaseMissing('inventories', [
            'id' => $inventory->id,
        ]);
    }
}
