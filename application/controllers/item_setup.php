<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Item_setup extends MY_Controller
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

    $this->view_data['form_action'] = 'item_setup/insert_data';
    $this->view_data['upload_action'] = 'item_setup/import_data';
    $this->content_view = 'mymodules/item_setup';
}

public function item_list(){
    $this->db->select('item_number,item_desc,cat1,cat2,cat3,cat4,cat5,cat6,cat7,cat8,cat9,cat10');
    $this->db->from('items');
    $this->db->where('item_number !=','');
    $query = $this->db->get();
    $items = $query->result_array();
    $this->view_data['items'] = $items;
    $this->view_data['form_action'] = 'item_setup/show_Details';
    $this->content_view = 'mymodules/item_list';
}

public function show_Details(){
    
}
public function item_delete($id){
    $this->db->where('item_number', $id);
        $res = $this->db->delete('items');
        if($res)
        {
            $this->session->set_flashdata('message', 'success: Item deleted');
        
        } else {
            $this->session->set_flashdata('message', 'error: Something went wrong');
        }
        redirect('item_setup');
}

public function insert_data(){
    $itemRes = 0;
    if ($_POST) {
        $item_data = array(
            'item_number' => trim($_POST['item_num']),
            'item_desc' => trim($_POST['item_desc']),
            'cat1' => trim($_POST['cat_1']),
            'cat2' => trim($_POST['cat_2']),
            'cat3' => trim($_POST['cat_3']),
            'cat4' => trim($_POST['cat_4']),
            'cat5' => trim($_POST['cat_5']),
            'cat6' => trim($_POST['cat_6']),
            'cat7' => trim($_POST['cat_7']),
            'cat8' => trim($_POST['cat_8']),
            'cat9' => trim($_POST['cat_9']),
            'cat10' => trim($_POST['cat_10'])
        );
        $this->db->where('item_number',$_POST['item_num']);
        $query = $this->db->get('items');
        if ($query->num_rows() > 0){
            $this->db->where('item_number', $_POST['item_num']);
            $itemRes = $this->db->update('items', $item_data);
        }
        else{
            $itemRes = $this->db->insert('items', $item_data);
        }
    }
    if($itemRes)
    {
       $this->session->set_flashdata('message', 'success: Item(s) added successfully');
   }
   else{
    $this->session->set_flashdata('message', 'error: Something went wrong');
}
redirect('item_setup');
}

public function import_data(){
    $reset_ids = [];
    $filename = $_FILES['csv_file']['name'];
    $file_data = $this->csvimport->get_array($_FILES["csv_file"]["tmp_name"]);
    // ini_set('memory_limit', '1M');
    $ids = [];
    $count = 1;
    $error_table = '<div class="row">
    <div class="col-md-8 col-md-offset-2"><table id="error_table" class="table table-hover"><tr><th class="text-center">Row #</th><th class="text-center">Errors</th></tr>';
    $error_count = 0;    
    foreach($file_data as $row)
    {
        $row = array_change_key_case($row,CASE_LOWER);
        if(!array_key_exists("item number",$row) || !array_key_exists("description",$row) || !array_key_exists("category 1",$row) || !array_key_exists("category 2",$row) || !array_key_exists("category 3",$row) || !array_key_exists("category 4",$row) || !array_key_exists("category 5",$row)) || !array_key_exists("category 6",$row)) || !array_key_exists("category 7",$row)) || !array_key_exists("category 8",$row)) || !array_key_exists("category 9",$row)) || !array_key_exists("category 10",$row))
        {
        echo 'headerError';exit;
        }
        $error = '';
        $row = array_change_key_case($row, CASE_LOWER);
        if($error_count ==0){
        $data = array
        (
          'item_number' => trim($row["item number"]),
          'item_desc'  => trim($row["description"]),
          'cat1'   =>   trim( $row["category 1"]),
          'cat2'   =>   trim( $row["category 2"]),
          'cat3'   =>   trim( $row["category 3"]),
          'cat4'   =>   trim( $row["category 4"]),
          'cat5'   =>   trim( $row["category 5"]),
          'cat6'   =>   trim( $row["category 6"]),
          'cat7'   =>   trim( $row["category 7"]),
          'cat8'   =>   trim( $row["category 8"]),
          'cat9'   =>   trim( $row["category 9"]),
          'cat10'   =>   trim( $row["category 10"])
      );
        }
        $item_num = trim($row["item number"]);
        $item_desc = trim($row["description"]);
        if($item_num==""){
            $error = 'Item number is required';
            $error_count++;
        }
        else if(strlen($item_num) !=8 || !is_numeric($item_num)){
            $error= 'Item number should contain 8 numeric digits';
            $error_count++;
        }
        else if(empty($item_desc)){
            $error = 'Item description is required';   
            $error_count++;
        }
        if($error_count != 0 && !empty($error))
        $error_table.= '<tr>
            <td>'.$count.'</td>
            <td class="error_text">'.$error.'</td>
        </tr>';
        if(!empty($row["item number"]) && $error_count == 0)
        {
            $res=  $this->db->replace('items', $data);
        }
        $count++;
    }
    if($error_count !=0)
    {
        $error_table .= '</table></div></div>';
        $temp = $error_table;
        echo $temp;exit;
    }
    echo 'success';exit;
}

public function exportall(){
    $this->db->select('item_number,item_desc,cat1,cat2,cat3,cat4,cat5,cat6,cat7,cat8,cat9,cat10');
    $this->db->from('items');
    $this->db->where('item_number !=','');
    $query = $this->db->get();
    $items = $query->result_array();
    
    $filename = "Item_list_".date('m-d-Y').".csv";
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        // file creation
        $file = fopen('php://output', 'w');
        $header = array('Item Number', 'Description', 'Category 1', 'Category 2', 'Category 3', 'Category 4', 'Category 5');
        fputcsv($file, str_replace('"', '', $header));
        foreach ($items as $key=>$line){
            fputcsv($file, $line);
        }
        fclose($file);
        exit;
}
}

