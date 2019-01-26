<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penjualan_model extends CI_Model {
    
   public function count() {
       return $this->db->count_all("penjualan");
   }

   public function fetch($limit, $start, $nama) {
       $this->db->limit($limit, $start);
       $this->db->like('nama', $nama);
       $this->db->order_by('id', 'ASC');
       $query = $this->db->get("penjualan");
       if ($query->num_rows() > 0) {
           foreach ($query->result() as $row) {
               $data[] = $row;
           }
           return $data;
       }
       return [];
   }

}
