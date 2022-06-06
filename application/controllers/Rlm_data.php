<?php
/**
 * User: Maaz Uddin 
 * Description : This controller is being used to validate and import Oracle datasheets
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Rlm_data extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('CSVReader');
        $this->load->library('csvimport');
        //$this->load->helper('customstring');
        $this->load->model('Rlm_model');
        $this->load->model('Warning_model');
        $this->load->model('Mapping_model');

    }

    public function index(){
        print_r('please add import_data in the url');exit;
    }
    public function import_data(){
        $importdate =  $_GET['date'];
        $tabledata ='';
        $customer_num = $_GET['customer_number'];
        if(empty($importdate) || $importdate == NULL || $importdate == 'undefined'){
            $importdate = date('Ymd',strtotime('Last Monday'));
            if(date('D') == "Mon") $importdate = date('Ymd');
        }
        $mapping = $this->Mapping_model->get_mapping($customer_num,'file_name,customer_name');
        $customer_name = $mapping[0]['customer_name'];
        $file = $mapping[0]['file_name'];
        if($customer_num == 'Y1234'){
            $file = 'Yazaki.csv';
            $tabledata = $this->Rlm_model->import_yazaki_data($customer_num,$customer_name,$file,$importdate);
        }
        else{
            $tabledata = $this->Rlm_model->import_data($customer_num,$customer_name,$file,$importdate);
        }
        print_r($tabledata);
        // $this->weekly_warning();
        // redirect('Warning/weektoweek_warning');
        exit;
    }
    public function weekly_warning(){
        $currentrelease_date = $_GET['date'];
        $weeklythreshold = $this->Warning_model->get_threshold(1);
        $fourweektrenthreshold = $this->Warning_model->get_threshold(2);
        $eightweektrendthreshold = $this->Warning_model->get_threshold(3);
        $backorderthreshold = $this->Warning_model->get_threshold(4);
        /*$currentrelease_date = date('Ymd');
        if(date('D') != 'Mon')
          $currentrelease_date = date('Ymd',strtotime('last Monday'));*/
      $last_release_date = date('Ymd',strtotime($currentrelease_date.' last Monday'));
      $current_rlm_keys = $this->Rlm_model->getdata_by_release_date($currentrelease_date);
      $warning_count = 0;
      if(!empty($current_rlm_keys)){
        foreach($current_rlm_keys as $row){
            $previous_key = explode("-",$row['rlmkey']);
          $temp = $previous_key[0].'-'.$previous_key[1].'-'.$previous_key[2].'-'.$last_release_date;
if($previous_key[0] == 'A1234'){
          $current_val = $this->Rlm_model->get_period_by_key($row['rlmkey'],$currentrelease_date);
          $current_fourweek_sum = $this->Rlm_model->get_period_sum_by_key($row['rlmkey'],$currentrelease_date,4);
          $current_eightweek_sum = $this->Rlm_model->get_period_sum_by_key($row['rlmkey'],$currentrelease_date,8);
          
            $forecasted_val = $this->Rlm_model->get_period_sum_by_key($temp,$last_release_date,2);   
            $forecasted_fourweek_sum = $this->Rlm_model->get_period_sum_by_key($temp,$last_release_date,5);   
            $forecasted_eightweek_sum = $this->Rlm_model->get_period_sum_by_key($temp,$last_release_date,9);  
          }
          else{
            $current_val = $this->Rlm_model->get_period_by_key($row['rlmkey'],$currentrelease_date);
            $current_fourweek_sum = $this->Rlm_model->get_period_by_key($row['rlmkey'],$currentrelease_date,4);
            $current_eightweek_sum = $this->Rlm_model->get_period_by_key($row['rlmkey'],$currentrelease_date,8);
            $forecasted_val = $this->Rlm_model->get_period_by_key($temp,$currentrelease_date);   
            $forecasted_fourweek_sum = $this->Rlm_model->get_period_by_key($temp,$currentrelease_date,4);   
            $forecasted_eightweek_sum = $this->Rlm_model->get_period_by_key($temp,$currentrelease_date,8); 
          }

          // print_r($forecasted_eightweek_sum);exit;
          $delta = 0;
          if($forecasted_val !=0 && $current_val!=0)
            $delta = $current_val/$forecasted_val;
        $delta = number_format($delta,2); 
        
        if($row['customer_num'] == 'L2222' && $current_val == $row['cum_rec_qty']){
            continue;        
        }
        // weekly warning            
        if($delta>=$weeklythreshold)
        {
            $warning_count++;
            $sws_part_num = $this->Rlm_model->getswspartnum($row['customer_num'],$row['plant_num'],$row['part_num']);
            $data = array(
                'log_date'=>date('Ymd'),
                'release_date'=>$row['rel_date'],
                'customer_name'=>$row['customer_name'],
                'release_num'=>$row['rel_num'],
                'customer_num'=>$row['customer_num'],
                'plant_num'=>$row['plant_num'],
                'part_num'=>$row['part_num'],
                'sws_part_num'=>$sws_part_num,
                'warning'=>'week to week warning',
                'delta'=>$delta,
                'user_note'=>'',
                'rlm_key'=>$row['rlmkey'],
            );
            $this->Warning_model->create_warning($data);
        }
        // four week warning
        $delta = 0;
        if($forecasted_fourweek_sum !=0 && $current_fourweek_sum!=0)
            $delta = $current_fourweek_sum/$forecasted_fourweek_sum;
        $delta = number_format($delta,2);            
        if($delta>=$fourweektrenthreshold)
        {
            $warning_count++;
            $sws_part_num = $this->Rlm_model->getswspartnum($row['customer_num'],$row['plant_num'],$row['part_num']);
            $data = array(
                'log_date'=>date('Ymd'),
                'release_date'=>$row['rel_date'],
                'customer_name'=>$row['customer_name'],
                'release_num'=>$row['rel_num'],
                'customer_num'=>$row['customer_num'],
                'plant_num'=>$row['plant_num'],
                'part_num'=>$row['part_num'],
                'sws_part_num'=>$sws_part_num,
                'warning'=>'4 week trend warning',
                'delta'=>$delta,
                'user_note'=>'',
                'rlm_key'=>$row['rlmkey'],
            );
            $this->Warning_model->create_warning($data);
        }
       // eight week warning
        $delta = 0;
        if($forecasted_eightweek_sum !=0 && $current_eightweek_sum!=0)
            $delta = $current_eightweek_sum/$forecasted_eightweek_sum;
        $delta = number_format($delta,2);            
        if($delta>=$eightweektrendthreshold)
        {
            $warning_count++;
            $sws_part_num = $this->Rlm_model->getswspartnum($row['customer_num'],$row['plant_num'],$row['part_num']);
            $data = array(
                'log_date'=>date('Ymd'),
                'release_date'=>$row['rel_date'],
                'customer_name'=>$row['customer_name'],
                'release_num'=>$row['rel_num'],
                'customer_num'=>$row['customer_num'],
                'plant_num'=>$row['plant_num'],
                'part_num'=>$row['part_num'],
                'sws_part_num'=>$sws_part_num,
                'warning'=>'8 week trend warning',
                'delta'=>$delta,
                'user_note'=>'',
                'rlm_key'=>$row['rlmkey'],
            );
            $this->Warning_model->create_warning($data);
        }
        
        //Backorder warning
        $backorder_trend = 0;
        $forcast_start = str_replace('*', '', $row['forecast_start']);
        $forcast_start = (empty($forcast_start) || $forcast_start == 0) ? 1 : $forcast_start;
        $cum_rec_qty = ($row['cum_rec_qty'] == 0 || empty($row['cum_rec_qty'])) ? 1 : $row['cum_rec_qty'];
        
        // if(!empty($forcast_start) && !empty($row['cum_rec_qty']) && $forcast_start !=0 &&$row['cum_rec_qty']!=0){
        $backorder_trend = number_format($forcast_start/$cum_rec_qty,2);
        if($backorder_trend > $backorderthreshold)
        {
            $warning_count++;
            $sws_part_num = $this->Rlm_model->getswspartnum($row['customer_num'],$row['plant_num'],$row['part_num']);
            $data = array(
                'log_date'=>date('Ymd'),
                'release_date'=>$row['rel_date'],
                'customer_name'=>$row['customer_name'],
                'release_num'=>$row['rel_num'],
                'customer_num'=>$row['customer_num'],
                'plant_num'=>$row['plant_num'],
                'part_num'=>$row['part_num'],
                'sws_part_num'=>$sws_part_num,
                'warning'=>'Back order warning',
                'delta'=>$backorder_trend,
                'user_note'=>'',
                'rlm_key'=>$row['rlmkey'],
            );
            $this->Warning_model->create_warning($data);       
        }
            // }
    }//end of foreach
 // echo $warning_count++.' warnings found';
}// end of if(!empty($current_rlm_keys)){
    $tabledata="";
    $tabledata.="<h3>Warnings:</h3>";
    if($warning_count){            
        $tabledata.="<li> ".$warning_count." Warnings found. Details are available in the warning log.</li>";                

    }else{
        $tabledata.="<li> No Warnings found on ".date("m-d-Y"). " </li>"; 
    }               
        $this->load->library('parser');
        $this->load->helper('file');
        $data['core_settings'] = Setting::first();
        $this->email->from($data['core_settings']->email, $data['core_settings']->company);
        $this->email->to('s.reeder@us.sws.co.jp');
        $this->email->cc('kazimmuhammad10@gmail.com');
        // $this->email->cc('maazuddinsheikh@gmail.com');
        $this->email->subject('SWS-USA Weekly Warning Alert');
        $emailLogo="https://extswsrfq.swsusainc.com:4433/assets/blueline/images/sws-logo-email.png";
        $parse_data = [
            'link' => base_url() . 'login/',
            'company' =>$data['core_settings']->company,
            'table_data' =>$tabledata,
            'logo' => '<img src="' . $emailLogo . '" alt="' . $data['core_settings']->company . '"/>',
            'invoice_logo' => '<img src="' . $emailLogo . '" alt="' . $data['core_settings']->company . '"/>'
        ];
        $email = read_file('./application/views/' . $data['core_settings']->template . '/templates/email_weekly_warning.html');
        $message = $this->parser->parse_string($email, $parse_data);
        $this->email->message($message);
        $r =   $this->email->send();
        print_r($message);
        // exit;
    


// else{
//   echo 'No warning found';
// }
exit;
}
}