<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Index extends CI_Controller {

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

        $data['loggedin'] = $this->ciauth->hasIdentity();
        
        if ( !$data['loggedin'] )
            redirect('login');

        $this->load->view('index', $data);
    }

    public function logout() {
        $this->ciauth->clearIdentity();
        redirect('login');
    }

}