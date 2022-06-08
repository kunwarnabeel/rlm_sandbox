<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rlm_model extends CI_Model  {

    public function __construct()
    {
        parent::__construct();
        $this->table = 'rlm_data';
        $this->load->library('CSVReader');
        $this->load->library('csvimport');
        $this->load->model('Mapping_model');
    }

  public function import_yazaki_data($customer_num,$customer_name,$file,$import_date){
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        //print_r($file);exit;
        $isReleaseHeader = false;
        $islastAsnHeader = false;
        $isSectionHeader = false;
        $flag = true;
        $bulkinsert_arr = array();
        $headerSectionArr = [];
        $lastAsnSectionArr = [];
        $scheduleSectionArr = [];
        $transaction_level_data = [];
        $bulkSectionPeriodArr = [];
        
        $forecastArr = [];

        $tabledata ='';
        $transaction_level_errors = [];
        $fileFound= false;

        $partNum='';
        $plantNum= '';
        $yazakiReleaseDate = ''; 
        
        $index=0;
        
        
        $lines = file(FCPATH . '../../../rlm_sheets/'.$file );
        if(!empty($lines)){
            $fileFound = true;
            foreach ($lines as $lineNo => $line) {
                if($lineNo === 0) {
                    $yazakiReleaseDate =  explode(" ",str_getcsv($lines[$lineNo])[0])[1]; 
                    $yazakiReleaseDate = date('Ymd',strtotime($yazakiReleaseDate));
                    unset($lines[$lineNo]);
                }
                $csv = str_getcsv($line);
                if (count(array_filter($csv)) == 0) unset($lines[$lineNo]);
            }
            foreach($lines as $lineNo => $line){
                if(strpos($line,'SECTION') !== false){ // Read Sections
                    if(strpos($line,'RELEASE HEADER SECTION') !== false){
                        $sectionFlag = true;
                        $isReleaseHeader = true;
                        $isSectionHeader = false;
                    }
                    elseif(strpos($line,'SCHEDULE LINE SECTION') !== false){
                        $isReleaseHeader = false;
                        $isSectionHeader = true;
                    }
                    else{
                        $isReleaseHeader = false;
                        $isSectionHeader = false;
                    }
                }
                elseif($isReleaseHeader){
                    if($sectionFlag){
                        $sectionFlag = false;
                    }
                    else{
                        $temp = str_getcsv($line);
                        $plantNum = $temp[0].'_'.$temp[6];
                        $partNum =  $temp[10];
                        $sectionFlag = true;
                        // print_r($plantNum);exit;
                    }
                }
                elseif($isSectionHeader) {
                    $temp = str_getcsv($line);
                    $delimitedStr = $temp[1]."|".$temp[2]."|".$temp[5];
                    
                    $sec_rlm_key = $customer_num.'-'.$plantNum.'-'.$partNum.'-'.$import_date;

                    if($sectionFlag){
                        if($temp[1]!='Delivery Date' && $temp[2]!='Quantity')
                        {
                            array_push($transaction_level_errors, 'Column '.strtoupper($line).' is missing');
                        }
                        $sectionFlag = false;
                    }
                    else{
                        $scheduleSectionArr[$sec_rlm_key][] = $delimitedStr;
                    }
                }    
                elseif(sizeof($scheduleSectionArr)>0 && !$isSectionHeader && !$isReleaseHeader && count($transaction_level_errors)==0){
                    foreach($scheduleSectionArr as $row => $col){
                        $periodArr = [];
                        foreach($col as $str){
                            $period = date('Ymd',strtotime(explode("|",$str)[0]));
                            $quantity = explode("|",$str)[1];
                            $cum_value = explode("|",$str)[2];
                            if($period == $yazakiReleaseDate){
                                $forecastArr[$row] = $cum_value;
                            }
                            $periodArr = array(
                                'rlmkey' => $row,
                                'period' =>$period,
                                'value' => $quantity,
                            );
                            array_push($bulkSectionPeriodArr,$periodArr);
                            $this->delete_rlmdata($row,'periods');
                        }
                    }
                    $scheduleSectionArr= [];
                }
            }
            $checkIndex = 1;
            foreach($lines as $lineNo => $line){
                if(strpos($line,'SECTION') !== false){ // Read Sections
                    if(strpos($line,'RELEASE HEADER SECTION') !== false){
                        $isReleaseHeader = true;
                        $islastAsnHeader = false;
                    }
                    elseif(strpos($line,'LAST ASN SECTION')!== false){
                        $isReleaseHeader = false;
                        $islastAsnHeader = true;
                    }
                    else{
                        $isReleaseHeader = false;
                        $islastAsnHeader = false;
                    }
                }
                elseif($isReleaseHeader){ // read inside section data
                    $temp = str_getcsv($line);
                    
                    if($flag){
                        if($temp[1]!='Customer' && $temp[7]!='Yazaki Location' && $temp[0]!='Supplier' && $temp[5]!='SA Release No.' && $temp[8]!='Yazaki Material No.')
                        {
                            array_push($transaction_level_errors, 'Column '.strtoupper($line).' is missing');
                        }
                        $flag = false;
                    }
                    else{
                        /*$plantNum = $temp[0].'_'.$temp[6];
                        $rlm_key = $customer_num.'-'.$plantNum.'-'.$temp[10].'-'.$import_date;*/
                         $plant_part_error_count = 0;   
                        $plantNum = $temp[0].'_'.$temp[6];  
                        $part_num = $temp[10];
                        $rlm_key = $customer_num.'-'.$plantNum.'-'.$temp[10].'-'.$import_date;
                        $plant_part_exists = $this->check_plant_part($customer_num,$plantNum,$part_num);    
                        if(!$plant_part_exists){    
                            if($plant_part_error_count == 0)    
                                $tabledata.="<tr><td><h3>Errors:</h3> <ol>";    
                            $tabledata.="<li>Plant Number ".$plantNum." with Part Number ".$part_num." for Customer ".$customer_name." is missing from plant part setup</li>";  
                            $plant_part_error_count++;  
                        }
                        if($plant_part_error_count > 0){
                            if($checkIndex == (Count($lineNo))){
                                $tabledata.="</ol></td></tr>";
                                break;
                            }
                            $checkIndex++;
                            continue;
                        }   
                        $headerSectionArr = array
                        (
                        'customer_num' => $customer_num,
                        'customer_name'  => $customer_name,
                        'import_date'   =>  $import_date,
                        'plant_num'   =>   $plantNum,
                        'part_num'   =>   $temp[10],
                        'past_due' => 'N/A',
                        'rel_num' => $temp[5],
                        'rel_date' => $yazakiReleaseDate,
                        'vendor_code' => $temp[0],
                        'updated_date' => date('Ymd'),
                        'rlmkey' => $rlm_key,
                        'forecast_start'=> array_key_exists($rlm_key, $forecastArr) ? $forecastArr[$rlm_key] : 0,
                        'forecast'=> '0'
                    );
                        $flag = true;
                    }
                }
                elseif($islastAsnHeader){
                    $temp = str_getcsv($line);
                    if($flag){
                        if($temp[0]!='Last ASN No.' && $temp[1]!='Delivery Date' && $temp[2]!='Last ASN Qty' && $temp[4]!='Cml. Received Qty')
                        {
                            array_push($transaction_level_errors, 'Column '.strtoupper($line).' is missing');
                        }
                        $flag = false;
                    }
                    else{
                        $lastAsnSectionArr = array
                        (
                            'rec_qty'   =>   $temp[2],
                            'rec_asn_id' => $temp[0],
                            'cum_rec_qty' => $temp[4],
                            'last_rcv_date' => date('Ymd',strtotime($temp[1]))
                        );
                        $flag = true;
                    }
                }
                if(sizeof($lastAsnSectionArr)>0 && sizeof($headerSectionArr)>0 && count($transaction_level_errors)==0)
                {
                    array_push($transaction_level_data,array_merge($headerSectionArr,$lastAsnSectionArr));
                    $this->delete_rlmdata($rlm_key,'rlm_data');
                    $headerSectionArr = array();
                    $lastAsnSectionArr = array();
                }
                $checkIndex++;
            }
        }

        else{
            array_push($transaction_level_errors, 'File does not exists');
        }
        //******Transaction Level******
        $tabledata.="<tr><td><h2>Import Release Result</h2></td></tr>";
        
        if(count($transaction_level_errors)>0 && $fileFound)
        {
            $tabledata.="<tr><td><h3>Errors:</h3> <ol>";
            foreach($transaction_level_errors as $error){
                $tabledata.="<li>".$error."</li>";
            }
            $tabledata.="</ol></td></tr><tr><td></td></tr>";
        }
        elseif(count($transaction_level_errors)>0 && !$fileFound){
            $tabledata.="<tr><td><h3>Errors:</h3> <ol><li>File not found</li></ol></td></tr>";
        }
        else{
            $this->db->insert_batch('rlm_data', $transaction_level_data);   
            $this->db->insert_batch('periods', $bulkSectionPeriodArr);
            $tabledata.="<tr><td><h3>Success:</h3> <p>".count($transaction_level_data)." rows added successfully</p></td></tr>";
        }
        //print_r($transaction_level_data);exit;
        return $tabledata;
    }

    public function import_data($customer_num,$customer_name,$file,$import_date){
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        $period_arr = [];
        $transaction_level_data = [];
        $transaction_level_file = [];
        $transaction_level_errors = [];
        $transaction_headers = [];
        $transaction_level_header_position = [];
        $tabledata ='';
        $transaction_predefined_headers = $this->Mapping_model->get_mapping($customer_num);
        $transaction_predefined_headers = $transaction_predefined_headers[0];
        if (file_exists(FCPATH . '../../../rlm_sheets/'.$file)) {

            $oracle_transaction_file = fopen(FCPATH . '../../../rlm_sheets/'.$file, "r");
            $skip = 0;
            while(! feof($oracle_transaction_file))
            {
                    if($customer_name == 'Aptiv' && $skip<6){
                    fgetcsv($oracle_transaction_file);
                    // print_r( fgetcsv($oracle_transaction_file));
                    $skip++;
                }
                else{
                    if(empty($transaction_headers)){
                        $transaction_headers = fgetcsv($oracle_transaction_file);
                        // print_r($transaction_headers);exit;
                    }
                    else{
                        $transaction_level_file[] = fgetcsv($oracle_transaction_file);
                    }
                }
            }
            fclose($oracle_transaction_file);
        }
        else
        {
            array_push($transaction_level_errors, 'File does not exists');
        }
        //******Transaction Level******
        $tabledata.="<tr><td><h2>Import Release Result</h2></td></tr>";
        if(count($transaction_level_errors)>0)
        {
            $tabledata.="<tr><td><h3>Errors:</h3> <ol><li>File not found</li></ol></td></tr>";
        }
        else{
            // $transaction_headers = array_map('strtolower', $transaction_headers);
            $transaction_headers = array_map('trim', $transaction_headers);
            
            foreach($transaction_predefined_headers as $key => $row)
            {
                if(in_array($row,$transaction_headers))
                {
                    $transaction_level_header_position[$row] = array_search($row,$transaction_headers);
                }                
                elseif($key == 'date_format'){
                    //yaha masla he or ye masla dropdown wale kaam ke waja se aya he
                    $week = -1;
                    if($customer_name == 'Aptiv')
                        $week = -5;//-4
                    if(date('D',strtotime($import_date)) == 'Mon')
                        $week++;
                    $flag = 0;
                    //$lastMonday = date($row, strtotime($week.' monday'));
                    for($i=$week;$week<10;$i++){
                        $nextMonday = date($row, strtotime($import_date.' '.$i.' monday'));
                        // if($i==0)
                        //     continue;
                        if((!array_search($nextMonday,$transaction_headers) && $flag==0) || in_array($nextMonday, $period_arr))
                            continue;
                        if(!array_search($nextMonday,$transaction_headers))
                            break;
                        $transaction_level_header_position[$nextMonday] = array_search($nextMonday,$transaction_headers);
                        $flag = 1;
                        array_push($period_arr,$nextMonday);
                        
                    }
                }
                elseif($key!='customer_num'&&$key!='customer_name'&&$key!='date_format'&&$key!='id'&&$key!='updated_date'&&$key!='file_name' &&!empty($row))
                {
                    array_push($transaction_level_errors, 'Column '.strtoupper($row).' is missing');
                }
            }
            if(count($transaction_level_errors)>0)
            {
                $tabledata.="<tr><td><h3>Errors:</h3> <ol>";
                foreach($transaction_level_errors as $error)
                {
                    $tabledata.="<li>".$error."</li>";
                }
                $tabledata.="</ol></td></tr><tr><td></td></tr>";
            }
            else{
                $past_due = 'pastdue';
                if(!isset($transaction_level_header_position[$transaction_predefined_headers['past_due']]))
                    $past_due = 'N/A';
                $bulkinsert_arr = array();
                $bulkinsert_activity = array();
                $bulkinsert_periods = array();
                $activitykey = [];
                $plant_part_error_count = 0;
                foreach($transaction_level_file as $key => $row){                    
                    $part_num = trim($row[$transaction_level_header_position[$transaction_predefined_headers['part_num']]]);
                    $plant_num = trim($row[$transaction_level_header_position[$transaction_predefined_headers['plant_num']]]);
                    $rel_date = trim($row[$transaction_level_header_position[$transaction_predefined_headers['rel_date']]]);
                    $rel_num = trim($row[$transaction_level_header_position[$transaction_predefined_headers['rel_num']]]);
                    $tempkey = $customer_num.'-'.$plant_num.'-'.$part_num.'-'.$import_date;
                    $avgforecast = 0;
                    $bulkinsertPeriod_arr = array();
                    if(empty($plant_num) || empty($part_num)){
                        continue;
                    }
                    $plant_part_exists = $this->check_plant_part($customer_num,$plant_num,$part_num);
                    if(!$plant_part_exists){
                        if($plant_part_error_count == 0)
                            $tabledata.="<tr><td><h3>Errors:</h3> <ol>";
                        $tabledata.="<li>Plant Number ".$plant_num." with Part Number ".$part_num." for Customer ".$customer_name." is missing from plant part setup</li>";
                        $plant_part_error_count++;
                    }
                    if($plant_part_error_count > 0){
                        if(count($bulkinsert_activity)>0 && $plant_part_error_count==1){
                            $bulkinsert_activity = array();
                        }
                        if(!array_search(strval($tempkey), $activitykey)){
                            $sws_part_num = $this->getswspartnum($customer_num,$plant_num,$part_num);
                            $activity_log = array(
                                'activity_date'=>date('Ymd'),
                                'release_date'=>$rel_date,
                                'customer_name'=>$customer_name,
                                'release_num'=>$rel_num,
                                'plant_num'=>$plant_num,
                                'part_num'=>$part_num,
                                'sws_part_num'=>$sws_part_num,
                                'activity_status'=>'Rejected',
                                'message'=>'Import rejected due to missing plant/part number.'
                            );
                            array_push($activitykey,$tempkey);
                            array_push($bulkinsert_activity,$activity_log);
                        }                        
                        if($key == (Count($transaction_level_file)-2)){
                            $tabledata.="</ol></td></tr>";
                            break;
                        }
                        continue;
                    }
                    if(!empty($past_due))
                        $past_due = trim($row[$transaction_level_header_position[$transaction_predefined_headers['past_due']]]);
                    if($key == count($transaction_level_file)-1)
                    {
                        break;
                    }
                    $periodflag = 0;//this is used for aptiv only
                    $firstval = 0;//this is used for aptiv only
                    $forecast_start = $row[$transaction_level_header_position[$period_arr[0]]];
                    foreach($period_arr as $col){
                        $date = str_replace(".", "", $col);
                        $periodval = str_replace("*","",$row[$transaction_level_header_position[$col]]);
                        $perioddate = date('Ymd',strtotime($date));
                        //$nextmonday = date('Ymd',strtotime('-2 monday'));
                        
                        if($customer_name == 'Aptiv' && $periodflag == 0){
                            if($perioddate != $import_date){
                                $firstval+=$periodval;
                                continue;
                            }
                            else{
                               // $perioddate = $import_date;
                                $periodval += $firstval + trim($row[$transaction_level_header_position[$transaction_predefined_headers['cum_rec_qty']]]);
                                $forecast_start = $periodval;
                            }
                            $periodflag = 1;
                        }
                        $trasaction_level_period_data = array(
                            'rlmkey' => $tempkey,
                            'period' =>$perioddate,
                            'value' =>$periodval
                        );
                        if(!empty($row[$transaction_level_header_position[$col]]))
                            $avgforecast += $row[$transaction_level_header_position[$col]];

                        array_push($bulkinsertPeriod_arr,$trasaction_level_period_data);
                    }
					//  echo "<pre>";
					// print_r($bulkinsertPeriod_arr);
					// exit();

                    $transaction_level_data = array
                    (
                      'customer_num' => $customer_num,
                      'customer_name'  => $customer_name,
                      'import_date'   =>  $import_date,
                      'plant_num'   =>   $plant_num,
                      'part_num'   =>   $part_num,
                      'rec_qty'   =>   trim($row[$transaction_level_header_position[$transaction_predefined_headers['rec_qty']]]),
                      'rec_asn_id' => trim($row[$transaction_level_header_position[$transaction_predefined_headers['rec_asn_id']]]),
                      'cum_rec_qty' => trim($row[$transaction_level_header_position[$transaction_predefined_headers['cum_rec_qty']]]),
                      'last_rcv_date' => trim($row[$transaction_level_header_position[$transaction_predefined_headers['last_rcv_date']]]),
                      'past_due' => $past_due,
                      'rel_num' => $rel_num,
                      'rel_date' => $rel_date,
                      'vendor_code' => trim($row[$transaction_level_header_position[$transaction_predefined_headers['vendor_code']]]),
                      'updated_date' => date('Ymd'),
                      'rlmkey' => $tempkey,
                      'forecast_start'=> $forecast_start,
                      'forecast'=> $avgforecast/Count($period_arr)
                  );

                    if(!array_search(strval($tempkey), $activitykey)){
                        $sws_part_num = $this->getswspartnum($customer_num,$plant_num,$part_num);
                        $activity_log = array(
                            'activity_date'=>date('Ymd'),
                            'release_date'=>$rel_date,
                            'customer_name'=>$customer_name,
                            'release_num'=>$rel_num,
                            'plant_num'=>$plant_num,
                            'part_num'=>$part_num,
                            'sws_part_num'=>$sws_part_num,
                            'activity_status'=>'Success',
                            'message'=>'Data imported successfully'
                        );
                        array_push($activitykey,$tempkey);
                        array_push($bulkinsert_activity,$activity_log);
                    }
                    $this->delete_rlmdata($tempkey,'rlm_data');
                    $this->delete_rlmdata($tempkey,'periods');
                    array_push($bulkinsert_arr,$transaction_level_data);
                    array_push($bulkinsert_periods,$bulkinsertPeriod_arr);
                }
                if($plant_part_error_count == 0){
                    $queryResult1 = $this->db->insert_batch('rlm_data', $bulkinsert_arr);
                    foreach($bulkinsert_periods as $row){
                        $periodResult = $this->db->insert_batch('periods', $row);
                    }
                    $temp = count($bulkinsert_arr);
                    $tabledata.="<tr><td><h3>Success:</h3> <p>".$temp." rows added successfully</p></td></tr>";
                }
                $queryLog = $this->db->insert_batch('activity_log', $bulkinsert_activity);
                //update activity log

            }
        }
        return $tabledata;
    }

    public function getswspartnum($customer_num,$plant_num,$part_num){
        $this->db->select('sws_part_num');
        $this->db->where('customer_num',$customer_num);
        $this->db->where('plant_num',$plant_num);
        $this->db->where('part_num',$part_num);
        $this->db->from('plant_parts');
        $query = $this->db->get();
        $result = $query->result();
        if($result)
            return $result[0]->sws_part_num;
        else
            return '-';
    }

    public function getsearch_data($where_arr,$lead_time,$import_dates){
       $this->db->select('RLM.*,C.rlm_type');
       $this->db->from('rlm_data RLM');
       foreach($where_arr as $key => $row){
         $this->db->where($key,$row);
     }
     $this->db->where_in('RLM.import_date',$import_dates);
     $this->db->join('customers C','RLM.customer_num = C.oracle_acc_num','left');
     // $this->db->order_by('import_date','DESC');
     $query = $this->db->get();
     $rlm_data = $query->result();
     //print_r($rlm_data);exit;
     $date_format = '';
     if(count($rlm_data)>0){
         $customer_num = $rlm_data[0]->customer_num;
         $temp = array();
         $periods = [];
         foreach($rlm_data as $key=>$row){
           if (in_array($row->rlmkey, $temp))
            continue;
        array_push($temp,$row->rlmkey);
        $this->db->select('*');
        $this->db->from('periods');
        $this->db->where('rlmkey',$row->rlmkey);
        $this->db->where_in('period',$import_dates);
        // $this->db->order_by('id','ASC');
        // $this->db->where('period', $row->import_date);
        $query = $this->db->get();
        $result = $query->result();
        // print_r($result);exit;
        $tempperiod = [];
        foreach($result as $periodrow)
        {
            if(!empty($periodrow->value))
                $tempperiod[$periodrow->period] = $periodrow->value;
            else
                $tempperiod[$periodrow->period] = '0';
        }
        
        foreach($import_dates as $periodrow)
        {
            if(!isset($tempperiod[$periodrow])){
                $periods[$row->rlmkey][$periodrow] = '0';
            }
            else{
                $periods[$row->rlmkey][$periodrow] = $tempperiod[$periodrow];   
            }
        }
    }
    // print_r($periods);exit;

    $rlm_data[count($rlm_data)] = $periods;
}
return $rlm_data;
}

public function getdata_by_release_date($release_date){
    $this->db->select('*');
    $this->db->where('import_date',$release_date);
    $this->db->from('rlm_data');
    $query = $this->db->get();
    $rlm_keys = $query->result_array();
    return $rlm_keys;
}

public function get_period_sum_by_key($rlmkey,$date,$warning=0){
    $period_val = 0;
    if($warning == 0){
        $this->db->select('value');
        $this->db->where('rlmkey',$rlmkey);
        $this->db->where('period',$date);
        $this->db->from('periods');
        $query = $this->db->get();
        $result = $query->result_array();
        if(!empty($result) && $result[0]['value']!=0)
            $period_val = $result[0]['value'];
    }
    else{
        $period_arr=[$date];
        for($i=1;$i<=$warning;$i++)
        {
            array_push($period_arr,date('Ymd',strtotime($date.' '.$i.' monday')));
        }
        $testDate = date('Ymd',strtotime($date.' '.$warning.' monday'));
        $this->db->select('SUM(value) AS val');
        $this->db->where('rlmkey',$rlmkey);
        $this->db->where_in('period',$period_arr);
        $this->db->from('periods');
        $query = $this->db->get();
        $result = $query->result_array();
        if(!empty($result) && $result[0]['val']!=0)   
            $period_val = $result[0]['val'];
    }
    //echo $period_val;exit;
    if($period_val ==0)
        return 1;
    return $period_val;       
}
public function get_period_by_key($rlmkey,$date,$warning=0){
    $period_val = 0;
    if($warning == 0){
        $this->db->select('value');
        $this->db->where('rlmkey',$rlmkey);
        $this->db->where('period',$date);
        $this->db->from('periods');
        $query = $this->db->get();
        $result = $query->result_array();
        if(!empty($result) && $result[0]['value']!=0)
            $period_val = $result[0]['value'];
    }
    else{
        $testDate = date('Ymd',strtotime($date.' '.$warning.' monday'));
        $this->db->select('value');
        $this->db->where('rlmkey',$rlmkey);
        $this->db->where('period',$testDate);
        $this->db->from('periods');
        $query = $this->db->get();
        $result = $query->result_array();
        if(!empty($result) && $result[0]['value']!=0)   
            $period_val = $result[0]['value'];
    }
    if($period_val ==0)
        return 1;
    return $period_val;       
}
public function check_plant_part($customer_num,$plant_num,$part_num){
    $this->db->where('customer_num',$customer_num);
    $this->db->where('plant_num',$plant_num);
    $this->db->where('part_num',$part_num);
    $query = $this->db->get('plant_parts');
    if ($query->num_rows() > 0){
        return true;
    }
    else{
        return false;
    }
}
public function delete_rlmdata($key,$table){
    $this->db->where('rlmkey', $key);
    $res = $this->db->delete($table);
    if ($res){
        return true;
    }
    else{
        return false;
    }
}
}