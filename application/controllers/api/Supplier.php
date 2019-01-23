<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class Supplier extends BD_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->auth();
        $this->load->database();
        $this->load->model('Supplier_model');
        $this->load->library('pagination');
    }


    public function index_get()
    {        
        $config = array();
        $config["base_url"] = base_url() . "api/supplier";
        $config["total_rows"] = $this->Supplier_model->count();
        $config["per_page"] = 10;
        $config["uri_segment"] = 3;
        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        if ($this->query('search')) {
            // http://localhost/android_codeigniter_inventory/api/supplier/2?search=ma
            $data["data"] = $this->Supplier_model->fetch($config["per_page"], $page, $this->query('search'));
            $data["links"] = $this->pagination->create_links();
            $data['status'] = "success";

            $this->response($data, 200);
        } else {
            $data["data"] = $this->Supplier_model->fetch($config["per_page"], $page, "");
            $data["links"] = $this->pagination->create_links();
            $data['status'] = "success";

            $this->response($data, 200);
        }
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
