<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barang_model extends CI_Model {
    
   public function count() {
       return $this->db->count_all("barang");
   }

   public function fetch($limit, $start, $nama) {
       $this->db->limit($limit, $start);
       $this->db->like('nama', $nama);
       $this->db->order_by('id', 'ASC');
       $query = $this->db->get("barang");
       if ($query->num_rows() > 0) {
           foreach ($query->result() as $row) {
               $data[] = $row;
           }
           return $data;
       }
       return [];
   }

}
