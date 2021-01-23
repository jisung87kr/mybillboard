<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $billboardChartCategory = [
          [
                'key' => 'hot-100',
                'value' =>'hot-100',
          ],
          [
              'key'   => 'billboard-200',
              'value' => 'billboard-200',
          ],
          [
              'key'   => 'billboard-global-200',
              'value' => 'billboard-global-200',
          ],
          [
              'key'   => 'billboard-global-excl-us',
              'value' => 'billboard-global-excl-us',
          ],
          [
              'key'   => 'artist-100',
              'value' => 'artist-100',
          ],
        ];

        foreach ($billboardChartCategory as $index => $item) {
            \App\Models\BillboardChartCategory::create([
                'key' => $item['key'],
                'value' => $item['value']
            ]);
        }
    }
}
