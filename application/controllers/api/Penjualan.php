<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Penjualan extends BD_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // $this->auth();
        $this->load->database();
    }

    public function index_get()
    {
        $penjualan = $this->db->get('penjualan')->result();
        $response['status'] = "success";
        $response['data'] = $penjualan;
        
        $this->response($response, 200);
    }

    public function index_post()
    {
        $data = [
            'barang_id' => $this->post('barang_id'),
            'jumlah_barang' => $this->post('jumlah_barang'),
            'jumlah_harga' => $this->post('jumlah_harga'),
            'tanggal' => $this->post('tanggal')
        ];
    
        $insert = $this->db->insert('penjualan', $data);

        if ($insert) {
            $this->response(['status' => 'success'], 200);
        } else {
            $this->response(['status' => 'fail', 200]);            
        }


    }

    public function update_post($id)
    {
        $data = [
            'barang_id' => $this->post('barang_id'),
            'jumlah_barang' => $this->post('jumlah_barang'),
            'jumlah_harga' => $this->post('jumlah_harga'),
            'tanggal' => $this->post('tanggal')
        ];
        
        $this->db->where('id', $id);
        $update = $this->db->update('penjualan', $data);

        if ($update) {
            $this->response(['status' => 'success'], 200);
        } else {
            $this->response(['status' => 'fail'], 200);
        }

    }

    public function delete_post($id)
    {
        $this->db->where('id', $id);
        $delete = $this->db->delete('penjualan');

        if ($delete) {
            $this->response(['status' => 'success'], 201);
        } else {
            $this->response(['status' => 'fail'], 502);            
        }
    }

}
