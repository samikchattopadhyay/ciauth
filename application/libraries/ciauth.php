<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ciauth {

    private $CI;
    private $sess,
            $namespace,
            $tableName,
            $identityColumn,
            $credentialColumn,
            $credentialTreatment,
            $extraCredentials;

    /**
     * 
     * User Authentication class
     * @param string $name
     * @param array $options
     * 
     * 	tableName				This is the name of the database table that contains the authentication credentials
     * 	identityColumn			This is the name of the database table column used to represent the identity. 
     * 	credentialColumn		This is the name of the database table column used to represent the credential. 
     * 	credentialTreatment		MD5(?)
     *
     */
    public function __construct($options) {

        $this->CI = &get_instance();

        $this->namespace = isset($options['namespace']) ? $options['namespace'] : '';
        $this->tableName = isset($options['tableName']) ? $options['tableName'] : 'user';
        $this->identityColumn = isset($options['identityColumn']) ? $options['identityColumn'] : '';
        $this->credentialColumn = isset($options['credentialColumn']) ? $options['credentialColumn'] : '';
        $this->credentialTreatment = isset($options['credentialTreatment']) ? $options['credentialTreatment'] : '';
        $this->extraCredentials = isset($options['extraCredentials']) ? $options['extraCredentials'] : '';
    }

    public function setTableName($name) {
        $this->tableName = $name;
        return $this;
    }

    public function setIdentityColumn($name) {
        $this->identityColumn = $name;
        return $this;
    }

    public function setCredentialColumn($name) {
        $this->credentialColumn = $name;
        return $this;
    }

    public function setIdentity($name) {
        $this->identity = $name;
        return $this;
    }

    public function setCredential($name) {
        $this->credential = $name;
        return $this;
    }

    public function hasIdentity() {
        return $this->CI->session->userdata('storage' . $this->namespace);
    }

    public function getIdentity() {
        return $this->CI->session->userdata('storage' . $this->namespace);
    }

    public function clearIdentity() {
        $this->CI->session->unset_userdata('storage' . $this->namespace);
    }

    public function authenticate($identity, $credential) {
        $sql = "SELECT * FROM `{$this->tableName}` WHERE `{$this->identityColumn}`='{$identity}' AND ";
        if (!empty($this->credentialTreatment))
            $sql .= " `{$this->credentialColumn}`={$this->credentialTreatment}('{$credential}')";
        else
            $sql .= " `{$this->credentialColumn}`='{$credential}' ";

        if (!empty($this->extraCredentials))
            $sql .= " AND {$this->extraCredentials}";

        $res = $this->CI->db->query($sql)->result();

        if (sizeof($res)) {
            $userdata = array(
                'storage' . $this->namespace => $res[0]
            );
            $this->CI->session->set_userdata($userdata);
            return true;
        }

        return false;
    }

}