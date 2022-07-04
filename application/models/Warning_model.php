<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Warning_model extends CI_Model  {

    public function __construct()
    {
        parent::__construct();
        $this->table = 'warning';
    }

    public function get_warnings(){
        try{
        $this->db->select('*');
        $this->db->from($this->table);
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

    public function get_warning_logs(){
        try{
        $this->db->select('*');
        $this->db->from('warning_log');
        $this->db->order_by('id','DESC');
        $this->db->order_by('status','Open');
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

    public function get_threshold($id){
      try{
        $this->db->select('threshold');
        $this->db->from($this->table);
        $this->db->where('id',$id);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data[0]['threshold'];
        }
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
            exit;
        }  
    }

    public function get_warning_contacts(){
        try{
        $this->db->select('*');
        $this->db->from('contact_book');
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

    public function update_warning_contact($warning_arr,$id){
        $this->db->where('id', $id);
        $this->db->update('contact_book', $warning_arr);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function update_warnings($warning_arr,$id){
        $this->db->where('id', $id);
        $this->db->update($this->table, $warning_arr);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function update_warninglog($warning_arr,$id){
        $this->db->where('id', $id);
        $this->db->update('warning_log', $warning_arr);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function update_warninglog_by_rlmKey($warning_arr,$rlmKey){
        $this->db->where('rlm_key', $rlmKey);
        $this->db->update('warning_log', $warning_arr);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function create_warning($data){
        $this->db->insert('warning_log',$data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    
    public function getWarningLog($rlm_key){
        $this->db->select('status,user_note,in_transit_num');
        $this->db->from('warning_log');
        $this->db->where('rlm_key',$rlm_key);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    
    public function getRlmWarning($rlm_key){
        $this->db->select('sws_part_num,customer_num,rlmkey,rel_num, plant_num,part_num, vendor_code,rec_qty,rec_asn_id,last_rcv_date, cum_rec_qty');
        $this->db->from('rlm_data');
        $this->db->where('rlmkey',$rlm_key);
       // $this->db->order_by('id','DESC');
       // $this->db->limit("1");
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    public function getPlantDetails($customer_num,$plantNum,$partNum,$swsNum){
        $this->db->select('oracle_id,ship_to_location');
        $this->db->from('plant_parts');
        $this->db->where('customer_num',$customer_num);
        $this->db->where('plant_num',$plantNum);
        $this->db->where('part_num',$partNum);
        $this->db->where('sws_part_num',$swsNum);
       // $this->db->order_by('id','DESC');
       // $this->db->limit("1");
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    public function getPeriodWarning($rlm_key){
        $this->db->select('period,value');
        $this->db->from('periods');
        $this->db->where('rlmkey ',$rlm_key);
        $this->db->order_by('id','ASC');
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    public function getTransitData($sws_part_num,$oracle_id,$ship_to_location,$part_num){
        $this->db->select('ship_to_location,customer_name,customer_num,part_number, description,schedule_ship_date,actual_ship_date,period,qty_due,qty_shipped,qty_open');
        $this->db->from('transit_data');
        $this->db->where('status',1);
        $this->db->where_in('part_number',[$sws_part_num,$part_num]);
        $this->db->where('customer_num',$oracle_id);
        $this->db->where('ship_to_location',$ship_to_location);
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
}