<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Quick_search extends MY_Controller
{
  public function __construct()
  {
   parent::__construct();
   $this->load->model('Rlm_model');
}

public function index()
{
    $this->db->select('customer_num,customer_name');
    $this->db->from('rlm_data');
    $this->db->distinct('customer_num');
    $query = $this->db->get();
    $customers = $query->result();
    $this->db->select('plant_num');
    $this->db->from('plant_parts');
    $this->db->distinct('plant_num');
    $query = $this->db->get();
    $plants = $query->result();
    $this->db->select('part_num');
    $this->db->from('plant_parts');
    $this->db->distinct('part_num');
    $query = $this->db->get();
    $parts = $query->result();
    $this->db->select('import_date');
    $this->db->from('rlm_data');
    $this->db->distinct('import_date');
    $query = $this->db->get();
    $import_dates = $query->result();

    $this->db->select('rel_date');
    $this->db->from('rlm_data');
    $this->db->distinct('rel_date');
    $query = $this->db->get();
    $rel_dates = $query->result();

    $this->view_data['customers'] = $customers;
    $this->view_data['import_dates'] = $import_dates;
    $this->view_data['rel_dates'] = $rel_dates;
    $this->view_data['plants'] = $plants;
    $this->view_data['parts'] = $parts;
    $this->view_data['upload_action'] = 'quick_search/import_data';
    $this->view_data['export_url'] = 'quick_search/export_result';
    $this->view_data['form_action'] = 'quick_search/export_release';
    $this->content_view = 'mymodules/quick_search';
}

public function export_release(){
    $customer_num = $_POST['customer_num'];
    $result = [];
    $data = [];
    $headers = array('RLM Key', 'Customer Number', 'Customer Name', 'Import Week', 'Plant Number','Part Number','Recevied Qty','Recevied ASN ID','CUM Recevied Qty','Past Due','Release Number','Release Date','Vendor Code');
    $import_date = date('Ymd',strtotime('last monday'));
    if(date('M') == "Mon")
        $import_date = date('Ymd');
    while(empty($result)){
        $this->db->select('rlmkey,customer_num,customer_name,import_date,plant_num,part_num,rec_qty,rec_asn_id,cum_rec_qty,past_due,rel_num,rel_date,vendor_code');
        $this->db->from('rlm_data');
        $this->db->where('import_date',$import_date);
        $this->db->where('customer_num',$customer_num);
        $this->db->distinct('rlmkey');
        $this->db->order_by('id','DESC');
        $query = $this->db->get();
        $result = $query->result_array();        
        $import_date = date('Ymd',strtotime($import_date.' last monday'));
    }
    foreach($result as $key=>$row){
        $this->db->select('*');
        $this->db->from('periods');
        $this->db->where('rlmkey',$row['rlmkey']);
        $this->db->limit(20);
        $query = $this->db->get();
        $periods = $query->result_array();
        foreach($periods as $row){
            if(!in_array($row['period'],$headers))
                array_push($headers,$row['period']);
            $result[$key][$row['period']] = $row['value'];   
        }
    }
    $filename = "Latest_".$customer_num."_release_".$import_date.".csv";
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=$filename");
    header("Content-Type: application/csv; ");

        // file creation
    $file = fopen('php://output', 'w');
    fputcsv($file, str_replace('"', '', $headers));
    foreach ($result as $key=>$line){
        fputcsv($file, $line);
    }
    fclose($file);
    exit;
}

public function get_headers(){
   $where_arr = [];
   foreach($_POST as $key=>$row){
      if(!empty($row))
         $where_arr[$key] = $row;
 }
 $result = 0;       
 $result = $this->Rlm_model->getsearch_haeders($where_arr);

}

public function generate_report(){
   $where_arr = [];
   $lead_time = '';
   $import_dates = [];
   foreach($_POST as $key=>$row){
      if($key == 'lead_time'){
         $lead_time = $row;
     }
     else if($key == 'import_date'){
         $temp = $_POST['lead_time']*(-1);
         for($i=$temp;$i<0;$i++){
            array_push($import_dates , date('Ymd',strtotime($row.' '.$i.' monday')));
        }
        array_push($import_dates ,$_POST['import_date']);
    }
    elseif(!empty($row)){
     $where_arr[$key] = $row;
 }
}        
$result = 0;        
$result = $this->Rlm_model->getsearch_data($where_arr,$lead_time,$import_dates);
if($result){
   json_response("success", "RLM data", $result);
} else {
    json_response("error", "Error while sending data to server!", '');
}
        // print_r($result);exit;
        // return $result;
}
public function export_result(){

}
}
