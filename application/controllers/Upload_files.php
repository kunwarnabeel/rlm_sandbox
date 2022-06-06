<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Upload_files extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();


        if(!$this->user)
        {
           redirect('login');
       }
       $this->load->library('csvimport');
   }

   public function index()
   {
    $this->db->from('sf_data');
    $sf_data_date = $this->db->get()->row()->data_date;
    $this->db->from('order_data');
    $orders_data_date = $this->db->get()->row()->data_date;
    $this->db->from('sales_data');
    $sales_data_date = $this->db->get()->row()->data_date;
    
    $this->view_data['sf_last_uploaded'] = $sf_data_date;
    $this->view_data['order_last_uploaded'] = $orders_data_date;
    $this->view_data['sales_last_uploaded'] = $sales_data_date;

    $this->view_data['sf_action'] = 'upload_files/sf_data';
    $this->view_data['order_action'] = 'upload_files/order_data';
    $this->view_data['sales_action'] = 'upload_files/sales_data';
    $this->content_view = 'mymodules/upload_files';
}

public function sf_data(){
    try{
        $filename = $_FILES['csv_file']['name'];
        $filename = strtolower($filename);
        if(strpos($filename,'forecast')===false || strpos($filename,'.csv')===false)
        {
            echo 'wrongfile';exit;
        }
        $file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
        $data = [];
        $count = 1;
        $error_table = '<div class="row">
        <div class="col-md-8 col-md-offset-2"><table id="error_table" class="table table-hover"><tr><th class="text-center">Row #</th><th class="text-center">Errors</th></tr>';
        $arrKeys = array_keys($file_data[0]);
        $arrKeys = array_map('trim', $arrKeys);
        $error_count = 0;    

        $required_headers = array($arrKeys[0]);
        for($i=-16;$i<20;$i++)
        {
            if($i !=0)
                $current_header = date('M Y',strtotime( $i.' months'));
            else
                $current_header = date('M Y');
            $keyIndex = array_search("Sum of ".$current_header." Forecasted Volume",$arrKeys);
            if($keyIndex < 0 || $keyIndex == false || empty($keyIndex)){
                echo 'headerError';exit;
            }

            array_push($required_headers,$arrKeys[$keyIndex]);
        }
        $arrKeys = array_map('strtolower', $required_headers);
        unset($required_headers);
        $data_date = date('M Y');
        foreach($file_data as $row)
        {
            $row = array_change_key_case($row,CASE_LOWER);
            $error = '';
            $item_number = trim($row[$arrKeys[0]]);
            if($item_number==""){
                $error = 'Item number is required';
                $error_count++;
            }
            else if(strlen($item_number) !=8 || !is_numeric($item_number)){
                $error= 'Item number should contain 8 numeric digits';
                $error_count++;
            }
            if($error_count ==0){
                $data[] = array
                (
                  'item_number' => trim($row[$arrKeys[0]]),
                  'data_date' => $data_date,
                  'month1'  => trim($row[$arrKeys[1]]),
                  'month2'  => trim($row[$arrKeys[2]]),
                  'month3'  => trim($row[$arrKeys[3]]),
                  'month4'  => trim($row[$arrKeys[4]]),
                  'month5'  => trim($row[$arrKeys[5]]),
                  'month6'  => trim($row[$arrKeys[6]]),
                  'month7'  => trim($row[$arrKeys[7]]),
                  'month8'  => trim($row[$arrKeys[8]]),
                  'month9'  => trim($row[$arrKeys[9]]),
                  'month10'  => trim($row[$arrKeys[10]]),
                  'month11'  => trim($row[$arrKeys[11]]),
                  'month12'  => trim($row[$arrKeys[12]]),
                  'month13'  => trim($row[$arrKeys[13]]),
                  'month14'  => trim($row[$arrKeys[14]]),
                  'month15'  => trim($row[$arrKeys[15]]),
                  'month16'  => trim($row[$arrKeys[16]]),
                  'month17'  => trim($row[$arrKeys[17]]),
                  'month18'  => trim($row[$arrKeys[18]]),
                  'month19'  => trim($row[$arrKeys[19]]),
                  'month20'  => trim($row[$arrKeys[20]]),
                  'month21'  => trim($row[$arrKeys[21]]),
                  'month22'  => trim($row[$arrKeys[22]]),
                  'month23'  => trim($row[$arrKeys[23]]),
                  'month24'  => trim($row[$arrKeys[24]]),
                  'month25'  => trim($row[$arrKeys[25]]),
                  'month26'  => trim($row[$arrKeys[26]]),
                  'month27'  => trim($row[$arrKeys[27]]),
                  'month28'  => trim($row[$arrKeys[28]]),
                  'month29'  => trim($row[$arrKeys[29]]),
                  'month30'  => trim($row[$arrKeys[30]]),
                  'month31'  => trim($row[$arrKeys[31]]),
                  'month32'  => trim($row[$arrKeys[32]]),
                  'month33'  => trim($row[$arrKeys[33]]),
                  'month34'  => trim($row[$arrKeys[34]]),
                  'month35'  => trim($row[$arrKeys[35]]),
                  'month36'  => trim($row[$arrKeys[36]]),
              );
            }

            if($error_count != 0 && !empty($error))
                $error_table.= '<tr>
            <td>'.$count.'</td>
            <td class="error_text">'.$error.'</td>
            </tr>';
            $count++;
        }
        if($error_count !=0)
        {
            $error_table .= '</table></div></div>';
            $temp = $error_table;
            echo $temp;exit;
        }
        $this->db->where('data_date',$data_date);
        $this->db->delete('sf_data');
        $result = $this->db->insert_batch('sf_data', $data);
        if($result){
            echo 'success';exit;}
            else{
                echo 'Something went wrong contact with developer';exit;
            }
        }
        catch(Exception $e) {
          echo 'headerError';exit;
      }
  }
  public function order_data(){
    try{
     $filename = $_FILES['csv_file']['name'];
     $filename = strtolower($filename);  
        if(strpos($filename,'ordersactual') === false || strpos($filename,'.csv')===false)
        {
            echo 'wrongfile';exit;
        }
     $file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
     $data = [];
     $count = 1;
     $error_table = '<div class="row">
     <div class="col-md-8 col-md-offset-2"><table id="error_table" class="table table-hover"><tr><th class="text-center">Row #</th><th class="text-center">Errors</th></tr>';
     $arrKeys = array_keys($file_data[0]);
     $arrKeys = array_map('strtolower', $arrKeys);
     $error_count = 0;    
     $data_date = date('M Y');
    // $this->db->where('data_date',$data_date);
    // $this->db->delete('order_data');
     foreach($file_data as $row)
     {
        $row = array_change_key_case($row,CASE_LOWER);
        $error = '';
        $item_number = trim($row[$arrKeys[0]]);
        $period = trim($row[$arrKeys[1]]);
        $value = trim($row[$arrKeys[2]]);
        if($item_number==""){
            $error = 'Item number is required';
            $error_count++;
        }
        else if(strlen($item_number) !=8 || !is_numeric($item_number)){
            $error= 'Item number should contain 8 numeric digits';
            $error_count++;
        }
        else if($period == ""){
            $error= 'Period missing';
            $error_count++;   
        }
        else if($value == ""){
            $error= 'Quantity missing';
            $error_count++;   
        }
        if($error_count ==0){
            $data[] = array
            (
              'item_number' => trim($row[$arrKeys[0]]),
              'data_date' => $data_date,
              'period' => date('M Y',strtotime(trim($row[$arrKeys[1]]))),
              'value' => trim($row[$arrKeys[2]])
          );
        }

        if($error_count != 0 && !empty($error))
            $error_table.= '<tr>
        <td>'.$count.'</td>
        <td class="error_text">'.$error.'</td>
        </tr>';
        $count++;
    }
    if($error_count !=0)
    {
        $error_table .= '</table></div></div>';
        $temp = $error_table;
        echo $temp;exit;
    }
    $this->db->truncate('order_data');
    $result = $this->db->insert_batch('order_data', $data);
    if($result){
        echo 'success';exit;
    }
    else{
        echo 'Something went wrong contact with developer';exit;
    }
}
catch(Exception $e) {
  echo 'headerError';exit;
}
}
public function sales_data(){
    try {
        $filename = $_FILES['csv_file']['name'];
        $filename = strtolower($filename);
        if(strpos($filename,'salesactual')===false || strpos($filename,'.csv')===false)
        {
            echo 'wrongfile';exit;
        }
        $file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
        $data = [];
        $count = 1;
        $error_table = '<div class="row">
        <div class="col-md-8 col-md-offset-2"><table id="error_table" class="table table-hover"><tr><th class="text-center">Row #</th><th class="text-center">Errors</th></tr>';
        $arrKeys = array_keys($file_data[0]);
        $arrKeys = array_map('strtolower', $arrKeys);
        $error_count = 0;    
        $data_date = date('M Y');
        foreach($file_data as $row)
        {
            $row = array_change_key_case($row,CASE_LOWER);
            $error = '';
            $item_number = trim($row[$arrKeys[0]]);
            $period = trim($row[$arrKeys[1]]);
            $value = trim($row[$arrKeys[2]]);
            if($item_number==""){
                $error = 'Item number is required';
                $error_count++;
            }
            else if(strlen($item_number) !=8 || !is_numeric($item_number)){
                $error= 'Item number should contain 8 numeric digits';
                $error_count++;
            }
            else if($period == ""){
                $error= 'Period missing';
                $error_count++;   
            }
            else if($value == ""){
                $error= 'Quantity missing';
                $error_count++;   
            }
            if($error_count ==0){
                $data[] = array
                (
                  'item_number' => trim($row[$arrKeys[0]]),
                  'data_date' => $data_date,
                  'period' => date('M Y',strtotime(trim($row[$arrKeys[1]]))),
                  'value' => trim($row[$arrKeys[2]])
              );
            }

            if($error_count != 0 && !empty($error))
                $error_table.= '<tr>
            <td>'.$count.'</td>
            <td class="error_text">'.$error.'</td>
            </tr>';
            $count++;
        }
        if($error_count !=0)
        {
            $error_table .= '</table></div></div>';
            $temp = $error_table;
            echo $temp;exit;
        }
        $this->db->truncate('sales_data');
        $result = $this->db->insert_batch('sales_data', $data);
        if($result){
            echo 'success';exit;
        }
        else{
            echo 'Something went wrong contact with developer';exit;
        }
    }
    catch(Exception $e) {
      echo 'headerError';exit;
  }
}

}

