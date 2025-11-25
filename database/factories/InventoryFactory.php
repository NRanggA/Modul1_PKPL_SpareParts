<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryFactory extends Factory
{
    public function definition()
    {
        return [
            'nama_barang' => $this->faker->word(),
            'stok' => $this->faker->numberBetween(0, 100),
            'kategori' => 'OEM',
            'gambar' => null,
        ];
    }
}
