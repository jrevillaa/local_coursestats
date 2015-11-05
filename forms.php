<?php

require_once($CFG->libdir.'/formslib.php');
require_once('type_model.php');

class links_form extends moodleform {

    function definition() {
    	global $DB;

        $model = new links_type_Model();
        $typesDB = $model->get_all();
        
        $options = array('0'=>'Selecciona...');
        foreach($typesDB as $c){
            $options[$c->id] = $c->name;
        }
        
        $mform =& $this->_form;
        $data = $this->_customdata;

        $mform->addElement('hidden','id',$data['id']);
        $mform->addElement('text','name',get_string('name','local_links'));
        $mform->addElement('select','typeid',get_string('type','local_links'), $options);
        $mform->addElement('text','link',get_string('links','local_links'));
        $mform->addElement('text','colorhead',get_string('colorhead','local_links'));
        $mform->addElement('text','colormenu',get_string('colormenu','local_links'));
        $mform->addElement('text','coloroptional',get_string('coloroptional','local_links'));
        $mform->addElement('filemanager', 'icon', get_string('icon','local_links'), null,array('subdirs' => 0,'maxfiles' => 1));
        $mform->addElement('filemanager', 'image', get_string('image','local_links'), null,array('subdirs' => 0,'maxfiles' => 1));
        $mform->addElement('text','description',get_string('description','local_links'));

        $mform->setType('name',PARAM_TEXT);
        $mform->setType('typeid',PARAM_TEXT);
        $mform->setType('link',PARAM_TEXT);
        $mform->setType('colorhead',PARAM_TEXT);
        $mform->setType('colormenu',PARAM_TEXT);
        $mform->setType('coloroptional',PARAM_TEXT);
        $mform->setType('icon',PARAM_TEXT);
        $mform->setType('image',PARAM_TEXT);
        $mform->setType('description',PARAM_TEXT);
        $mform->setType('id',PARAM_INT);

        $mform->addRule('name',get_string('required'),'required','','cliente',false,false);
        $mform->addRule('typeid',get_string('required'),'required','','cliente',false,false);
        $mform->addRule('link',get_string('required'),'required','','cliente',false,false);
        $mform->addRule('colorhead',get_string('required'),'required','','cliente',false,false);
        $mform->addRule('colormenu',get_string('required'),'required','','cliente',false,false);
        $mform->addRule('coloroptional',get_string('required'),'required','','cliente',false,false);
        $mform->addRule('icon',get_string('required'),'required','','cliente',false,false);
        $mform->addRule('image',get_string('required'),'required','','cliente',false,false);

        if(!empty($data['id'])){
            $data = $DB->get_record('links_entry',array('id'=>$data['id']), 'id, name, typeid, link, colorhead,colormenu,coloroptional, image, icon, description');

            $mform->setDefault('name',$data->name);
            $mform->setDefault('link',$data->link);
            $mform->setDefault('colorhead',$data->colorhead);
            $mform->setDefault('colormenu',$data->colormenu);
            $mform->setDefault('coloroptional',$data->coloroptional);
            $mform->setDefault('description',$data->description);
        }

        $this->add_action_buttons();

    }
}

