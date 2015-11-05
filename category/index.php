<?php

require_once('../../../config.php');
require_once('model.php');

//setear url del archivo
$url = new moodle_url('/local/links/category/index.php');
$PAGE->set_url($url);

//el usuario debe estar logueado
require_login();
//obtener contexto
$context = context_system::instance();
has_capability('local/links:create',$context);
$PAGE->set_context($context);

//titulo
$name = 'Category Images';
$PAGE->set_title($name);
$PAGE->navbar->add($name);
$PAGE->set_heading($name);

$model = new category_Model();
$categories = $model->get_all();

$html = '';
//imprimir header
$html .= $OUTPUT->header();


//opciones
$new = new moodle_url('/local/links/category/edit.php',array('id'=>0));
$html .= html_writer::tag('a', 'Nuevo', array('href'=>$new));

//listado
$html.= html_writer::start_tag('table',array('align'=>'center'));
	$html.= html_writer::start_tag('thead');
		$html.= html_writer::start_tag('tr');
            $html.= html_writer::tag('td', 'ID');
			$html.= html_writer::tag('td', 'Categoría');
            $html.= html_writer::tag('td', 'Imagen');
			$html.= html_writer::tag('td', get_string('date','local_links'));
			$html.= html_writer::tag('td', get_string('options','local_links'));
		$html.= html_writer::end_tag('tr');
	$html.= html_writer::end_tag('thead');

	foreach($categories as $c){	

		$c->date = date('d-m-Y',$c->timecreated);
		$c->image = $model->get_image($c->image);
        
        $categoryname = $model->get_category_name($c->categoryid);
            
		$uri = $CFG->wwwroot.'/pluginfile.php/'.$c->image->contextid.'/'.$c->image->component.'/'.$c->image->filearea.'/'.$c->image->itemid.'/'.$c->image->filename;
        
		$img = html_writer::empty_tag('img',array('src'=>$uri,'style'=>'max-width: 100px!important;'));
        
		$edituri = new moodle_url('/local/links/category/edit.php',array('id'=>$c->id));
		$deleteuri = new moodle_url('/local/link/category/delete.php',array('id'=>$c->id));
		$html.= html_writer::start_tag('tr');
            $html.= html_writer::tag('td',$c->id);
            $html.= html_writer::tag('td',$categoryname);
            $html.= html_writer::tag('td',$img);
			$html.= html_writer::tag('td',$c->date);
				
			$html.= html_writer::start_tag('td');
				$html.= html_writer::tag('a',get_string('edit'),array('href'=>$edituri));
				$html.= html_writer::tag('a',get_string('delete'),array('href'=>$deleteuri));
			$html.= html_writer::end_tag('td');
		$html.= html_writer::end_tag('tr');
	}

	if(count($categories) == 0){
		$html.= html_writer::start_tag('tr');
			$html.= html_writer::tag('td', 'No existen categorías vinculadas',array('colspan'=>5));
		$html.= html_writer::end_tag('tr');		

	}

$html.= html_writer::end_tag('table');


//imprimir footer
$html .= $OUTPUT->footer();

echo $html;
