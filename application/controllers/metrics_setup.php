<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Metrics_setup extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();


        if(!$this->user)
        {
           redirect('login');
       }
   }

   public function index()
   {
    $this->db->select('*');
    $this->db->from('metrics_setup');
    $query = $this->db->get();
    $result = $query->result();
    $this->view_data['metrics'] = $result[0];
    $this->view_data['form_action'] = 'metrics_setup/update_data';
    $this->content_view = 'mymodules/metrics_setup';
}

public function update_data(){
    $metricsRes = false;
    if ($_POST) {
        $lower_good = ($_POST['lower_good'])/100;
        $upper_good = ($_POST['upper_good'])/100;
        $lower_concern = ($_POST['lower_concern'])/100;
        $upper_concern = ($_POST['upper_concern'])/100;
        $lower_bad = ($_POST['lower_concern'] -1)/100;
        $upper_bad = ($_POST['upper_concern'] +1)/100;
        
        $metrics_data = array(
                'id' => '1',
                'lower_good' => $lower_good,
                'upper_good' => $upper_good,
                'lower_concern' => $lower_concern,
                'upper_concern' => $upper_concern,
                'lower_bad' => $lower_bad,
                'upper_bad' => $upper_bad,
                'color_good' => $_POST['color_good'],
                'color_concern' => $_POST['color_concern'],
                'color_bad' => $_POST['color_bad'],
                'desc_good' => $_POST['desc_good'],
                'desc_concern' => $_POST['desc_concern'],
                'desc_bad' => $_POST['desc_bad']

        );
        $metricsRes = $this->db->update('metrics_setup', $metrics_data);
    }
if($metricsRes)
{
 $this->session->set_flashdata('message', 'success: Metrics updated');
}
else{
    $this->session->set_flashdata('message', 'error: Something went wrong');
}
redirect('metrics_setup');
}
}