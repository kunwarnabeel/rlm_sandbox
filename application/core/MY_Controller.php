<?php
class My_Controller extends CI_Controller
{
    public $user = false;
    public $client = false;
    public $core_settings = false;
    // Theme functionality
    protected $theme_view = 'application';
    protected $content_view = '';
    protected $view_data = array();
    protected $sql_details_arr = array();
    
    public function __construct()
    {
        parent::__construct();
       
         if($_SERVER['SERVER_NAME'] == 'localhost') {
            $this->sql_details_arr = array
            (
                'user' => 'root',
                'pass' => '',
                'db' => 'arportal',
                'host' => '127.0.0.1'
            );
        }
        else{
            $this->sql_details_arr = array
            (
                'user' => 'rfq',
                'pass' => '!@#qweASD2019',
                'db' => 'rfq_testdb',
                'host' => '127.0.0.1'
            );
        }

        /* XSS Filtering */
        if (!empty($_POST)) {
            $fieldList = array("description","message", "terms", "note", "invoice_terms", "estimate_terms", "bank_transfer_text", "smtp_pass", "password", "ticket_config_pass", "css-area");
            $ignoreXSS = array("mail_body");
            function remove_bad_tags_from($field)
            {
                $_POST[$field] = preg_replace('/(&lt;|<)\?php(.*)(\?(&gt;|>))/imx', '[php] $2 [php]', $_POST[$field]);
                $_POST[$field] = preg_replace('/((&lt;|<)(\s*|\/)script(.*?)(&gt;|>))/imx', ' [script] ', $_POST[$field]);
                $_POST[$field] = preg_replace('/((&lt;|<)(\s*)link(.*?)\/?(&gt;|>))/imx', '[link $4 ]', $_POST[$field]);
                $_POST[$field] = preg_replace('/((&lt;|<)(\s*)(\/*)(\s*)style(.*?)(&gt;|>))/imx', ' [style] ', $_POST[$field]);
                $_POST[$field] = preg_replace('/((&lt;|<)(\s*)(\/*)(\s*)input(.*?)(&gt;|>))/imx', ' [input] ', $_POST[$field]);
                $_POST[$field] = preg_replace('/((\s*)(\/*)(\s*)javascript:(.*?))/imx', ' [javascript] ', $_POST[$field]);
                $_POST[$field] = preg_replace('/((\s*)(\/*)(\s*)(alert|confirm|console.log)(\s*?\()(.*?))/imx', ' [blocked] ', $_POST[$field]);
                $_POST[$field] = preg_replace('/((\s *)(\/*)(\s*)(onclick|onfocus|ondblclick|onmouseover|onmousemove|onmouseenter)(\s*?)(\=))/imx', ' [blocked] ', $_POST[$field]);
        
            }

            foreach ($_POST as $key => $value) {
                if (in_array($key, $fieldList)) {
                    remove_bad_tags_from($key);
                } elseif (!in_array($key, $ignoreXSS)) {
                    $_POST[$key] = $this->security->xss_clean($_POST[$key]);
                }
            }
        }

        
        $this->view_data['core_settings'] = Setting::first();

        //Timezone
        if ($this->view_data['core_settings']->timezone != "") {
            date_default_timezone_set($this->view_data['core_settings']->timezone);
        }else{
          //   date_default_timezone_set("Asia/Karachi"); 
        }
        
        $this->view_data['datetime'] = date('Y-m-d H:i', time());
        $date = date('Y-m-d', time());

        //Languages
        if ($this->input->cookie('fc2language') != "") {
            $language = $this->input->cookie('fc2language');
        } else {
            if (isset($this->view_data['language'])) {
                $language = $this->view_data['language'];
            } else {
                if (!empty($this->view_data['core_settings']->language)) {
                    $language = $this->view_data['core_settings']->language;
                } else {
                    $language = "english";
                }
            }
        }
        $this->view_data['time24hours'] = "true";
        switch ($language) {

              case "english": $this->view_data['langshort'] = "en"; $this->view_data['timeformat'] = "h:i K"; $this->view_data['dateformat'] = "F j, Y"; $this->view_data['time24hours'] = "false"; break;
              case "dutch": $this->view_data['langshort'] = "nl"; $this->view_data['timeformat'] = "H:i"; $this->view_data['dateformat'] = "d-m-Y"; break;
              case "french": $this->view_data['langshort'] = "fr"; $this->view_data['timeformat'] = "H:i"; $this->view_data['dateformat'] = "d-m-Y"; break;
              case "german": $this->view_data['langshort'] = "de"; $this->view_data['timeformat'] = "H:i"; $this->view_data['dateformat'] = "d.m.Y"; break;
              case "italian": $this->view_data['langshort'] = "it"; $this->view_data['timeformat'] = "H:i"; $this->view_data['dateformat'] = "d/m/Y"; break;
              case "norwegian": $this->view_data['langshort'] = "no"; $this->view_data['timeformat'] = "H:i"; $this->view_data['dateformat'] = "d.m.Y"; break;
              case "polish": $this->view_data['langshort'] = "pl"; $this->view_data['timeformat'] = "H:i"; $this->view_data['dateformat'] = "d.m.Y"; break;
              case "portuguese": $this->view_data['langshort'] = "pt"; $this->view_data['timeformat'] = "H:i"; $this->view_data['dateformat'] = "d/m/Y"; break;
              case "portuguese_pt": $this->view_data['langshort'] = "pt"; $this->view_data['timeformat'] = "H:i"; $this->view_data['dateformat'] = "d/m/Y"; break;
              case "russian": $this->view_data['langshort'] = "ru"; $this->view_data['timeformat'] = "H:i"; $this->view_data['dateformat'] = "d.m.Y"; break;
              case "spanish": $this->view_data['langshort'] = "es"; $this->view_data['timeformat'] = "H:i"; $this->view_data['dateformat'] = "d/m/Y"; break;
              default: $this->view_data['langshort'] = "en"; $this->view_data['timeformat'] = "h:i K"; $this->view_data['dateformat'] = "F j, Y"; $this->view_data['time24hours'] = "false"; break;

        }

        //fetch installed languages
        $installed_languages = array();
        if ($handle = opendir('application/language/')) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && $entry != ".DS_Store") {
                    array_push($installed_languages, $entry);
                }
            }
            closedir($handle);
        }

        $this->lang->load('application', $language);
        if (file_exists("./application/language/".$language."/custom_lang.php")) {
            $this->lang->load('custom', $language);
        }
        $this->lang->load('messages', $language);
        $this->lang->load('event', $language);
        $this->view_data['current_language'] = $language;
        $this->view_data['installed_languages'] = $installed_languages;


        //userdata
        $this->user = $this->session->userdata('user_id') ? User::find_by_id($this->session->userdata('user_id')) : false;
        $this->client = $this->session->userdata('client_id') ? Client::find_by_id($this->session->userdata('client_id')) : false;

        
        if ($this->user || $this->client) {

            //check if user or client
            if ($this->user) {
                $access            = explode(",", $this->user->access);
                $update            = $this->user;
                $email                = 'u'.$this->user->id;
                // $userIsSuperAdmin    = ($this->user->admin == '1') ? true : false;
                $comp_array            = false;
                $userIsSuperAdmin=false;
                
                    $this->view_data['tickets_access'] = true;
            } else {
                $this->theme_view = 'application_client';
                $access = $this->client->access;
                $access = explode(",", $access);
                $email = 'c'.$this->client->id;
            }
        }

        /*$this->load->database();
        $sql = "select * FROM templates WHERE type='notes'";
        $query = $this->db->query($sql); */
        $this->view_data["note_templates"] = "";//$query->result();

        /* save current url */
        $url = explode('/', $this->uri->uri_string());
        $no_link = array('login', 'register', 'logout', 'language', 'forgotpass', 'postmaster', 'cronjob', 'agent', 'api');
        if (!in_array($this->uri->uri_string(), $no_link) && empty($_POST) && (!isset($url[1]) || $url[1] == "view")) {
            $link = '/'.$this->uri->uri_string();
            $cookie = array(
                       'name'   => 'fc2_link',
                       'value'  => $link,
                       'expire' => '500',
                   );

            $this->input->set_cookie($cookie);
        }
    }
    
    public function _output($output)
    {
        // set the default content view
        if ($this->content_view !== false && empty($this->content_view)) {
            $this->content_view = $this->router->class . '/' . $this->router->method;
        }
                     
         
       

        //render the content view
        $yield = file_exists(APPPATH . 'views/' . $this->view_data['core_settings']->template . '/' . $this->content_view . EXT) ? $this->load->view($this->view_data['core_settings']->template . '/' . $this->content_view, $this->view_data, true) : false;

       
           
        if($this->theme_view!="modal" && $this->theme_view!="login"){
             $yield.= file_exists(APPPATH . 'views/' . $this->view_data['core_settings']->template . '/' . 'theme/loader' . EXT) ? $this->load->view($this->view_data['core_settings']->template . '/' . 'theme/loader', $this->view_data, true) : false;
        }

        //render the theme
        if ($this->theme_view) {


            echo $this->load->view($this->view_data['core_settings']->template . '/' .'theme/' . $this->theme_view, array('yield' => $yield), true);


        } else {
            echo $yield;
        }
        
        echo $output;
    }
}
