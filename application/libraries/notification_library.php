<?php
/**
 * Created by PhpStorm.
 * User: DanishQ
 * Date: 3/14/2019
 * Time: 1:35 PM
 */

class notification_library
{
    private $CI;
   function  __construct(){
       $this->CI =& get_instance();
       $this->CI->load->database();


}
    function Create_Notification($sender_id,$receiver_id,$rfq_id,$msg)
    {
        $notification_data = array(
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'rfq_id'=>$rfq_id,
            'status_message' => $msg,

        );
        $this->CI->db->insert("notification_log", $notification_data);
    }


    function Notification_ReadStatus_To1($id)
    {
        $data = array(
            'read_status' => '1',
        );
        $this->CI->db->where('id', $id);
        $this->CI->db->update('notification_log', $data);
    }


}