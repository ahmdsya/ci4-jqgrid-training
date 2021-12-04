<?php namespace App\Models;
use CodeIgniter\Model;

class Pesanan_m extends Model
{
    protected $table          = 'pesanan';
    protected $primaryKey     = 'id';
    protected $returnType     = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields  = ['pelanggan_id', 'nama_produk', 'harga', 'qty', 'total_harga'];

    protected $validationRules    = [
        'pelanggan_id' => 'required',
        'nama_produk'  => 'required',
        'harga'        => 'required',
        'qty'          => 'required',
        'total_harga'  => 'required',
    ];
 
    protected $validationMessages = [
        'pelanggan_id'  => [
            'required' => 'Harus diisi.',
        ],

        'nama_produk'  => [
            'required' => 'Harus diisi.',
        ],

        'harga'        => [
            'required' => 'Harus diisi.',
        ],

        'qty'        => [
            'required' => 'Harus diisi..',
        ],

        'total_harga'  => [
            'required' => 'Harus diisi.',
        ],      
    ];
}