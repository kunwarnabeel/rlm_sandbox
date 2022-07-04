<?php
/**
 * User: Maaz Uddin 
 * Description : This controller is being used to validate and import Oracle datasheets
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sws_intransit extends MY_Controller
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
        set_time_limit(0);
        ini_set('memory_limit', '20000M');
        $tabledata ='';
        $transaction_level_data = [];
        $transaction_level_file = [];
        $transaction_level_errors = [];
        $openOrderArr = [];
        $import_date = date('Ymd',strtotime('Last Monday'));
        if(date('D') == "Mon") $import_date = date('Ymd');
        $file='SPD-Export.csv';
        if (file_exists(FCPATH . '../../../rlm_sheets/'.$file)) {
            
            $oracle_transaction_file = fopen(FCPATH . '../../../rlm_sheets/'.$file, "r");
            $skip = 0;
            while(! feof($oracle_transaction_file))
            {
                $transaction_level_file[] = fgetcsv($oracle_transaction_file);
            }
            fclose($oracle_transaction_file);
        }
        else
        {
            array_push($transaction_level_errors, 'File does not exists');
        }
        $tabledata.="<tr><td><h2>Import Release Result</h2></td></tr>";
        if(count($transaction_level_errors)>0)
        {
            $tabledata.="<tr><td><h3>Errors:</h3> <ol><li>File not found</li></ol></td></tr>";
        }else{
            // echo "<pre>";
            // print_r($transaction_level_file);
            // exit();
            $i=0;
            foreach($transaction_level_file as $key => $row){    
                // if($row[0]=='' || $row[6]=='' || $row[10]=='' || $row[11]==''){
                //     continue;
                // }

                if(strpos(trim($row[0]),'Aptiv')!== false || strpos(trim($row[0]),'YAZAKI')!== false || strpos(trim($row[0]),'LEAR')!== false){
                    $openOrderArr[$i]['customer_num'] = trim($row[1]);
                    $openOrderArr[$i]['customer_name'] = trim($row[0]);
                    $openOrderArr[$i]['ship_to_location'] = trim($row[2]);
                    $openOrderArr[$i]['part_number'] = trim($row[7]);
                    $openOrderArr[$i]['description'] = trim($row[8]);
                    $openOrderArr[$i]['schedule_ship_date'] = trim($row[12]);
                    $openOrderArr[$i]['actual_ship_date'] = trim($row[13]);
                    $openOrderArr[$i]['period'] = trim($row[14]);
                    $openOrderArr[$i]['qty_due'] = trim($row[15]);
                    $openOrderArr[$i]['qty_shipped'] = trim($row[16]);
                    $openOrderArr[$i]['qty_open'] = trim($row[17]);
                    $openOrderArr[$i]['import_date'] = $import_date;
                    $i++;
                }else{
                    continue;
                }
            }

            if(count($openOrderArr) > 0){
                $this->db->query('UPDATE transit_data SET status=0');
                $queryResult1 = $this->db->insert_batch('transit_data', $openOrderArr);
                $tabledata.="<tr><td><h3>Success:</h3> <p>".$temp." rows added successfully</p></td></tr>";
            }
            print_r($tabledata);
            exit();
        }
    }

}
?>