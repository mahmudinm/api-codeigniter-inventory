<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Penjualan extends BD_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // $this->auth();
        $this->load->database();
        $this->load->model('Penjualan_model');
        $this->load->library('pagination');
    }


    public function index_get()
    {

        $config = array();
        $config["base_url"] = base_url() . "api/penjualan";
        $config["total_rows"] = $this->Penjualan_model->count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        if ($this->query('search')) {
            $penjualan = $this->db->select('penjualan.*, barang.nama, barang.harga')
                    ->from('penjualan')
                    ->join('barang', 'penjualan.barang_id = barang.id', 'LEFT')
                    ->limit($config["per_page"], $page)
                    ->order_by('id', 'ASC')
                    ->like('barang.nama', $this->query('search'))
                    ->get()
                    ->result();
            $response['status'] = "success";
            $response['data'] = $penjualan;        
            $this->response($response, 200);
        } else {
            $penjualan = $this->db->select('penjualan.*, barang.nama, barang.harga')
                                ->from('penjualan')
                                ->join('barang', 'penjualan.barang_id = barang.id', 'LEFT')
                                ->order_by('id', 'ASC')
                                ->limit($config["per_page"], $page)
                                ->get()
                                ->result();
            // $penjualan = $this->db->get('penjualan')->result();
            $response['status'] = "success";
            $response['data'] = $penjualan;
            
            $this->response($response, 200);
        }
    }

    public function index_post()
    {
        $data = [
            'barang_id' => $this->post('barang_id'),
            'jumlah_barang' => $this->post('jumlah_barang'),
            'jumlah_harga' => $this->post('jumlah_harga'),
            'tanggal' => date("Y-m-d H:i:s")
        ];
    		
        // Mengurangi jumlah data stock barang
	    $this->db->set('stock', 'stock - ' . (int) $data['jumlah_barang'], FALSE);
	    $this->db->where('id', $data['barang_id']);
	    $this->db->update('barang'); 
		    
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
            'tanggal' => date("Y-m-d H:i:s")
        ];
        
        // Penambahan STOCK Barang dan Pengurangan Jumlah Barang Penjualan
        // Jumlah awal = 12
        // Jumlah yang di isi = 10 adalah var $data['jumlah_barang'] 
        // Jumlah awal - jumlah isi = 2
        // Stock Barang jadi 8 + 2 = 10

        // Pengurangan STOCK Barang dan Penambahan Jumlah Barang Penjualan
        // Jumlah awal = 10
        // Jumlah yang di isi = 12 adalah var $data['jumlah_barang'] 
        // Jumlah awal (10) - jumlah isi (12) = -2
        // Stock Barang jadi 8 + -2 = 6

        $this->db->where('id', $id);
        $data_jumlah = $this->db->get('penjualan')->row_array();
        $jumlah_awal = $data_jumlah['jumlah_barang'];
        
        if ($jumlah_awal < $this->post('jumlah_barang')) {
            // Mengurangi barang / memberikan item barang ke penjualan 
            $total = $jumlah_awal - $this->post('jumlah_barang');
            $this->db->set('stock', 'stock + ' . (int) $total, FALSE);
            $this->db->where('id', $data['barang_id']);
            $this->db->update('barang');             
        } else if ($jumlah_awal > $this->post('jumlah_barang')) {
            // Menambah barang / mengembalikan item barang ke database 
            $total = $jumlah_awal - $this->post('jumlah_barang');
            $this->db->set('stock', 'stock + ' . (int) $total, FALSE);
            $this->db->where('id', $data['barang_id']);
            $this->db->update('barang');
        } else if ($jumlah_awal == $this->post('jumlah_barang')) {
            // Do nothing
        }

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
        // Mengembalikan Barang yang telah di beli
        $this->db->where('id', $id);
        $data_jumlah = $this->db->get('penjualan')->row_array();
        $jumlah_barang = $data_jumlah['jumlah_barang'];

        $this->db->set('stock', 'stock + ' . (int) $jumlah_barang, FALSE);
        $this->db->where('id', $data_jumlah['barang_id']);
        $this->db->update('barang');             

        $this->db->where('id', $id);
        $delete = $this->db->delete('penjualan');

        if ($delete) {
            $this->response(['status' => 'success'], 201);
        } else {
            $this->response(['status' => 'fail'], 502);            
        }
    }

}
