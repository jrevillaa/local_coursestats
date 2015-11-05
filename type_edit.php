<?php

include('../../config.php');
include('type_form.php');
include('type_model.php');

$id = required_param('id',PARAM_INT);

$url = new moodle_url('/local/links/type_edit.php',array('id'=>$id));

$PAGE->set_url($url);

require_login();

$context = context_system::instance();

$PAGE->set_context($context);

require_capability('local/links:create',$context);

$name = 'Agregar Tipo de Link';

//$model = new links_Model();

$mform = new type_link_form($url,array('id'=>$id));

$PAGE->set_context($context);

$PAGE->navbar->add($name);

$PAGE->set_title($name);

$PAGE->set_heading($name);

if($mform->is_cancelled()){
	redirect(new moodle_url('/local/links/index.php'));
}elseif($data = $mform->get_data()){

	$record = new stdClass();

	if(!empty($data->id)){
		$record->id = $data->id;
		$type = 'update_record';
	}else{
		$record->id = null;
		$type = 'insert_record';
	}
    
	$record->name = $data->name;

	$record->timecreated = time();

	$id = $DB->$type('type_link',$record,true);
	redirect(new moodle_url('/local/links/index.php'));
}

if(!empty($id)){
	$entry = new stdClass();
    $entry->id = $id;
	$mform->set_data($entry);

}
    
echo $OUTPUT->header();

	$mform->display();

echo $OUTPUT->footer();	

