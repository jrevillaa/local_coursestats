<?php

require_once($CFG->libdir.'/formslib.php');


class type_link_form extends moodleform {

    function definition() {
    	global $DB;

        $mform =& $this->_form;
        $data = $this->_customdata;

        $mform->addElement('hidden','id',$data['id']);
        
        $mform->addElement('text','name',get_string('name','local_links'));

        $mform->setType('name',PARAM_TEXT);
        $mform->setType('id',PARAM_INT);

        $mform->addRule('name',get_string('required'),'required','','cliente',false,false);

        if(!empty($data['id'])){
            $data = $DB->get_record('type_link',array('id'=>$data['id']), 'id, name');

            $mform->setDefault('name',$data->name);
        }

        $this->add_action_buttons();

    }
    
//    function validation($data, $files){
//        $errors= array();
//        if($data['typeid'] <= 0){
//            $errors['typeid'] = 'Debes seleccionar un tipo';
//        }
//        
//        return $errors;
//    }
}

