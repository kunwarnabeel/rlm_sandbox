<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Warnings extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
   //  if(!$this->user)
   //  {
   //   redirect('login');
   // }
   $this->load->model('Warning_model');
   $this->load->model('Rlm_model');
 }

 public function index(){
  $warning_list = $this->Warning_model->get_warnings();
  $this->view_data['warnings'] = $warning_list;     
  $this->view_data['form_action'] = 'warnings/update';     
    $this->content_view = 'mymodules/warnings';
 }
 public function warning_log(){
  $warning_list = $this->Warning_model->get_warning_logs();
  $this->view_data['warnings'] = $warning_list;     
  $this->view_data['form_action'] = 'warnings/update_log';     
    $this->content_view = 'mymodules/warning_log';
 }
 public function update(){
  $result = false;
  $mapping = array(
    'threshold'=>$_POST['threshold'],
    'updated_date'=> date('Ymd')
      );
  $result = $this->Warning_model->update_warnings($mapping,$_POST['id']);
  if($result)
        {
            $this->session->set_flashdata('message', 'success: Warnings updated successfully.');
        
        } else {
            $this->session->set_flashdata('message', 'error: Something went wrong');
        }
        redirect('warnings');
 }

 public function update_log(){
  // print_r($_POST);exit;
  $result = false;
  $mapping = array(
    'user_note'=>$_POST['user_notes'],
    'status'=> $_POST['status']
      );
  $result = $this->Warning_model->update_warninglog($mapping,$_POST['id']);
  if($result)
        {
            $this->session->set_flashdata('message', 'success: Warnings updated successfully.');
        
        } else {
            $this->session->set_flashdata('message', 'error: Something went wrong');
        }
        redirect('warnings/warning_log');
 }

 public function send_warning(){
    
 }
}