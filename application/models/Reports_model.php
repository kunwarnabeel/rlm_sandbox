<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports_model extends CI_Model  {

    public function __construct()
    {
        parent::__construct();
        $this->table = 'reports';
    }

    public function get_reports_mapping($report_id='0'){
        // echo $customer_number;exit;
        try{
        $this->db->select('*');
        $this->db->from($this->table);
        if($report_id != '0')
        $this->db->where('id',$report_id);
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
    public function update_export_date($id){
     try{
        $this->db->where('id', $id);
        $temp_arr = array('last_exported_date'=>date('Ymd'));
        $this->db->update($this->table, $temp_arr);
        }
        //catch exception
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
            exit;
        }   
    }
    public function get_rlm_data($select,$condition){
        $condition = trim($condition);
        try{
            $query = "Select ".$select." FROM rlm_data ";
            $condition_arr = [];
            if(!empty($condition) && $condition!="''"){
                $condition_arr = explode(",",$condition);
                foreach($condition_arr as $key => $row){
                    if($key == 0 && ($row!='' || $row == NULL))
                    $query .= "WHERE ".$row;
                    else
                    $query .= "AND ".$row;            
                }
        }
        $q = $this->db->query($query);
        $data = $q->result_array();
        
        // print_r($data);exit;
        return $data;
        }
        //catch exception
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
            exit;
        }   
    }

}