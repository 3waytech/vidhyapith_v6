<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class logbook_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }
    
    // get subjects assign list
    public function getlogbookList($id)
    {
        $this->db->select('*');
        $this->db->from('logbook');
        $this->db->where('id', $id);
        $result = $this->db->get()->result_array();
        return $result;
    }
    // get subjects assign list
    public function getlogbookListall()
    {
        $this->db->select('*');
        $this->db->from('logbook');
        $result = $this->db->get()->result_array();
        return $result;
    }  

}
