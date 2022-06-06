<?php 
/**
 * Created by PhpStorm.
 * User: Imported Library
 * Date: 1/29/2019
 * Description : This controller is being used for Manage Authentication 
 */


if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Auth extends MY_Controller

{

	function login()

	{

			$this->config->load('recaptcha');

			$this->view_data['error'] = "false";		

			$this->theme_view = 'login';


		
		

		if($_POST)

		{
			$_POST['username'] = $this->security->xss_clean($_POST['username']);
			$_POST['username'] = trim($_POST['username']);
			$user = User::validate_login($_POST['username'], $_POST['password']);
			if($user){

				if($this->input->cookie('fc2_link') != ""){
					redirect($this->input->cookie('fc2_link'));

				}else{

					redirect('');

				}

			}

			else {

				$this->view_data['error'] = "true";

				$this->view_data['username'] = $this->security->xss_clean($_POST['username']);

				$this->view_data['message'] = 'error:'.$this->lang->line('messages_login_incorrect');

			}

		}

		

	}

	

	function logout()

	{
		User::logout();

		redirect('login');

	}

	function language($lang = false){

		$folder = 'application/language/';

		$languagefiles = scandir($folder);

		if(in_array($lang, $languagefiles)){

		$cookie = array(

                   'name'   => 'fc2language',

                   'value'  => $lang,

                   'expire' => '31536000',

               );

 

		$this->input->set_cookie($cookie);

		}

		redirect(''); 

	}

	

}

