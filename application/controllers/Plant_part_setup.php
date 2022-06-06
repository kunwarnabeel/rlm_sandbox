<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Plant_part_setup extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('csvimport');
    }

    public function index()
    {
        $this->db->select('*');
        $this->db->from('plant_parts');
        $this->db->order_by('customer_num');
        $query = $this->db->get();
        $parts = $query->result_array();
        $this->db->select('*');
        $this->db->from('customers');
        $query = $this->db->get();
        $customer_list = $query->result();
        $this->view_data['items'] = $parts;
        $this->view_data['customer_list'] = $customer_list;
        $this->view_data['upload_action'] = 'plant_part_setup/import_data';
        $this->view_data['form_action'] = 'plant_part_setup/insert_plant_part';
        $this->content_view = 'mymodules/plant_part_setup';
    }

    public function insert_plant_part(){

        $itemRes = 0;
        
        if ($_POST) {
            $part_data = array(
                'customer_num' => trim($_POST['customer_num']),
                'plant_num' => trim($_POST['plant_num']),
                'part_num' => trim($_POST['part_num']),
                'sws_part_num' => trim($_POST['sws_part_num']),
                'created_date'=> get_current_date('Ymd'),
                'updated_date'=> get_current_date('Ymd'),
                'oracle_id' => trim($_POST['oracle_id']),
                'ship_to_location' => trim($_POST['ship_to_location']),
            );
            $this->db->where('customer_num',$_POST['customer_num']);
            $this->db->where('plant_num',$_POST['plant_num']);
            $this->db->where('part_num',$_POST['part_num']);
            $query = $this->db->get('plant_parts');
            
            if ($query->num_rows() > 0){
                //$this->session->set_flashdata('message', 'error: Data already exists');
                json_response("error", "Data already exists!", '');
            }
            else{
                $itemRes = $this->db->insert('plant_parts', $part_data);
                $this->session->set_flashdata('message', 'success: Data added successfully');
                json_response("success", $part_data,'');
            }
        }
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
        if(!array_key_exists("customer number",$row) || !array_key_exists("plant number",$row) || !array_key_exists("part number",$row) || !array_key_exists("sws part number",$row) )
        {
        echo 'headerError';exit;
        }
        $error = '';
        $row = array_change_key_case($row, CASE_LOWER);
        
        $customer_num = trim($row["customer number"]);
        if($customer_num=="" || trim($row["plant number"]) == "" || trim($row["part number"]) == "" || trim($row["sws part number"]) == ""){
            $error = 'All fields are required';
            $error_count++;
        }
        if($error_count != 0 && !empty($error))
        $error_table.= '<tr>
            <td>'.$count.'</td>
            <td class="error_text">'.$error.'</td>
        </tr>';
        if($error_count == 0)
        {
            $data = array(
                'customer_num' => trim($row["customer number"]),
                'plant_num' => trim($row["plant number"]),
                'part_num' => trim($row["part number"]),
                'sws_part_num' => trim($row["sws part number"]),
                'created_date'=> get_current_date('Ymd'),
                'updated_date'=> get_current_date('Ymd'),
            );
            $this->db->select('*');
            $this->db->where('customer_num',trim($row["customer number"]));
            $this->db->where('plant_num',trim($row["plant number"]));
            $this->db->where('part_num',trim($row["part number"]));
            $query = $this->db->get('plant_parts');
            if ($query->num_rows() == 0)
            $res=  $this->db->insert('plant_parts', $data);
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

    public function data_delete($id)
    {
        $this->db->where('id', $id);
        $res = $this->db->delete('plant_parts');
        if($res)
        {
            $this->session->set_flashdata('message', 'success: Item deleted');
        
        } else {
            $this->session->set_flashdata('message', 'error: Something went wrong');
        }
        redirect('plant_part_setup');
    }

    public function export_all(){
        $this->db->select('C.customer_name,P.customer_num,P.plant_num,P.part_num,P.sws_part_num');
    $this->db->from('plant_parts P');
    $this->db->join('customers C','C.oracle_acc_num = P.customer_num');
    $this->db->order_by('P.customer_num','DESC');
    $query = $this->db->get();
    $items = $query->result_array();
    
    $filename = "Plants_parts_list_".date('m-d-Y').".csv";
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        // file creation
        $file = fopen('php://output', 'w');
        $header = array('Customer', 'ID', 'Plant', 'Part', 'SWS Part Number');
        fputcsv($file, str_replace('"', '', $header));
        foreach ($items as $key=>$line){
            fputcsv($file, $line);
        }
        fclose($file);
        exit;
    }
}

