<?php
/**
 * Created by PhpStorm.
 * User: Danish Qaimkhani
 * Date: 1/29/2019
 * Description : This controller is being used for Managing All Deleted RFQ will be show in Trash
 * from here admin can  delete it permantly
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Trash extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $access = false;
          if(!$this->user)
        {
           redirect('login');
        }
       
            foreach ($this->view_data['menu'] as $key => $value) 
            {
                if ($value->link == 'trash') {
                    $access = true;
                }
                
            }
            if (!$access) {
                redirect('dashboard');
            }
       
        
    }

    public function index()
    {
        
        $this->content_view = 'trash/all';
    }


    public function viewall(){

    $this->content_view = 'trash/all';

    }

    public function ShowTrashedRFQ($RFQ_id){

        //Leaving select method empty is equal to selecting all in default
        $this->db->select();
        $this->db->from('rfq');
        $this->db->where('id', $RFQ_id);
        $query = $this->db->get();
        $rfq_main = $query->result();
        $this->view_data['rfq_main'] = $rfq_main;
         
      
        // getting rfq owner name and number      
         $query=    $this->db
            ->query('SELECT u.id,u.personname  FROM users u
                    WHERE
                    u.id = "'.$rfq_main[0]->created_by.'" ');
             $foundRows = $query->result();
        $this->view_data['rfq_owner']=$foundRows;

        //Leaving select method empty is equal to selecting all in default
        $this->db->select();
        $this->db->from('rfq_items');
        $this->db->where('rfq_id', $RFQ_id);
        $query = $this->db->get();
        $rfq_items = $query->result();
        $this->view_data['rfq_items'] = $rfq_items;

          //This query will show the comment messages on the thread if there is any.
        $this->db->select('messages.id, messages.rfq_id, messages.sender_id, messages.receiver_id, messages.comment, messages.created_date, users.personname as sender, users.type as sendertype');
        $this->db->from('messages');
        $this->db->join('users',' messages.sender_id = users.id');
        $this->db->where('rfq_id',$RFQ_id);
        $this->db->order_by("messages.id", "asc");
        $query_comment = $this->db->get();
        $comments_list = $query_comment->result();
        $this->view_data['comments_list'] = $comments_list;

    $this->view_data['form_action'] = 'rfq/DeletePermanently/'.$RFQ_id  ;
    $this->view_data['form_action_restore'] = 'rfq/Restore/'.$RFQ_id  ;
    $this->content_view = 'rfq/Trashed_rfq';

    }


    public function getTrashed_RFQ_Archive(){

        require_once(APPPATH.'libraries/ssp.class2.php');
        $table = 'rfq';
        $conditionShowRelatedOnly="";
        if($this->user->type=="Administrator") {
            $conditionShowRelatedOnly="";
        }
        else if($this->user->type=="Sales Agent") {
            $conditionShowRelatedOnly="AND R.assign_ID=".$this->user->id;
        }




        if (isset($_GET['applyfilter']) && $_GET['applyfilter']==1)
        {
            $start_date =  $this->db->escape($_GET['start_date']." 00:00:00");
            $end_date =    $this->db->escape($_GET['end_date']." 23:59:59");

            $condition="AND R.created_date>=$start_date AND R.created_date<=$end_date  ";
            $table = <<<EOT
 (
    SELECT
      R.id,
      R.created_date,
      R.your_rfqn,
      R.your_SOP,
      R.reasons_text,
      ST.name AS StatusName,
      RE.Personname As Requester,
      ASI.Personname As AssignName,
      R.csv_generated,
      R.status_ID As status_ID,
      R.your_rfqn AS B,
      R.your_rfqn AS C,
      R.your_rfqn AS D
    FROM rfq R
    LEFT JOIN users RE ON RE.id = R.owner_ID
    LEFT JOIN rfq_status AS ST ON ST.id = R.status_ID
    LEFT  JOIN users AS ASI  ON ASI.id = R.assign_ID
    Where R.isActive ='1'
    $condition
    $conditionShowRelatedOnly
  
 ) temp
EOT;



        }else {


            $table = <<<EOT
 (
    SELECT
      R.id,
      R.created_date,
      R.your_rfqn,
      R.your_SOP,
      R.reasons_text,
      ST.name AS StatusName,
      RE.Personname As Requester,
      ASI.Personname As AssignName,
      R.csv_generated,
      R.status_ID As status_ID,
      R.your_rfqn AS B,
      R.your_rfqn AS C,
      R.your_rfqn AS D
    FROM rfq R
    LEFT JOIN users RE ON RE.id = R.owner_ID
    LEFT JOIN rfq_status AS ST ON ST.id = R.status_ID
    LEFT  JOIN users AS ASI  ON ASI.id = R.assign_ID
    Where R.isActive ='1'
    $conditionShowRelatedOnly
 ) temp
EOT;

        }



        // Table's primary key
        $primaryKey = 'id';
        $columns = array
        (
            array( 'db' => 'id', 'dt' => 0 ),
            array( 'db' => 'your_rfqn',     'dt' => 1 ),
            array( 'db' => 'reasons_text',   'dt' => 2 ),
            array(
                'db'        => 'created_date',
                'dt'        => 3,
                'formatter' => function( $d, $row ) {
                    $temp="<td>".mb_substr($d, 0, 10)." </td>";
                    return $temp;
                }
            ), // created date
            array( 'db' => 'your_SOP',     'dt' => 4 ),
            array( 'db' => 'Requester',   'dt' => 5 ),   // Requester

            array( 'db' => 'StatusName',     'dt' => 6 ),
            array( 'db' => 'AssignName',     'dt' => 7 ), // assign to
            array(
                'db'        => 'id',
                'dt'        => 8,
                'formatter' => function( $d, $row ) {
                    $temp=" <a href='".site_url()  ."trash/ShowTrashedRFQ/".$d."'   <span class=\"label label-info\"> View  </span> </a>";
                    if($this->user->type=="Administrator") {

                        $temp .= ' <a href="' . base_url() . 'rfq/DeletePermanently_show/' . $d . '" class="btn-option" data-toggle="mainmodal">
                         <i class="icon dripicons-cross" ></i>
                            </a>  ';
                    } else{

                    }
                    return $temp;
                }
            ), // Download CSV

        );


       // if($_SERVER['SERVER_NAME'] == 'localhost') {
       //      $sql_details = array
       //      (
       //          'user' => 'rfq',
       //          'pass' => '!@#qweASD2019',
       //          'db' => 'sumitomo',
       //          'host' => '127.0.0.1'
       //      );
       //  }
       //  else{
       //      $sql_details = array
       //      (
       //          'user' => 'rfq',
       //          'pass' => '!@#qweASD2019',
       //          'db' => 'sumitomo',
       //          'host' => '127.0.0.1'
       //      );
       //  }
        echo $a= json_encode(
            SSP::simple( $_GET, $this->sql_details_arr, $table, $primaryKey, $columns ));

        exit();

    }

  

    public function delete($id = false)
    {
         redirect('rfq/viewall');
    }

   

   
}
