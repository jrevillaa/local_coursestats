<?php

include('../../config.php');
include('model.php');

$url = new moodle_url('/local/links/index.php');

$PAGE->set_url($url);

require_login();

$context = context_system::instance();//get_context_instance(CONTEXT_SYSTEM);

has_capability('local/links:create',$context);

$name = get_string('pluginname','local_links');

$model = new Links_Model();

$links = $model->get_all();

$PAGE->set_context($context);

$PAGE->navbar->add($name);

$PAGE->set_title($name);

$PAGE->set_heading($name);


echo $OUTPUT->header();

$newlink = new moodle_url('/local/links/edit.php',array('id'=>0));
$newtype = new moodle_url('/local/links/type_edit.php',array('id'=>0));
//$newenterprise = new moodle_url('/local/links/edit.php',array('id'=>0));

echo html_writer::start_tag('div', array('id'=>'add-new'));
	echo html_writer::tag('a',get_string('addnew','local_links'),array('href'=>$newlink));
	echo html_writer::tag('a','Nuevo Tipo',array('href'=>$newtype));
echo html_writer::end_tag('div');

echo html_writer::start_tag('table',array('align'=>'center'));
	echo html_writer::start_tag('thead');
		echo html_writer::start_tag('tr');
            echo html_writer::tag('td',get_string('name','local_links'));
            echo html_writer::tag('td',get_string('type','local_links'));
            echo html_writer::tag('td',get_string('colorhead','local_links'));
            echo html_writer::tag('td',get_string('colormenu','local_links'));
            echo html_writer::tag('td',get_string('coloroptional','local_links'));
			echo html_writer::tag('td',get_string('date','local_links'));
            echo html_writer::tag('td',get_string('icon','local_links'));
			echo html_writer::tag('td',get_string('image','local_links'));
			echo html_writer::tag('td',get_string('options','local_links'));
		echo html_writer::end_tag('tr');
	echo html_writer::end_tag('thead');

		/*global $USER;
        echo '<pre>';
        echo print_r($USER->profile['empresa']);
        echo '</pre>';*/

	foreach($links as $n){	

		$n->date = date('d-m-Y',$n->timecreated);
		$n->image = $model->get_image($n->image);
        $uri = $CFG->wwwroot.'/pluginfile.php/'.$n->image->contextid.'/'.$n->image->component.'/'.$n->image->filearea.'/'.$n->image->itemid.'/'.$n->image->filename;
        $img = html_writer::empty_tag('img',array('src'=>$uri,'style'=>'max-width: 100px!important;'));
        
        if($n->icon != '0'){
            $n->icon = $model->get_image($n->icon);
            $uriico = $CFG->wwwroot.'/pluginfile.php/'.$n->icon->contextid.'/'.$n->icon->component.'/'.$n->icon->filearea.'/'.$n->icon->itemid.'/'.$n->icon->filename;
            $ico = html_writer::empty_tag('img',array('src'=>$uriico,'style'=>'max-width: 100px!important;'));
        }
        
        
        
		$edituri = new moodle_url('/local/links/edit.php',array('id'=>$n->id));
		$deleteuri = new moodle_url('/local/links/delete.php',array('id'=>$n->id));
		echo html_writer::start_tag('tr');
            echo html_writer::start_tag('td');
			echo html_writer::tag('a',$n->name, array('href'=>$n->link, 'taget'=>'_blank'));
            echo html_writer::end_tag('td');
        
            echo html_writer::tag('td',$n->type);
        
            echo html_writer::start_tag('td');
            echo html_writer::tag('span', $n->colorhead, array('style'=>'padding: .5rem; background:' . $n->colorhead . '; display:block; text-align:center; color:#FFF'));
            echo html_writer::end_tag('td');
            
            echo html_writer::start_tag('td');
            echo html_writer::tag('span', $n->colormenu, array('style'=>'padding: .5rem; background:' . $n->colormenu . '; display:block; text-align:center; color:#FFF'));
            echo html_writer::end_tag('td');
            
            echo html_writer::start_tag('td');
            echo html_writer::tag('span', $n->coloroptional, array('style'=>'padding: .5rem; background:' . $n->coloroptional . '; display:block; text-align:center; color:#FFF'));
            echo html_writer::end_tag('td');
            
        
			echo html_writer::tag('td',$n->date);
            echo html_writer::tag('td', ($n->icon != '0')? $ico : '');
			echo html_writer::tag('td',$img);	
			echo html_writer::start_tag('td');
				echo html_writer::tag('a',get_string('edit'),array('href'=>$edituri));
                echo html_writer::empty_tag('br');
				echo html_writer::tag('a',get_string('delete'),array('href'=>$deleteuri));
			echo html_writer::end_tag('td');
		echo html_writer::end_tag('tr');
	}

	if(count($links) == 0){
		echo html_writer::start_tag('tr');
			echo html_writer::tag('td',get_string('norecords','local_links'),array('colspan'=>6));
		echo html_writer::end_tag('tr');		

	}

echo html_writer::end_tag('table');

echo $OUTPUT->footer();	