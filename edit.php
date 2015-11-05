<?php

include('../../config.php');
include('forms.php');
include('model.php');

$id = required_param('id',PARAM_INT);

$url = new moodle_url('/local/links/edit.php',array('id'=>$id));

$PAGE->set_url($url);

require_login();

$context = context_system::instance();

$PAGE->set_context($context);

require_capability('local/links:create',$context);

$name = get_string('addnew','local_links');

$model = new links_Model();

$mform = new links_form($url,array('id'=>$id));

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
	$record->typeid = $data->typeid;
	$record->link = $data->link;
	$record->colorhead = $data->colorhead;
	$record->colormenu = $data->colormenu;
	$record->coloroptional = $data->coloroptional;
	$record->description = $data->description;

	$record->timecreated = time();

	$record->image = 0;
    $record->icon = 0;

	$id = $DB->$type('links_entry',$record,true);

	if(empty($record->id)) $record->id = (int)$id;


	file_save_draft_area_files($data->image, $context->id, 'local_links', 'image',$record->id , array('subdirs' => 0, 'maxfiles' => 1));
	file_save_draft_area_files($data->icon, $context->id, 'local_links', 'icon',$record->id , array('subdirs' => 0, 'maxfiles' => 1));

	$sql = "SELECT id
			FROM {files} 
			WHERE component = ? AND itemid = ? AND filesize > 0 and filearea = ?";

	$params = array('local_links',$record->id);
    
	$record->image = $DB->get_field_sql($sql,array('local_links',$record->id, 'image'));
	$record->icon = $DB->get_field_sql($sql,array('local_links',$record->id, 'icon'));

	$DB->update_record('links_entry',$record);
	redirect(new moodle_url('/local/links/index.php'));
}

if(!empty($id)){
	$entry = new stdClass();

	$entry->id = $id;

	$draftitemimage = file_get_submitted_draft_itemid('image');
	file_prepare_draft_area($draftitemimage, 1, 'local_links', 'image', $entry->id);
    
    $draftitemicon = file_get_submitted_draft_itemid('icon');
	file_prepare_draft_area($draftitemicon, 1, 'local_links', 'icon', $entry->id);
	 
	$entry->image = $draftitemimage;
    $entry->icon = $draftitemicon;
	 
	$mform->set_data($entry);

}
    
echo $OUTPUT->header();

	$mform->display();

echo $OUTPUT->footer();	

