<?php

namespace App\Controllers;
use App\Models\Pesanan_m;

class Pesanan extends BaseController
{
    protected $modelPesanan;
    
    public function __construct()
    {
        $this->modelPesanan = new Pesanan_m();
    }

    public function index($pelangganID)
    {
        $pesanan = $this->modelPesanan
                    ->where('pelanggan_id', $pelangganID)
                    ->orderBy('id', 'asc')
                    ->findAll();
        
        $db = $this->modelPesanan->builder();
        $db->selectSum('total_harga');
        $db->where('pelanggan_id', $pelangganID);
        $sum = $db->get()->getRow()->total_harga;

        $userData = [
			'nama_produk' => '',
			'harga'       => '',
			'qty'         => 'Total',
			'total_harga' => $sum
		];

        $return = [
			'userdata' => $userData,
			'rows'     => $pesanan
		];

        return $this->response->setJSON($return);
    }
}
