<?php

include('../../../config.php');
include('form.php');
//include('model.php');

$id = required_param('id',PARAM_INT);

$url = new moodle_url('/local/links/category/edit.php',array('id'=>$id));

$PAGE->set_url($url);

require_login();

$context = context_system::instance();

$PAGE->set_context($context);

require_capability('local/links:create',$context);

$name = 'Category Images';

//$model = new links_Model();

$mform = new links_category_form($url,array('id'=>$id));

$PAGE->set_context($context);

$PAGE->navbar->add($name);

$PAGE->set_title($name);

$PAGE->set_heading($name);

if($mform->is_cancelled()){
	redirect(new moodle_url('/local/links/category/index.php'));
}elseif($data = $mform->get_data()){

	$record = new stdClass();

	if(!empty($data->id)){
		$record->id = $data->id;
		$type = 'update_record';
	}else{
		$record->id = null;
		$type = 'insert_record';
	}
    
	$record->categoryid = $data->categoryid;
	$record->timecreated = time();
	$record->image = 0;

	$id = $DB->$type('category_image',$record,true);

	if(empty($record->id)) $record->id = (int)$id;


	file_save_draft_area_files($data->image, $context->id, 'local_links', 'category_image',$record->id , array('subdirs' => 0, 'maxfiles' => 1));

	$sql = "SELECT id
			FROM {files} 
			WHERE component = ? AND itemid = ? AND filesize > 0 and filearea = ?";

    
	$record->image = $DB->get_field_sql($sql,array('local_links',$record->id, 'category_image'));

	$DB->update_record('category_image',$record);
	redirect(new moodle_url('/local/links/category/index.php'));
}

if(!empty($id)){
	$entry = new stdClass();

	$entry->id = $id;

	$draftitemimage = file_get_submitted_draft_itemid('image');
	file_prepare_draft_area($draftitemimage, 1, 'category_image', 'image', $entry->id);
	 
	$entry->image = $draftitemimage;
	 
	$mform->set_data($entry);

}
    
echo $OUTPUT->header();

	$mform->display();

echo $OUTPUT->footer();	

