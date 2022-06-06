<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
          
        // if(!$this->user)
        // {
        //    redirect('login');
        // }
        $access = false;
        $this->view_data['update'] = false;
    }

    public function index($year = false)
    {
        $this->content_view = 'dashboard/dashboard';
    }
}
