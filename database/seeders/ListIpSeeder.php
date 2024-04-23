<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use SplFileObject;

class ListIpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $filePath = public_path('geoip2-ipv4.csv');
        $file = new SplFileObject($filePath, 'r');
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD | SplFileObject::SKIP_EMPTY);
        $file->setCsvControl(','); // Set separator

        $rowIndex = 0;

        foreach ($file as $row) {
            $dt = str_getcsv($row[0]); // Menggunakan str_getcsv() untuk memisahkan nilai dengan koma
            if ($rowIndex > 0) {
                DB::table('list_ip')->insert([
                    'network' => explode('/', $dt[0])[0],
                    'geoname_id' => is_numeric($dt[1]) ? (int) trim($dt[1], '"') : 0, // Menghilangkan tanda kutip ganda
                    'continent_code' => $dt[2],
                    'continent_name' => $dt[3],
                    'country_iso_code' => $dt[4],
                    'country_name' => $dt[5],
                    'is_anonymous_proxy' => ($dt[6] === '1') ? true : false,
                    'is_satellite_provider' => ($dt[7] === '1') ? true : false,
                    'netmask' => $this->calculateNetmask($dt[0]),
                ]);
            }
            $rowIndex++;
        }

    }

    private function calculateNetmask($network)
    {
        try {
            $cidr = explode('/', $network)[1];
            $netmask = str_repeat('1', $cidr) . str_repeat('0', 32 - $cidr);
            $netmaskParts = str_split($netmask, 8);
            $netmaskIP = array_map(function ($part) {
                return bindec($part);
            }, $netmaskParts);

            return implode('.', $netmaskIP);

        } catch (\Throwable $e) {
            return null;
        }
    }
}