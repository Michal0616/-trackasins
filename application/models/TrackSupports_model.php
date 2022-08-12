<?php
class TrackSupports_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getAllTrackSupports(){
        $trackSupports = $this->db->query("SELECT * from track_supports ORDER BY  price ASC")->result();
        return $trackSupports;
    }

    public function getTrackItem($item){
        $trackSupports = $this->db->query("SELECT * from track_supports  WHERE id='".$item."'")->row();
        return $trackSupports;
    }
}