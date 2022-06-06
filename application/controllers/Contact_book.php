<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Contact_book extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
   //  if(!$this->user)
   //  {
   //   redirect('login');
   // }
   $this->load->model('Warning_model');
 }

 public function index(){
  $warning_list = $this->Warning_model->get_warning_contacts();
  $this->view_data['warning_contacts'] = $warning_list;     
  $this->view_data['form_action'] = 'contact_book/update';     
    $this->content_view = 'mymodules/contact_book';
 }
 public function update(){
  $result = false;
  if(empty($_POST['email'])){
    $this->session->set_flashdata('message', 'error: Email field can not be empty');
    redirect('contact_book');
  }
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $this->session->set_flashdata('message', 'error: Invalid email format');
    redirect('contact_book');
  }
  $emails = $_POST['emails'].','.$_POST['email'];
  $mapping = array(
    'emails'=> $emails,
    'updated_date'=> date('Ymd')
      );
  $result = $this->Warning_model->update_warning_contact($mapping,$_POST['id']);
  if($result)
        {
            $this->session->set_flashdata('message', 'success: Contact updated successfully.');
        
        } else {
            $this->session->set_flashdata('message', 'error: Something went wrong');
        }
        redirect('contact_book');
 }
}
