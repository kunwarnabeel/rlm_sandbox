<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Report_builder extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Reports_model');
    $this->load->model('Mapping_model');
  }

  public function index(){
    $select = 'customer_num,customer_name,plant_num,part_num,vendor_code,rel_num,rel_date, rec_qty,rec_asn_id,cum_rec_qty,past_due,last_rcv_date';
    $report_list = $this->Reports_model->get_reports_mapping();
    $column_list = $this->Mapping_model->get_mapping(0,$select);
    $this->view_data['reports'] = $report_list;     
    $this->view_data['columns_list'] = $column_list[0];     
    $this->view_data['form_action'] = 'report_builder/export';     
    $this->view_data['insert_action'] = 'report_builder/create_report';     
    $this->content_view = 'mymodules/report_builder';
  }
  public function create_report()
  {
    $conditions = '';
    if(empty($_REQUEST['report_name']) || !isset($_REQUEST['selected_columns']))
    {
      $this->session->set_flashdata('message', 'error: Report name is required and atleast one column should be selected');
    } else 
    {
      $selected_columns = '';
      foreach($_REQUEST['selected_columns'] as $key=>$row){
        if($key !=0)
          $selected_columns.=','.$row;
        else
          $selected_columns.=$row;
      }
      if(isset($_REQUEST['condition_field']))
      {
        foreach($_REQUEST['condition_field'] as $key=>$row)
        {
          if($key !=0)
            $condition .= ",".$row." ".$_REQUEST['condition'][$key]." '".$_REQUEST['condition_value'][$key]."'";
          else 
            $condition .= $row." ".$_REQUEST['condition'][$key]." '".$_REQUEST['condition_value'][$key]."'";
        }
      }    
      $report_temp = array(
                'report_name'=>$_REQUEST['report_name'],
                'columns'=>$selected_columns,
                'conditions'=>$condition,
                'created_date'=>date('Ymd'),
                'last_exported_date'=>'',
              );
      $reportRes = $this->db->insert('reports', $report_temp);
      $this->session->set_flashdata('message', 'success: Template created successfully');
    }
    redirect('report_builder');
  }
  public function delete_template(){
    $this->db->where('id', $_GET['id']);
        $res = $this->db->delete('reports');
        if($res)
        {
            $this->session->set_flashdata('message', 'success: Report deleted');
        
        } else {
            $this->session->set_flashdata('message', 'error: Something went wrong');
        }
        redirect('report_builder');
  }
  public function export(){
    $id = $_GET['id'];
    $report_mapping = $this->Reports_model->get_reports_mapping($id);
    $this->Reports_model->update_export_date($id);
    $select = $report_mapping[0]['columns'];
    $conditions = $report_mapping[0]['conditions'];
    //update export date in reports

    $report_data = $this->Reports_model->get_rlm_data($select,$conditions);
    $filename = "RLM_CUSTOM_REPORT_".date('m-d-Y').".csv";
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        // file creation
        $file = fopen('php://output', 'w');
        $header = explode(',', $select);
        fputcsv($file, $header);
        foreach ($report_data as $key=>$line){

          fputcsv($file, $line);
        }
        fclose($file);
        exit;
  }
}
