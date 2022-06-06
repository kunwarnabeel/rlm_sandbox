<?php
/**
 * User: Maaz Uddin 
 * Description : This controller is being used to validate and import Oracle datasheets
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Open_order extends MY_Controller
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
        $file='OOD-Export.csv';
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
            //echo "<pre>";
            $i=0;
            // print_r($transaction_level_file);
            // exit();
            foreach($transaction_level_file as $key => $row){    
                if($row[0]=='' || $row[6]=='' || $row[10]=='' || $row[11]==''){
                    continue;
                }

                if(strpos(trim($row[1]),'Aptiv')!== false || strpos(trim($row[1]),'YAZAKI')!== false || strpos(trim($row[1]),'LEAR')!== false){
                    $openOrderArr[$i]['customer_num'] = trim($row[0]);
                    $openOrderArr[$i]['customer_name'] = trim($row[1]);
                    $openOrderArr[$i]['order_num'] = trim($row[2]);
                    $openOrderArr[$i]['order_date'] = trim($row[3]);
                    $openOrderArr[$i]['requested_date'] = trim($row[4]);
                    $openOrderArr[$i]['customer_po'] = trim($row[5]);
                    $openOrderArr[$i]['ship_to_location'] = trim($row[6]);
                    $openOrderArr[$i]['total_order_open'] = trim($row[7]);
                    $openOrderArr[$i]['line'] = trim($row[8]);
                    $openOrderArr[$i]['item'] = trim($row[9]);
                    $openOrderArr[$i]['schd_ship_date'] = date('Ymd',strtotime(trim($row[10])));
                    $openOrderArr[$i]['open_qty'] = trim($row[11]);
                    $openOrderArr[$i]['price'] = trim($row[12]);
                    $openOrderArr[$i]['open_amount'] = trim($row[13]);
                    $openOrderArr[$i]['import_date'] = $import_date;
                    $i++;
                }else{
                    continue;
                }
            }

            if(count($openOrderArr) > 0){
                $queryResult1 = $this->db->insert_batch('open_orders', $openOrderArr);
                $tabledata.="<tr><td><h3>Success:</h3> <p>".$temp." rows added successfully</p></td></tr>";
            }
            print_r($tabledata);
            // echo "<pre>";
            // print_r($openOrderArr);
            exit();
        }
    }

}
?>