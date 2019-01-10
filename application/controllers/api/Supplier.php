<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Supplier extends BD_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // $this->auth();
        $this->load->database();
    }

    public function index_get()
    {
        $supplier = $this->db->get('supplier')->result();
        $response['status'] = "success";
        $response['data'] = $supplier;
        
        $this->response($response, 200);
    }

    public function index_post()
    {
        $data = [
            'nama' => $this->post('nama'),
            'no_hp' => $this->post('no_hp'),
            'alamat' => $this->post('alamat')
        ];
    
        $insert = $this->db->insert('supplier', $data);

        if ($insert) {
            $this->response(['status' => 'success'], 200);
        } else {
            $this->response(['status' => 'fail', 200]);            
        }


    }

    public function update_post($id)
    {
        $data = [
            'nama' => $this->post('nama'),
            'no_hp' => $this->post('no_hp'),
            'alamat' => $this->post('alamat')
        ];
        
        $this->db->where('id', $id);
        $update = $this->db->update('supplier', $data);

        if ($update) {
            $this->response(['status' => 'success'], 200);
        } else {
            $this->response(['status' => 'fail'], 200);
        }

    }

    public function delete_post($id)
    {
        $this->db->where('id', $id);
        $delete = $this->db->delete('supplier');

        if ($delete) {
            $this->response(['status' => 'success'], 201);
        } else {
            $this->response(['status' => 'fail'], 502);            
        }
    }

}
