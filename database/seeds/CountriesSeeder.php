<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = json_decode(file_get_contents(resource_path("files/countries.json")), true);
        foreach ($countries as $country) {


            $whereOrInsert = [
                'name' => $country['name'],
                'code' => $country['code'],
                'dial_code' => $country['dial_code']
            ];
            $qty = DB::table("countries")->where($whereOrInsert)->count();
            if ($qty == 0) {
                DB::table("countries")->insert($whereOrInsert);
            }

        }
    }
}
