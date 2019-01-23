<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier_model extends CI_Model {
    
   public function count() {
       return $this->db->count_all("supplier");
   }

   public function fetch($limit, $start, $nama) {
       $this->db->limit($limit, $start);
       $this->db->like('nama', $nama);
       $query = $this->db->get("supplier");
       if ($query->num_rows() > 0) {
           foreach ($query->result() as $row) {
               $data[] = $row;
           }
           return $data;
       }
       return false;
   }

}
