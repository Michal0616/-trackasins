<?php
class EmailSupports_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllEmailSupports(){
        $emailSupports = $this->db->query("SELECT * from email_supports ORDER  BY  price ASC ")->result();
        return $emailSupports;
    }
}