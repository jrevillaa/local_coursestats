<?php


Class links_type_Model{

	public function links_type_Model(){
		global $DB;
		$this->db = $DB;
	}

	public function get_all(){

		$sql = "SELECT * from {type_link}";

		$params = array();
        return $this->db->get_records_sql($sql,$params);
	}

}

