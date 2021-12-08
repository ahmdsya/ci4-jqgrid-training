<?php namespace App\Models;
use CodeIgniter\Model;

class Pelanggan_m extends Model
{
    protected $table          = 'pelanggan';
    protected $primaryKey     = 'id';
    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields  = ['tgl_pesanan', 'nama', 'nik', 'hp', 'email', 'alamat'];

    protected $validationRules    = [
        'tgl_pesanan' => 'required',
        'nama'        => 'required',
        'nik'         => 'required|numeric',
        'hp'          => 'required|numeric',
        'email'       => 'required|valid_email',
        'alamat'      => 'required',
    ];
 
    protected $validationMessages = [
        'tgl_pesanan'  => [
            'required' => 'Tanggal pesanan harus diisi.',
        ],

        'nama'  => [
            'required' => 'Nama harus diisi.',
        ],

        'nik'        => [
            'required' => 'Nik harus diisi.',
            'numeric' => 'Hanya bisa diisi dengan angka'
        ],

        'hp'        => [
            'required' => 'HP harus diisi.',
            'numeric' => 'Hanya bisa diisi dengan angka'
        ],

        'email'        => [
            'required' => 'Email harus diisi.',
            'valid_email' => 'Hanya bisa diisi dengan email'
        ],

        'alamat'        => [
            'required' => 'Alamat harus diisi.',
        ],        
    ];
}