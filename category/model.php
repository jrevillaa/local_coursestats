<?php


Class category_Model{

	public function category_Model(){
		global $DB;
		$this->db = $DB;
	}

	public function get_all(){

		$sql = "SELECT *
				FROM {category_image}
				ORDER BY id";

		$params = array();

		return $this->db->get_records_sql($sql,$params);
	}
    
    public function get_front($limit){
        
        $sql = "SELECT *
				FROM {category_image}
				ORDER BY id limit " . $limit ;

		$params = array();

		return $this->db->get_records_sql($sql,$params);
        
    }

	public function get_image($image){
		return $this->db->get_record('files',array('id'=>$image));	
	}	

	public function get_imageid($id){
		return $this->db->get_field_sql('select image from {category_image} where categoryid = ?', array($id));	
	}
    
    public function get_category_name($id){
        $sql = "SELECT name
			FROM {course_categories} 
			WHERE id = ?";
        
        return $this->db->get_field_sql($sql,array($id));	
    }

}

