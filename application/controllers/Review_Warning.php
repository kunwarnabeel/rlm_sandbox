<?php
/**
 * Created by VisualStudioCode.
 * User: Syed Kazim Hussain
 * Date: 15/04/2022
 * Description : This controller is being used reviewing warning
 */


if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Review_Warning extends MY_Controller{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('csvimport');
        $this->load->model('Warning_model');
    }
    public function reviewWarning($rlm_key){
        $rlmData = $this->Warning_model->getRlmWarning($rlm_key);
        $plantDetailData = $this->Warning_model->getPlantDetails($rlmData[0]['customer_num'],$rlmData[0]['plant_num'],$rlmData[0]['part_num'],$rlmData[0]['sws_part_num']);
        $periods = $this->Warning_model->getPeriodWarning($rlmData[0]['rlmkey']);
        $periodArr = array_column($periods, 'value', 'period');
        $transitData= $this->Warning_model->getTransitData($rlmData[0]['sws_part_num'],$plantDetailData[0]['oracle_id'],$plantDetailData[0]['ship_to_location']);
        $this->view_data['rlmData'] = $rlmData;
        $this->view_data['periods'] = $periodArr;
        $this->view_data['transitData'] = $transitData;
        $this->view_data['customer_num'] = $rlmData[0]['customer_num'];
        $this->view_data['form_action'] = 'Review_Warning/reviewWarning/'.$rlm_key;
        if ($_POST) {
            $userNotes = trim($_POST['user_note']);
            $in_transit_num = trim($_POST['in_transit_num']);
            if($in_transit_num!=null && $in_transit_num!='' && $in_transit_num!=0){
                $warning_arr = array(
                    'in_transit_num' => $in_transit_num,
                );
                $this->Warning_model->update_warninglog_by_rlmKey($warning_arr,$rlm_key);
            }
            if($userNotes!=null && $userNotes!=''){
                $warning_arr = array(
                    'user_note' => $userNotes,
                    'status' => 'close',
                );
                $this->Warning_model->update_warninglog_by_rlmKey($warning_arr,$rlm_key);
            }
        }
        $warningLogArr = $this->Warning_model->getWarningLog($rlm_key);
        $this->view_data['in_transit_num'] = $warningLogArr[0]['in_transit_num'];
        $this->view_data['user_note'] = $warningLogArr[0]['user_note'];
        $this->view_data['status'] = $warningLogArr[0]['status'];
        $this->content_view = 'mymodules/reviewDetailedWarning';
    }
}