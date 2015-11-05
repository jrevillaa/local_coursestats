<?php

require_once($CFG->libdir.'/formslib.php');

class links_category_form extends moodleform {

    function definition() {
    	global $DB;

        $mform =& $this->_form;
        $data = $this->_customdata;
        
        $categories = $DB->get_records_sql('select * from {course_categories}');
        
        $options = array('0'=>'Selecciona...');
        foreach($categories as $c){
            $options[$c->id] = $c->name;
        }

        $mform->addElement('hidden','id',$data['id']);
        $mform->addElement('select','categoryid','Categoría', $options);
        $mform->addElement('filemanager', 'image', get_string('image','local_links'), null,array('subdirs' => 0,'maxfiles' => 1));

        $mform->setType('categoryid',PARAM_TEXT);
        $mform->setType('image',PARAM_TEXT);
        $mform->setType('id',PARAM_INT);

        $mform->addRule('categoryid',get_string('required'),'required','','cliente',false,false);
        $mform->addRule('image',get_string('required'),'required','','cliente',false,false);

        if(!empty($data['id'])){
            $data = $DB->get_record('category_image',array('id'=>$data['id']), 'id, categoryid, image');

            $mform->setDefault('categoryid',$data->categoryid);
        }

        $this->add_action_buttons();

    }
    
    function validation($data, $files){
        global $DB;
        $errors= array();
        
        if($data['id'] == 0){
            $result = $DB->get_records_list('category_image', 'categoryid', array($data['categoryid']));
        
            if(count($result) > 0){
                $errors['categoryid'] = 'La categoría ya tiene una imagen';
            }    
        }
        
        return $errors;
    }
}

