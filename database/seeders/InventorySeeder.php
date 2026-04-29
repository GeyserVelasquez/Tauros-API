<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Herd;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Supply;
use App\Models\SupplyType;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $herd = Herd::first();

        $batches = [
            ['code' => 'BATCH-001', 'name' => 'Lote de Engorde 1', 'herd_id' => $herd->id],
            ['code' => 'BATCH-002', 'name' => 'Lote de Cría A', 'herd_id' => $herd->id],
        ];

        foreach ($batches as $batch) {
            Batch::updateOrCreate(['code' => $batch['code']], $batch);
        }

        $supplyType = SupplyType::where('code', 'MEDICINE')->first();

        $supplies = [
            ['code' => 'OXIT-500', 'name' => 'Oxitetraciclina 500mg', 'supply_type_id' => $supplyType->id],
            ['code' => 'IVEM-1', 'name' => 'Ivermectina 1%', 'supply_type_id' => $supplyType->id],
        ];

        foreach ($supplies as $supply) {
            Supply::updateOrCreate(['code' => $supply['code']], $supply);
        }

        $productType = ProductType::where('code', 'MILK')->first();

        $products = [
            [
                'code' => 'MILK-PREM',
                'name' => 'Leche Premium',
                'description' => 'Leche de alta calidad',
                'attributes' => ['fat_content' => '3.5%', 'grade' => 'A'],
                'product_type_id' => $productType->id
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['code' => $product['code']], $product);
        }
    }
}
