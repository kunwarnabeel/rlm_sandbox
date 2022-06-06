<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Activities extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
   //  if(!$this->user)
   //  {
   //   redirect('login');
   // }
   $this->load->model('Activity_model');
 }

 public function index(){
  $activity_list = $this->Activity_model->get_activities();
  $this->view_data['activities'] = $activity_list;     
    $this->content_view = 'mymodules/activity_log';
 }
 public function export_all(){
  $result = $this->Activity_model->get_activities();
  $filename = "All_releases_".date('m-d-Y').".csv";
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        // file creation
        $file = fopen('php://output', 'w');
        $header = array('ID','Import Date','Release Date','Customer', 'Release No', 'Plant Number', 'Part Number', 'SWS Part Number','Status','Message');
        // $header = array('Customer', 'ID', 'Plant', 'Part', 'SWS Part Number');
        fputcsv($file, str_replace('"', '', $header));
        foreach ($result as $key=>$line){
            fputcsv($file, $line);
        }
        fclose($file);
        exit;
}
public function export_rejected(){
  $result = $this->Activity_model->get_activities('Rejected');
  $filename = "Rejected_Releases_".date('m-d-Y').".csv";
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");

        // file creation
        $file = fopen('php://output', 'w');
        // $header = array('Customer', 'ID', 'Plant', 'Part', 'SWS Part Number');
        $header = array('ID','Import Date','Release Date','Customer', 'Release No', 'Plant Number', 'Part Number', 'SWS Part Number','Status','Message');
        fputcsv($file, str_replace('"', '', $header));
        foreach ($result as $key=>$line){
            fputcsv($file, $line);
        }
        fclose($file);
        exit;
}
}