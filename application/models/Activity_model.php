<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity_model extends CI_Model  {

    public function __construct()
    {
        parent::__construct();
        $this->table = 'activity_log';
    }

    public function get_activities($status = 'All'){
        // echo $customer_number;exit;
        try{
        $this->db->select('*');
        $this->db->from($this->table);
        if($status != 'All')
            $this->db->where('activity_status',$status);
        $this->db->order_by('id','DESC');
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
        }
        //catch exception
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
            exit;
        }
    }

    public function get_date_format($customer_num){
        try{
        $this->db->select('date_format');
        $this->db->from($this->table);
        if($customer_num != '0')
        $this->db->where('customer_num',$customer_num);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data[0]['date_format'];
        }
        //catch exception
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
            exit;
        }   
    }
    // public function insert_mapping($mapping_arr){

    // }
    public function update_mapping($mapping_arr,$id){
        $this->db->where('id', $id);
        $this->db->update($this->table, $mapping_arr);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
}