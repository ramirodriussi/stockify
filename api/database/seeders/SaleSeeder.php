<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Product::insert([
        //     [
        //         'sale_id' => hexdec(uniqid()),
        //         'payment_type' => 'Efectivo',
        //         'created_at' => \Carbon\Carbon::now(),
        //     ],
        // ]);

        $sale = new Sale();

        $sale->sale_id = hexdec(uniqid());
        $sale->payment_type = 'Efectivo';

        $sale->save();

        $arr[1] = [
            'product' => 'Producto 1',
            'price' => 500,
            'quantity' => 2
        ];

        $arr[2] = [
            'product' => 'Producto 2',
            'price' => 1500,
            'quantity' => 3
        ];

        $sale->product()->attach($arr);

    }
}
