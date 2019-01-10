<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Barang extends BD_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->auth();
        $this->load->database();
    }

    public function index_get()
    {
        $barang = $this->db->get('barang')->result();
        $response['status'] = "success";
        $response['data'] = $barang;
        
        $this->response($response, 200);
    }

    public function index_post()
    {
        $data = [
            'kode' => $this->post('kode'),
            'nama' => $this->post('nama'),
            'stock' => $this->post('stock'),
            'harga' => $this->post('harga'),
            'ukuran' => $this->post('ukuran'),
            'kategori' => $this->post('kategori')
        ];
        
        $config['upload_path'] = './upload/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = '16000000';
        $config['max_width']  = '1110024';
        $config['max_height']  = '115768';

        $this->load->library('upload', $config);
        
        if ($this->upload->do_upload('gambar')) {            
            $gambar = array('upload_data' => $this->upload->data());
            $file_name = $gambar['upload_data']['file_name'];
            $data['gambar'] = $file_name;
        }
    
        $insert = $this->db->insert('barang', $data);

        if ($insert) {
            $this->response(['status' => 'success'], 200);
        } else {
            $this->response(['status' => 'fail', 200]);            
        }


    }

    public function update_post($id)
    {
        $data = [
            'kode' => $this->post('kode'),
            'nama' => $this->post('nama'),
            'stock' => $this->post('stock'),
            'harga' => $this->post('harga'),
            'ukuran' => $this->post('ukuran'),
            'kategori' => $this->post('kategori')
        ];

        $config['upload_path'] = './upload/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = '16000000';
        $config['max_width']  = '1110024';
        $config['max_height']  = '115768';

        $this->load->library('upload', $config);
        
        if ($this->upload->do_upload('gambar')) {            
            $gambar = array('upload_data' => $this->upload->data());
            $file_name = $gambar['upload_data']['file_name'];
            $data['gambar'] = $file_name;
        }
        
        $this->db->where('id', $id);
        $update = $this->db->update('barang', $data);

        if ($update) {
            $this->response(['status' => 'success'], 200);
        } else {
            $this->response(['status' => 'fail'], 200);
        }

    }

    public function delete_post($id)
    {
        // $id = $this->post('id');

        $this->db->where('id', $id);
        $data = $this->db->get('barang')->row_array();
        $gambar = $data['gambar'];
        $path = './upload/'.$gambar;
        unlink($path);

        $this->db->where('id', $id);
        $delete = $this->db->delete('barang');

        if ($delete) {
            $this->response(['status' => 'success'], 201);
        } else {
            $this->response(['status' => 'fail'], 502);            
        }
    }

}
