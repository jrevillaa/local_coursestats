<?php

include('../../config.php');

$id = required_param('id',PARAM_INT);

$DB->delete_records('links_entry',array('id'=>$id));

redirect(new moodle_url('/local/links/index.php'));
