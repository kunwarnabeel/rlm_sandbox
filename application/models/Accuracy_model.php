<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Accuracy_model extends CI_Model  {

    public function __construct()
    {
        parent::__construct();
    }

    public function getbacket_trend($item_number,$startmonth,$endmonth,$table,$action){
    	try{
    	$this->db->select('*');
    	$this->db->from($table);
    	$this->db->Order_by('id','desc');
    	$this->db->where('item_number',$item_number);
    	$this->db->limit(1);
    	$query = $this->db->get();
    	$result = $query->result_array();
    	if(count($result) == 0)
    		return 0;
    	$bucketSum = 0;
    	$monthscount = 0;
    	if($action == 'sum'){
            for($i=$startmonth;$i<=$endmonth;$i++){
            $bucketSum += $result[0]['month'.$i];
        }
    		return $bucketSum;
        }
        for($i=$startmonth+1;$i<=$endmonth;$i++){
            $bucketSum += $result[0]['month'.$i];
            $monthscount++;
        }
    	if($action == 'trend' && ($bucketSum!=0 && $result[0]['month'.$startmonth]!=0))
    	{
    		return ($bucketSum/$monthscount)/$result[0]['month'.$startmonth];
    	}
    	else
    	{
    		return 0;
    	}
    }
    catch(Exception $e){
    	return 0;
    }
    }

    public function sales_orders_trend($item_number,$startmonth,$endmonth,$table,$action){
    	try{
    	$months = array();
    	$startmonth = $startmonth-19; 
    	$endmonth = $endmonth-19; 
    	for($i=$startmonth;$i<=$endmonth;$i++){
    		array_push($months,date('M Y',strtotime($i.' months')));
    	}
    	$this->db->select('value,'.$action.'(value) AS avgVal');
    	$this->db->from($table);
    	$this->db->where('item_number',$item_number);
    	$this->db->where_in('period',$months);
    	$query = $this->db->get();
    	$result = $query->result_array();
    	if($action == 'sum'){
    		return $result[0]['avgVal'];
    	}
    	else if($result[0]['avgVal'] !=0 || $result[0]['value'] != 0){
    		return $result[0]['avgVal']/$result[0]['avgVal'];
    	}
    	return 0;
    }
    catch(Exception $e){
    	return 0;
    }
    }
}
