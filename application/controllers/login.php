<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('ciauth', array(
            'namespace' => 'frontend',
            'tableName' => 'user',
            'identityColumn' => 'EMAIL',
            'credentialColumn' => 'PASSWORD',
            'credentialTreatment' => 'MD5',
            'extraCredentials' => 'STATUS = 1'
        ));
    }

    public function index() {
        
        $data = array();

        if ($this->ciauth->hasIdentity())
            redirect('index');


        if ($this->input->post('EMAIL')) {
            $email = $this->input->post('EMAIL');
            $password = $this->input->post('PASSWORD');

            if ($this->ciauth->authenticate($email, $password))
                redirect('index');
            else
                $data['loginerror'] = 'Sorry! Login failed';
        }

        $this->load->view('login', $data);
    }

}