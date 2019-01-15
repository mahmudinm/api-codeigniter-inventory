<?php

defined('BASEPATH') or exit('No direct script access allowed');
use \Firebase\JWT\JWT;

class Auth extends BD_Controller
{

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model('Auth_model');
    }


    public function index_post()
    {
        $username = $this->post('username');
        $password = md5($this->post('password'));
        $kunci = $this->config->item('thekey');
        
        $invalidLogin = ['status' => 'invalid login'];

        $check_login = $this->Auth_model->check_login($username, $password);
        
        // echo $username . ' ' . $password;

        if (!$check_login) {
            $this->response($invalidLogin, 200);
        } else {
            // $token['id'] = $data[0]['id'];  //From here
            $token['username'] = $username;
            $date = new DateTime();
            $token['iat'] = $date->getTimestamp();
            $token['exp'] = $date->getTimestamp() + 60*60*5; //To here is to generate token
            $output['status'] = 'success'; //This is the output token
            $output['token'] = JWT::encode($token, $kunci); //This is the output token
            $output['username'] = $username; //This is the output token
            $output['id'] = $check_login['id']; //This is the output token
            $this->response($output, 200);
            // $this->response($output, 200);
        }

    }    
}
