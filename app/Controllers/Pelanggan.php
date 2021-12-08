<?php

namespace App\Controllers;
use App\Models\Pelanggan_m;
use App\Models\Pesanan_m;

class Pelanggan extends BaseController
{
    protected $modelPelanggan;
    protected $modelPesanan;

    public function __construct()
    {
        $this->modelPelanggan = new Pelanggan_m();
        $this->modelPesanan = new Pesanan_m();
    }

    public function index()
    {
        return view('pelanggan/index');
    }

    public function get()
    {
        $db = $this->modelPelanggan->builder();

        $page    = $this->request->getGet('page');
        $limit   = $this->request->getGet('rows');
        $sidx    = ($this->request->getGet('sidx') == "" ? 'nama' : $this->request->getGet('sidx'));
        $sord    = $this->request->getGet('sord');
        $search  = $this->request->getGet('_search');
        $filters = ($this->request->getGet('filters') ? json_decode($this->request->getGet('filters'), true) : false);
        
        if ($search == 'true') {

			foreach($filters['rules'] as $rule){

                $field     = $rule['field'];
				$ops       = "LIKE";
				$searchstr = $rule['data'];
				$searchstr = '%'.$searchstr.'%';
				
				$where[] = "$field $ops '$searchstr' ";
			}

            $where = implode($filters['groupOp']." ", $where);

            $db->where($where);
        }

        $start = $limit * $page - $limit;

        $db->orderBy($sidx, $sord);
        $db->limit($limit, $start);
        $query = $db->get();
        $rows = $query->getResult();

        $rowcnt = ($search == 'true' ? $db->where($where)->countAllResults() : $db->countAll());

        $data  = [
            'page'    => $page,
            'total'   => ceil($rowcnt/$limit),
            'records' => $rowcnt,
            'rows'    => $rows
        ];
        
        return $this->response->setJSON($data);
    }

    public function add()
    {
        return view('pelanggan/add-modal');
    }

    public function edit($id)
    {
        $pelanggan = $this->modelPelanggan->find($id);
        
        $tbPesanan = $this->modelPesanan->builder();
        $tbPesanan->where('pelanggan_id', $pelanggan->id);
        $pelanggan->pesanan = $tbPesanan->get()->getResult();

        $data = [
            'pelanggan' => $pelanggan
        ];

        return view('pelanggan/edit-modal', $data);
        // return $this->response->setJSON($data);
    }

    public function delete($id)
    {
        $pelanggan = $this->modelPelanggan->find($id);
        
        $tbPesanan = $this->modelPesanan->builder();
        $tbPesanan->where('pelanggan_id', $pelanggan->id);
        $pelanggan->pesanan = $tbPesanan->get()->getResult();

        $data = [
            'pelanggan' => $pelanggan
        ];

        return view('pelanggan/delete-modal', $data);
    }

    public function store()
    {
        $dataPelanggan = [
            'tgl_pesanan' => date('Y-m-d', strtotime($this->request->getPost('tgl_pesanan'))),
            'nama'        => $this->request->getPost('nama'),
            'nik'         => $this->request->getPost('nik'),
            'hp'          => $this->request->getPost('hp'),
            'email'       => $this->request->getPost('email'),
            'alamat'      => $this->request->getPost('alamat'),
        ];

        //insert data pelanggan
        $insert = $this->modelPelanggan->insert($dataPelanggan);
        if($insert === false){
            return $this->response->setJSON([
                'status' => 'error',
                'msg' => $this->modelPelanggan->errors()]);
        }

        $id = $this->modelPelanggan->insertID();

        //insert data pesanan baru
        $nama_produk = $this->request->getPost('nama_produk');
        $harga       = $this->request->getPost('harga');
        $qty         = $this->request->getPost('qty');

        for ($i=0; $i < count($nama_produk); $i++) { 
            $dataPesanan = [
                'pelanggan_id' => $id,
                'nama_produk'  => $nama_produk[$i],
                'harga'        => str_replace('.', '', $harga[$i]),
                'qty'          => $qty[$i],
                'total_harga'  => str_replace('.', '', $harga[$i]) * $qty[$i]
            ];

            $this->modelPesanan->insert($dataPesanan);
        }

        //delete data pesanan yang kosong
        $this->modelPesanan->where('nama_produk', '')->delete();

        return $this->response->setJSON(['msg' => 'success']);
    }

    public function update($id)
    {
        $dataPelanggan = [
            'tgl_pesanan' => date('Y-m-d', strtotime($this->request->getPost('tgl_pesanan'))),
            'nama'        => $this->request->getPost('nama'),
            'nik'         => $this->request->getPost('nik'),
            'hp'          => $this->request->getPost('hp'),
            'email'       => $this->request->getPost('email'),
            'alamat'      => $this->request->getPost('alamat'),
        ];

        $update = $this->modelPelanggan->update($id, $dataPelanggan);
        if($update === false){
            return $this->response->setJSON([
                'status' => 'error',
                'msg' => $this->modelPelanggan->errors()]);
        }

        //delete data pesanan
        $this->modelPesanan->where('pelanggan_id', $id)->delete();

        //insert data pesanan baru
        $nama_produk = $this->request->getPost('nama_produk');
        $harga       = $this->request->getPost('harga');
        $qty         = $this->request->getPost('qty');

        for ($i=0; $i < count($nama_produk); $i++) { 
            $dataPesanan = [
                'pelanggan_id' => $id,
                'nama_produk'  => $nama_produk[$i],
                'harga'        => str_replace('.', '', $harga[$i]),
                'qty'          => $qty[$i],
                'total_harga'  => str_replace('.', '', $harga[$i]) * $qty[$i]
            ];

            $this->modelPesanan->insert($dataPesanan);
        }

        //delete data pesanan yang kosong
        $this->modelPesanan->where('nama_produk', '')->delete();

        return $this->response->setJSON(['msg' => 'success']);
    }

    public function destroy($id)
    {
        $this->modelPelanggan->where('id', $id)->delete();
        $this->modelPesanan->where('pelanggan_id', $id)->delete();

        return $this->response->setJSON(['msg' => 'success']);
    }

    public function report()
    {
        $start   = ($_GET['start'] - 1) ?? 0;
		$limit   = $_GET['limit'] - $start;
		$getData = json_decode(base64_decode($_GET['data']));
		$myData  = array_slice($getData, $start, $limit);
		$sidx    = ($_GET['sidx'] == "" ? 'nama' : $_GET['sidx']);
		$sord    = ($_GET['sord'] == "" ? 'asc' : $_GET['sord']);
		$type    = isset($_GET['type'])?$_GET['type']:false;

		$pelangganID = [];

		foreach($myData as $data){
			$pelangganID[] = (int)$data->id;
		}
		
		$pelanggan = $this->modelPelanggan->builder();
		$pesanan   = $this->modelPesanan->builder();

		$pelanggan->whereIn('id', $pelangganID);
		$pelanggan->orderBy($sidx, $sord);
		$dataPelanggan = $pelanggan->get()->getResult();

		foreach($dataPelanggan as $pelanggan){
			$pelanggan->relations = $pesanan->getWhere(['pelanggan_id' => $pelanggan->id])->getResult();
		}

		$data = [
			'dataPelanggan' => $dataPelanggan
		];

        if($type == "stimulsoft"){
            return view('pelanggan/report', $data);
        }else{
            return view('pelanggan/excel', $data);
        }        
    }

    public function truncate()
    {
        $this->modelPelanggan->truncate();
        $this->modelPesanan->truncate();

        echo "OK";
    }
}
