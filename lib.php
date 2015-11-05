<?php

function local_links_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    // Check the contextlevel is as expected - if your plugin is a block, this becomes CONTEXT_BLOCK, etc.

    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false; 
    }

    if ($filearea !== 'image' && $filearea !== 'icon' && $filearea !== 'category_image'){
        return false;
    }

    $itemid = array_shift($args);
 
    $filename = array_pop($args); // The last item in the $args array.
    if (!$args) {
        $filepath = '/'; // $args is empty => the path is '/'
    } else {
        $filepath = '/'.implode('/', $args).'/'; // $args contains elements of the filepath
    }
 
    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'local_links', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; 
        
    }
    send_stored_file($file, 86400, 0, $forcedownload, $options);
}


function print_links($total = 10,$title = '',$class=''){
    global $CFG;
   require('model.php');


    $model = new links_Model();
    $links = $model->get_links($total);

    $class = 'main-links '.$class;

    echo html_writer::start_tag('div',array('class'=> $class));

    if(!empty($title)) echo html_writer::tag('h1',$title);

    foreach($links as $n){

        $n->image = $model->get_image($n->image);
        $uri = $CFG->wwwroot.'/pluginfile.php/'.$n->image->contextid.'/'.$n->image->component.'/'.$n->image->filearea.'/'.$n->image->itemid.'/'.$n->image->filename;

        $view = new moodle_url('/local/links/view.php',array('id'=>$n->id));
        
        echo html_writer::start_tag('div',array('class'=> 'row-link'));

            echo html_writer::start_tag('div',array('class'=> 'link-link'));
                echo html_writer::tag('h2',$n->title);
            echo html_writer::end_tag('div');

            echo html_writer::start_tag('div',array('class'=> 'link-image'));
                echo html_writer::empty_tag('img',array('src'=>$uri));
            echo html_writer::end_tag('div');

            echo html_writer::start_tag('div',array('class'=> 'link-view'));
                echo html_writer::tag('a',get_string('view','local_link'),array('href'=>$view));
            echo html_writer::end_tag('div');

        echo html_writer::end_tag('div');

    }

    echo html_writer::end_tag('div');
}