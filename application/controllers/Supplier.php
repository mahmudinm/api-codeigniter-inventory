<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends CI_Controller {


  function __construct()
  {
      // Construct the parent class
      parent::__construct();
      $this->load->model('Supplier_model');
      $this->load->library('pagination');
  }
  // Coba pagination 
	public function data()
	{
      $config = array();
      $config["base_url"] = base_url() . "supplier/data";
      $config["total_rows"] = $this->Supplier_model->count();
      $config["per_page"] = 7;
      $config["uri_segment"] = 3;
      $this->pagination->initialize($config);

      $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
      $data["results"] = $this->Supplier_model->fetch($config["per_page"], $page);
      $data["links"] = $this->pagination->create_links();

			$this->load->view('welcome_message', $data);
	}
}
