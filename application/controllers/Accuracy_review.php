<?php

if (!defined('BASEPATH')) {
  exit('No direct script access allowed');
}

class Accuracy_review extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    if(!$this->user)
    {
     redirect('login');
   }
   $this->load->model('Accuracy_model');
 }

 public function index_trend()
 {
  $this->db->select('item_number,item_desc,cat1,cat2,cat3,cat4,cat5,cat6,cat7,cat8,cat9,cat10');
  $this->db->from('items');
  $this->db->where('item_number !=','');
  $query = $this->db->get();
  $items = $query->result();

  $this->view_data['items'] = $items;      
  $this->content_view = 'mymodules/index_trend';
}


public function trends($item){

  $current_date = date('M Y');
  $sf_6M1 = $this->Accuracy_model->getbacket_trend($item,1,6,'sf_data','trend');
  $sf_6M2 = $this->Accuracy_model->getbacket_trend($item,7,12,'sf_data','trend');
  $sf_6M3 = $this->Accuracy_model->getbacket_trend($item,13,18,'sf_data','trend');

  $sf_6M4_1 = $this->Accuracy_model->getbacket_trend($item,7,12,'sf_data','sum');
  $sf_6M4_2 = $this->Accuracy_model->getbacket_trend($item,1,6,'sf_data','sum');
  if($sf_6M4_1 !=0 && $sf_6M4_2!=0){
    $sf_6M4 = $sf_6M4_1/$sf_6M4_2;
  }
  else{
   $sf_6M4 = 0; 
 }

 $sf_6M5_1 = $this->Accuracy_model->getbacket_trend($item,13,18,'sf_data','sum');
 if($sf_6M5_1 !=0 && $sf_6M4_1!=0){
  $sf_6M5 = $sf_6M5_1/$sf_6M4_1;
}
else{
 $sf_6M5 = 0; 
}

$sf_12M = $this->Accuracy_model->getbacket_trend($item,7,18,'sf_data','trend');
$sf_18M = $this->Accuracy_model->getbacket_trend($item,1,18,'sf_data','trend');
$sf_data = array(
  'sf_6M1'=>number_format($sf_6M1,2),
  'sf_6M2'=>number_format($sf_6M2,2),
  'sf_6M3'=>number_format($sf_6M3,2),
  'sf_6M4'=>number_format($sf_6M4,2),
  'sf_6M5'=>number_format($sf_6M5,2),
  'sf_12M'=>number_format($sf_12M,2),
  'sf_18M'=>number_format($sf_18M,2),
);

$orders_6M1 = $this->Accuracy_model->sales_orders_trend($item,1,6,'order_data','AVG');
$orders_6M2 = $this->Accuracy_model->sales_orders_trend($item,7,12,'order_data','AVG');
$orders_6M3 = $this->Accuracy_model->sales_orders_trend($item,13,18,'order_data','AVG');

$orders_6M4_1 = $this->Accuracy_model->sales_orders_trend($item,7,12,'order_data','sum');
$orders_6M4_2 = $this->Accuracy_model->sales_orders_trend($item,1,6,'order_data','sum');
if($orders_6M4_1 !=0 && $orders_6M4_2!=0){
  $orders_6M4 = $orders_6M4_1/$orders_6M4_2;
}
else{
 $orders_6M4 = 0; 
}

$orders_6M5_1 = $this->Accuracy_model->sales_orders_trend($item,13,18,'order_data','sum');
if($orders_6M5_1 !=0 && $orders_6M4_1!=0){
  $orders_6M5 = $orders_6M5_1/$orders_6M4_1;
}
else{
 $orders_6M5 = 0; 
}    

$orders_12M = $this->Accuracy_model->sales_orders_trend($item,7,18,'order_data','AVG');
$orders_18M = $this->Accuracy_model->sales_orders_trend($item,1,18,'order_data','AVG');

$orders_data = array(
  'order_6M1'=>number_format($orders_6M1,2),
  'order_6M2'=>number_format($orders_6M2,2),
  'order_6M3'=>number_format($orders_6M3,2),
  'order_6M4'=>number_format($orders_6M4,2),
  'order_6M5'=>number_format($orders_6M5,2),
  'order_12M'=>number_format($orders_12M,2),
  'order_18M'=>number_format($orders_18M,2),
);

$sales_6M1 = $this->Accuracy_model->sales_orders_trend($item,1,6,'sales_data','AVG');
$sales_6M2 = $this->Accuracy_model->sales_orders_trend($item,7,12,'sales_data','AVG');
$sales_6M3 = $this->Accuracy_model->sales_orders_trend($item,13,18,'sales_data','AVG');

$sales_6M4_1 = $this->Accuracy_model->sales_orders_trend($item,7,12,'sales_data','sum');
$sales_6M4_2 = $this->Accuracy_model->sales_orders_trend($item,1,6,'sales_data','sum');
if($sales_6M4_1 !=0 && $sales_6M4_2!=0){
  $sales_6M4 = $sales_6M4_1/$sales_6M4_2;
}
else{
 $sales_6M4 = 0; 
}

$sales_6M5_1 = $this->Accuracy_model->sales_orders_trend($item,13,18,'sales_data','sum');
if($sales_6M5_1 !=0 && $sales_6M4_1!=0){
  $sales_6M5 = $sales_6M5_1/$sales_6M4_1;
}
else{
 $sales_6M5 = 0; 
}    

$sales_12M = $this->Accuracy_model->sales_orders_trend($item,7,18,'sales_data','AVG');
$sales_18M = $this->Accuracy_model->sales_orders_trend($item,1,18,'sales_data','AVG');

$sales_data = array(
  'sales_6M1'=>number_format($sales_6M1,2),
  'sales_6M2'=>number_format($sales_6M2,2),
  'sales_6M3'=>number_format($sales_6M3,2),
  'sales_6M4'=>number_format($sales_6M4,2),
  'sales_6M5'=>number_format($sales_6M5,2),
  'sales_12M'=>number_format($sales_12M,2),
  'sales_18M'=>number_format($sales_18M,2),
);


$this->db->from('sf_data');
$sf_data_date = $this->db->get()->row()->data_date;
$this->db->from('order_data');
$orders_data_date = $this->db->get()->row()->data_date;
$this->db->from('sales_data');
$sales_data_date = $this->db->get()->row()->data_date;

$this->theme_view = 'modal';
$this->view_data['title'] = 'Index Trend';
$this->view_data['sf_last_uploaded'] = $sf_data_date;
$this->view_data['order_last_uploaded'] = $orders_data_date;
$this->view_data['sales_last_uploaded'] = $sales_data_date;

$this->view_data['sf_data'] = $sf_data;
$this->view_data['sales_data'] = $sales_data;
$this->view_data['orders_data'] = $orders_data;
$this->content_view = 'settings/_trend_view';

}

public function index_forward()
{
  $this->view_data['form_action'] = 'Accuracy_review/update_data';
  $this->content_view = 'mymodules/index_forward';
}

public function index_backward()
{
  $this->db->select('item_number,item_desc,cat1,cat2,cat3,cat4,cat5,cat6,cat7,cat8,cat9,cat10');
  $this->db->from('items');
  $this->db->where('item_number !=','');
  $query = $this->db->get();
  $items = $query->result();

  $this->view_data['items'] = $items;
  $this->content_view = 'mymodules/index_backward';
}

public function bkwd_accuracy($item){

  $this->db->from('sf_data');
  $sf_data_date = $this->db->get()->row()->data_date;
  $this->db->from('order_data');
  $orders_data_date = $this->db->get()->row()->data_date;
  $this->db->from('sales_data');
  $sales_data_date = $this->db->get()->row()->data_date;

  $this->db->from('metrics_setup');
  $metrics_data = $this->db->get()->row();
  $current_date = date('M Y');
  $a =  date_create($current_date);
  $b = date_create($sf_data_date);
  $diff=date_diff($a,$b);
  $months_diff = ceil($diff->format("%m"));
  $endmonth = 18+$months_diff;

  $sf_3M = $this->Accuracy_model->getbacket_trend($item,($endmonth-2),$endmonth,'sf_data','sum');
  $sf_6M = $this->Accuracy_model->getbacket_trend($item,($endmonth-5),$endmonth,'sf_data','sum');
  $sf_9M = $this->Accuracy_model->getbacket_trend($item,($endmonth-8),$endmonth,'sf_data','sum');
  $sf_12M = $this->Accuracy_model->getbacket_trend($item,($endmonth-11),$endmonth,'sf_data','sum');
  $sf_15M = $this->Accuracy_model->getbacket_trend($item,($endmonth-14),$endmonth,'sf_data','sum');
  $sf_18M = $this->Accuracy_model->getbacket_trend($item,($endmonth-17),$endmonth,'sf_data','sum');
  $sales_3M = 0;
  $sales_6M = 0;
  $sales_9M = 0;
  $sales_12M = 0;
  $sales_15M = 0;
  $sales_18M = 0;
  $orders_3M = 0;
  $orders_6M = 0;
  $orders_9M = 0;
  $orders_12M = 0;
  $orders_15M = 0;
  $orders_18M = 0;



  if($sf_3M != 0){
    $sales_3M = $this->Accuracy_model->sales_orders_trend($item,16,18,'sales_data','sum');
    // echo $sales_3M;exit;
    $orders_3M = $this->Accuracy_model->sales_orders_trend($item,16,18,'order_data','sum');
    if($sales_3M !=0)
      $sales_3M = $sales_3M/$sf_3M;  
    if($orders_3M !=0)
      $orders_3M = $orders_3M/$sf_3M;  

  }
  if($sf_6M !=0){
    $sales_6M = $this->Accuracy_model->sales_orders_trend($item,13,18,'sales_data','sum');
    $orders_6M = $this->Accuracy_model->sales_orders_trend($item,13,18,'order_data','sum');
    if($sales_6M !=0)
      $sales_6M = $sales_6M/$sf_6M;     
    if($orders_6M !=0)
      $orders_6M = $orders_6M/$sf_6M;     

  }
  if($sf_9M !=0){
    $sales_9M = $this->Accuracy_model->sales_orders_trend($item,10,18,'sales_data','sum');
    $orders_9M = $this->Accuracy_model->sales_orders_trend($item,10,18,'order_data','sum');
    if($sales_9M !=0)
      $sales_9M = $sales_9M/$sf_9M;        
    if($orders_9M !=0)
      $orders_9M = $orders_9M/$sf_9M;        

  }
  if($sf_12M !=0){
    $sales_12M = $this->Accuracy_model->sales_orders_trend($item,7,18,'sales_data','sum');
    $orders_12M = $this->Accuracy_model->sales_orders_trend($item,7,18,'order_data','sum');
    if($sales_12M !=0)
      $sales_12M = $sales_12M/$sf_12M;
    if($orders_12M !=0)
      $orders_12M = $orders_12M/$sf_12M;

  }
  if($sf_15M !=0){
    $sales_15M = $this->Accuracy_model->sales_orders_trend($item,4,18,'sales_data','sum');
    $orders_15M = $this->Accuracy_model->sales_orders_trend($item,4,18,'order_data','sum');
    if($sales_15M !=0)
      $sales_15M = $sales_15M/$sf_15M; 
    if($sales_15M !=0)
      $orders_15M = $orders_15M/$sf_15M;
  }
  if($sf_18M !=0){
    $sales_18M = $this->Accuracy_model->sales_orders_trend($item,1,18,'sales_data','sum');
    $orders_18M = $this->Accuracy_model->sales_orders_trend($item,1,18,'order_data','sum');
    if($sales_18M !=0 )
      $sales_18M = $sales_18M/$sf_18M;
    if($orders_18M !=0 )
      $orders_18M = $orders_18M/$sf_18M;

  }

  $sales_accuracy = array(
    '3M'=> number_format($sales_3M,2),
    '6M'=> number_format($sales_6M,2),
    '9M'=> number_format($sales_9M,2),
    '12M'=> number_format($sales_12M,2),
    '15M'=> number_format($sales_15M,2),
    '18M'=> number_format($sales_18M,2),
  );
  $orders_accuracy = array(
    '3M'=> number_format($orders_3M,2),
    '6M'=> number_format($orders_6M,2),
    '9M'=> number_format($orders_9M,2),
    '12M'=> number_format($orders_12M,2),
    '15M'=> number_format($orders_15M,2),
    '18M'=> number_format($orders_18M,2),
  );
  if($orders_accuracy['3M'] < $metrics_data->lower_concern || $orders_accuracy['3M'] > $metrics_data->upper_concern)
    $orders_3Mcolor = $metrics_data->color_bad;
  elseif ($orders_accuracy['3M'] < $metrics_data->lower_good || $orders_accuracy['3M'] > $metrics_data->upper_good)
    $orders_3Mcolor = $metrics_data->color_concern;
  else
    $orders_3Mcolor = $metrics_data->color_good;

  if($orders_accuracy['6M'] < $metrics_data->lower_concern || $orders_accuracy['6M'] > $metrics_data->upper_concern)
    $orders_6Mcolor = $metrics_data->color_bad;
  elseif ($orders_accuracy['6M'] < $metrics_data->lower_good || $orders_accuracy['6M'] > $metrics_data->upper_good)
    $orders_6Mcolor = $metrics_data->color_concern;
  else
    $orders_6Mcolor = $metrics_data->color_good;

  if($orders_accuracy['9M'] < $metrics_data->lower_concern || $orders_accuracy['9M'] > $metrics_data->upper_concern)
    $orders_9Mcolor = $metrics_data->color_bad;
  elseif ($orders_accuracy['9M'] < $metrics_data->lower_good || $orders_accuracy['9M'] > $metrics_data->upper_good)
    $orders_9Mcolor = $metrics_data->color_concern;
  else
    $orders_9Mcolor = $metrics_data->color_good;

  if($orders_accuracy['12M'] < $metrics_data->lower_concern || $orders_accuracy['12M'] > $metrics_data->upper_concern)
    $orders_12Mcolor = $metrics_data->color_bad;
  elseif ($orders_accuracy['12M'] < $metrics_data->lower_good || $orders_accuracy['12M'] > $metrics_data->upper_good)
    $orders_12Mcolor = $metrics_data->color_concern;
  else
    $orders_12Mcolor = $metrics_data->color_good;

  if($orders_accuracy['15M'] < $metrics_data->lower_concern || $orders_accuracy['15M'] > $metrics_data->upper_concern)
    $orders_15Mcolor = $metrics_data->color_bad;
  elseif ($orders_accuracy['15M'] < $metrics_data->lower_good || $orders_accuracy['15M'] > $metrics_data->upper_good)
    $orders_15Mcolor = $metrics_data->color_concern;
  else
    $orders_15Mcolor = $metrics_data->color_good;

  if($orders_accuracy['18M'] < $metrics_data->lower_concern || $orders_accuracy['18M'] > $metrics_data->upper_concern)
    $orders_18Mcolor = $metrics_data->color_bad;
  elseif ($orders_accuracy['18M'] < $metrics_data->lower_good || $orders_accuracy['18M'] > $metrics_data->upper_good)
    $orders_18Mcolor = $metrics_data->color_concern;
  else
    $orders_18Mcolor = $metrics_data->color_good;


  if($sales_accuracy['3M'] < $metrics_data->lower_concern || $sales_accuracy['3M'] > $metrics_data->upper_concern)
    $sales_3Mcolor = $metrics_data->color_bad;
  elseif ($sales_accuracy['3M'] < $metrics_data->lower_good || $sales_accuracy['3M'] > $metrics_data->upper_good)
    $sales_3Mcolor = $metrics_data->color_concern;
  else
    $sales_3Mcolor = $metrics_data->color_good;

  if($sales_accuracy['6M'] < $metrics_data->lower_concern || $sales_accuracy['6M'] > $metrics_data->upper_concern)
    $sales_6Mcolor = $metrics_data->color_bad;
  elseif ($sales_accuracy['6M'] < $metrics_data->lower_good || $sales_accuracy['6M'] > $metrics_data->upper_good)
    $sales_6Mcolor = $metrics_data->color_concern;
  else
    $sales_6Mcolor = $metrics_data->color_good;

  if($sales_accuracy['9M'] < $metrics_data->lower_concern || $sales_accuracy['9M'] > $metrics_data->upper_concern)
    $sales_9Mcolor = $metrics_data->color_bad;
  elseif ($sales_accuracy['9M'] < $metrics_data->lower_good || $sales_accuracy['9M'] > $metrics_data->upper_good)
    $sales_9Mcolor = $metrics_data->color_concern;
  else
    $sales_9Mcolor = $metrics_data->color_good;

  if($sales_accuracy['12M'] < $metrics_data->lower_concern || $sales_accuracy['12M'] > $metrics_data->upper_concern)
    $sales_12Mcolor = $metrics_data->color_bad;
  elseif ($sales_accuracy['12M'] < $metrics_data->lower_good || $sales_accuracy['12M'] > $metrics_data->upper_good)
    $sales_12Mcolor = $metrics_data->color_concern;
  else
    $sales_12Mcolor = $metrics_data->color_good;

  if($sales_accuracy['15M'] < $metrics_data->lower_concern || $sales_accuracy['15M'] > $metrics_data->upper_concern)
    $sales_15Mcolor = $metrics_data->color_bad;
  elseif ($sales_accuracy['15M'] < $metrics_data->lower_good || $sales_accuracy['15M'] > $metrics_data->upper_good)
    $sales_15Mcolor = $metrics_data->color_concern;
  else
    $sales_15Mcolor = $metrics_data->color_good;

  if($sales_accuracy['18M'] < $metrics_data->lower_concern || $sales_accuracy['18M'] > $metrics_data->upper_concern)
    $sales_18Mcolor = $metrics_data->color_bad;
  elseif ($sales_accuracy['18M'] < $metrics_data->lower_good || $sales_accuracy['18M'] > $metrics_data->upper_good)
    $sales_18Mcolor = $metrics_data->color_concern;
  else
    $sales_18Mcolor = $metrics_data->color_good;

  $orders_highlighter = array(
    '3Mcolor'=>$orders_3Mcolor,
    '6Mcolor'=>$orders_6Mcolor,
    '9Mcolor'=>$orders_9Mcolor,
    '12Mcolor'=>$orders_12Mcolor,
    '15Mcolor'=>$orders_15Mcolor,
    '18Mcolor'=>$orders_18Mcolor,
  );
  $sales_highlighter = array(
    '3Mcolor'=>$sales_3Mcolor,
    '6Mcolor'=>$sales_6Mcolor,
    '9Mcolor'=>$sales_9Mcolor,
    '12Mcolor'=>$sales_12Mcolor,
    '15Mcolor'=>$sales_15Mcolor,
    '18Mcolor'=>$sales_18Mcolor,
  );

  $sales_accuracy=array_merge($sales_accuracy, $sales_highlighter);
  $orders_accuracy=array_merge($orders_accuracy, $orders_highlighter);

  $this->theme_view = 'modal';
  $this->view_data['title'] = 'Accuracy';
  $this->view_data['sf_last_uploaded'] = $sf_data_date;
  $this->view_data['order_last_uploaded'] = $orders_data_date;
  $this->view_data['sales_last_uploaded'] = $sales_data_date;
  $this->view_data['metrics'] = $metrics_data;

  $this->view_data['sales_data'] = $sales_accuracy;
  $this->view_data['orders_data'] = $orders_accuracy;
  $this->content_view = 'settings/_accuracy_view';
}
}