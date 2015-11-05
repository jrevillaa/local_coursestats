<?php


Class links_Model{

	public function links_Model(){
		global $DB;
		$this->db = $DB;
	}

	public function get_all(){

		$sql = "SELECT l.id, l.name,l.link,l.colorhead,l.colormenu,l.coloroptional,l.timecreated,l.icon,l.image, l.description, t.name as type
				FROM {links_entry} l, {type_link} t
                WHERE l.typeid = t.id
				ORDER BY l.id";

		$params = array();

		return $this->db->get_records_sql($sql,$params);
	}
    
    public function get_front($empresa){
    	/*global $USER;
        echo '<pre>';
        echo print_r($USER->profile['empresa']);
        echo '</pre>';*/
        
        $sql = "SELECT l.name,l.link,l.icon,l.image
				FROM {links_entry} l
                WHERE l.name = ?
				LIMIT 1";

		$params = array($empresa);

		$result = $this->db->get_records_sql($sql,$params);

		
		return $result;
        
    }

    public function get_colors($empresa){
    	/*global $USER;
        echo '<pre>';
        echo print_r($USER->profile['empresa']);
        echo '</pre>';*/
    	$sql = "SELECT l.colormenu,l.coloroptional,l.timecreated
				FROM {links_entry} l
                WHERE l.name = ?
				 LIMIT 1";


		$params = array($empresa);

		$result = $this->db->get_records_sql($sql,$params);

		
		return $result;
    }

	public function get_image($image){
		return $this->db->get_record('files',array('id'=>$image));	
	}	

	public function get_link($id){
		return $this->db->get_record('links_entry',array('id'=>$id));	
	}	

	public function get_news(){
		$params = array();
		return $this->db->get_records('links_entry',$params);
	}

}

