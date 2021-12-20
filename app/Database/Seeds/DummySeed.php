<?php 

namespace App\Database\Seeds;
use App\Models\Pelanggan_m;
use App\Models\Pesanan_m;

class DummySeed extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $modelPelanggan = new Pelanggan_m();
        $modelPesanan = new Pesanan_m();
        
        $faker = \Faker\Factory::create();

        for ($i=0; $i < 10000; $i++) { 

            $dataPelanggan = [
                'tgl_pesanan' => $faker->date($format = 'Y-m-d', $max = 'now'),
                'nama' => strtoupper($faker->name),
                'nik' => rand(),
                'hp' => $faker->e164PhoneNumber,
                'email' => strtoupper($faker->email),
                'alamat' => strtoupper($faker->address)
            ];
            $modelPelanggan->insert($dataPelanggan);
            $pelanggan_id = $modelPelanggan->insertID();
            
            for ($j=0; $j < 4; $j++) { 
                
                $produk = ['Sepatu', 'Tas', 'Buku', 'Handphone', 'Jam Tangan', 'Laptop', 'Baju', 'Televisi', 'Komputer', 'Pensil'];
                $harga  = rand ( 10000 , 99999 );
                $qty    = rand(1, 9);

                $dataPesanan = [
                    'pelanggan_id' => $pelanggan_id,
                    'nama_produk' => strtoupper($produk[rand(0,9)]),
                    'harga' => $harga,
                    'qty' => $qty,
                    'total_harga' => $harga*$qty
                ];

                $modelPesanan->insert($dataPesanan);
            }
        }
            
    }
}