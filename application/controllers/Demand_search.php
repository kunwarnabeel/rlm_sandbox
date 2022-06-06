<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Demand_search extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    if(!$this->user)
    {
     redirect('login');
   }
   
 }
 public function export_csv(){
  $this->db->select('customer_number,item_number,period,value');
  $this->db->from('export_data');
  $query = $this->db->get();
  $items = $query->result_array();
   $filename = "Demand_search_".date('m-d-Y').".csv";
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        // file creation
        $file = fopen('php://output', 'w');
        $header = array('Customer Number','Item Number', 'Period', 'Value');
        fputcsv($file, str_replace('"', '', $header));
        foreach ($items as $key=>$line){
            fputcsv($file, $line);
        }
        fclose($file);
        exit;
 }
 public function export_search_sales(){
  $data = [];
  $dataa = json_decode($_POST['data']);
  // print_r($dataa[0][1]);exit;
  $items = array();
  $customers = array();
  $periods = array();
  foreach($dataa as $row)
  {
    array_push($customers,$row[0]);
    array_push($items,$row[1]);
    array_push($periods,$row[2]);
  }
  $this->db->select('I.item_number,S.period,S.value');
  $this->db->from('items I');
  $this->db->join('sales_data S','S.item_number = I.item_number');
  $this->db->where_in('I.item_number',$items);
  $this->db->where_in('S.period',$periods);
  $query = $this->db->get();
  $result = $query->result_array();
  foreach($result as $row)
  {
    $data[] = array
            (
              'customer_number' => '',
              'item_number' => trim($row['item_number']),
              'period' => trim($row['period']),
              'value' => trim($row['value'])
          );
  }
  $this->db->truncate('export_data');
  $inserted = $this->db->insert_batch('export_data',$data);
  if($inserted)
    echo 'success';
    else
      echo 'error';
  exit;
  
 }

public function export_search_orders(){
  $data = [];
  $dataa = json_decode($_POST['data']);
  // print_r($dataa[0][1]);exit;
  $items = array();
  $customers = array();
  $periods = array();
  foreach($dataa as $row)
  {
    array_push($customers,$row[0]);
    array_push($items,$row[1]);
    array_push($periods,$row[2]);
  }
  $this->db->select('I.item_number,S.period,S.value');
  $this->db->from('items I');
  $this->db->join('order_data S','S.item_number = I.item_number');
  $this->db->where_in('I.item_number',$items);
  $this->db->where_in('S.period',$periods);
  $query = $this->db->get();
  $result = $query->result_array();
  foreach($result as $row)
  {
    $data[] = array
            (
              'customer_number' => '',
              'item_number' => trim($row['item_number']),
              'period' => trim($row['period']),
              'value' => trim($row['value'])
          );
  }
  $this->db->truncate('export_data');
  $inserted = $this->db->insert_batch('export_data',$data);
  if($inserted)
    echo 'success';
    else
      echo 'error';
  exit;
  
 }

 public function sales_review()
 {
  $this->db->select('I.item_number,I.cat1,I.cat2,I.cat3,I.cat4,I.cat5,I.cat6,I.cat7,I.cat8,I.cat9,I.cat10,S.period');
  $this->db->from('items I');
  // $this->db->join('order_data O','O.item_number = O.item_number');
  $this->db->join('sales_data S','S.item_number = I.item_number');
  $this->db->where('I.item_number !=','');
  // $this->db->group_by('I.item_number');
  $query = $this->db->get();
  $items = $query->result_array();
  $this->view_data['items'] = $items; 
  $this->view_data['title'] = 'Sales'; 
  $this->view_data['export_url'] = 'demand_search/export_search_sales'; 
  $this->content_view = 'mymodules/demand_search';
}

public function orders_review(){
  $this->db->select('I.item_number,I.cat1,I.cat2,I.cat3,I.cat4,I.cat5,I.cat6,I.cat7,I.cat8,I.cat9,I.cat10,O.period');
  $this->db->from('order_data O');
  $this->db->join('items I','O.item_number = I.item_number','left');
  // $this->db->join('sales_data S','S.item_number = I.item_number');
  $this->db->where('I.item_number !=','');
  // $this->db->group_by('I.item_number');
  $query = $this->db->get();
  $items = $query->result_array();    
  $this->view_data['items'] = $items; 
  $this->view_data['title'] = 'Orders';
  $this->view_data['export_url'] = 'demand_search/export_search_orders'; 
  $this->content_view = 'mymodules/demand_search';
}

public function forecast_review(){
  $this->db->select('I.item_number,I.cat1,I.cat2,I.cat3,I.cat4,I.cat5,I.cat6,I.cat7,I.cat8,I.cat9,I.cat10,S.period');
  $this->db->from('order_data O');
  $this->db->join('items I','O.item_number = I.item_number','left');
  // $this->db->join('sales_data S','S.item_number = I.item_number');
  $this->db->where('I.item_number !=','');
  // $this->db->group_by('I.item_number');
  $query = $this->db->get();
  $items = $query->result_array();    
  $this->view_data['items'] = $items; 
  $this->view_data['title'] = 'Orders';
  $this->view_data['export_url'] = 'demand_search/export_search_orders'; 
  $this->content_view = 'mymodules/demand_search';
}
}