<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Product::insert([
            [
                'product' => 'Producto 1',
                'code' => '125478963',
                'price' => 500,
                'stock' => 10,
                'stock_notification_below' => 5,
                'store_id' => 1,
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'product' => 'Producto 2',
                'code' => '325478511',
                'price' => 1500,
                'stock' => 25,
                'stock_notification_below' => 10,
                'store_id' => 2,
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'product' => 'Producto 3',
                'code' => '965874125',
                'price' => 2800,
                'stock' => 12,
                'stock_notification_below' => 2,
                'store_id' => 1,
                'created_at' => \Carbon\Carbon::now(),
            ],
            [
                'product' => 'Producto 4',
                'code' => '458796317',
                'price' => 380,
                'stock' => 4,
                'stock_notification_below' => 2,
                'store_id' => 2,
                'created_at' => \Carbon\Carbon::now(),
            ],
        ]);

    }
}
