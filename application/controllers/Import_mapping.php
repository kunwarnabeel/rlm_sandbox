<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Import_mapping extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
   //  if(!$this->user)
   //  {
   //   redirect('login');
   // }
   $this->load->model('Mapping_model');
 }

 public function index(){
 	$mapping_list = $this->Mapping_model->get_mapping();
 	$this->view_data['mappings'] = $mapping_list;     
 	$this->view_data['form_action'] = 'import_mapping/update';     
  	$this->content_view = 'mymodules/import_file_mapping';
 }
 public function update(){
 	$result = false;
 	$mapping = array(
 		'plant_num'=>$_POST['plant_num'],
 		'file_name'=>$_POST['file_name'],
 		'part_num'=>$_POST['part_num'],
 		'vendor_code'=>$_POST['vendor_code'],
 		'rel_num'=>$_POST['rel_num'],
 		'rel_date'=>$_POST['rel_date'],
 		'rec_qty'=>$_POST['rec_qty'],
 		'rec_asn_id'=>$_POST['rec_asn_id'],
 		'cum_rec_qty'=>$_POST['cum_rec_qty'],
 		'past_due'=>$_POST['past_due'],
 		'date_format'=>$_POST['date_format'],
 		 	);
 	$result = $this->Mapping_model->update_mapping($mapping,$_POST['id']);
 	if($result)
        {
            $this->session->set_flashdata('message', 'success: Mapping changed.');
        
        } else {
            $this->session->set_flashdata('message', 'error: Something went wrong');
        }
        redirect('import_mapping');
 }
}
