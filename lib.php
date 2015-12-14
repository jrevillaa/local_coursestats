<?php

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');

function print_graphic($id){
	//include('../../../config.php');
    //global $CFG;
    global $USER;
    global $DB;
	$output = '';

	$keys = array_keys($USER->profile);

	$list = explode(PHP_EOL,$DB->get_record('user_info_field',  array('categoryid' =>  1))->param1);

	$total_alumnos = 0;
	$rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    	
   //hazme un favor se que pronto vas a enamorarla
   

	/*$output .= '<div id="canvas-holder">';
	$output .= '<canvas id="chart-area" width="450" height="450"/>';
	$output .= '</div>';*/

	//tabala leyenda
	$output .= '';
	

	foreach ($list as $value) {
		
		$sql_group = "SELECT  mdl_user.username, mdl_user_info_data.data FROM mdl_course 
		INNER JOIN mdl_context ON mdl_context.instanceid = mdl_course.id 
		INNER JOIN mdl_role_assignments ON mdl_context.id = mdl_role_assignments.contextid 
		INNER JOIN mdl_role ON mdl_role.id = mdl_role_assignments.roleid 
		INNER JOIN mdl_user ON mdl_user.id = mdl_role_assignments.userid 
		INNER JOIN mdl_user_info_data ON mdl_user_info_data.userid = mdl_role_assignments.userid 
		WHERE mdl_role.id = 5 AND mdl_course.id = $id AND mdl_user_info_data.data = '$value'" ;

		$data = $DB->get_records_sql($sql_group);
		$temp = $DB->get_record('links_entry',  array('name' => $value));
		//print_r($temp);
		if(is_object($temp)){
			$info[$value] = array(count($data), $temp->colorhead);
		}else{
			$color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
			$info[$value] =  array(count($data), $color);	
		}
			$total_alumnos += $info[$value][0];

	} 

	array_multisort($info,SORT_DESC);

	$leyenda = html_writer::start_tag('ul', array('class'=>'leyenda'));
	foreach ($info as $key=>$value) {
		if($value[0] > 0){
			$leyenda .= html_writer::start_tag('li');

			$leyenda .= html_writer::tag('span', '', array('class'=>'color', 'style'=>'background:'.$value[1].';'));
			$leyenda .= html_writer::tag('span', $key . ': ' . $value[0] , array('class'=>'name'));

			$leyenda .= html_writer::end_tag('li');
		}
	}

	$leyenda .= html_writer::start_tag('li', array('class'=>'total-alumnos'));
	$leyenda .= html_writer::tag('span', 'TOTAL: ' . $total_alumnos, array('class'=>'name bold'));
	$leyenda .= html_writer::end_tag('li');

	$leyenda .= html_writer::end_tag('ul');

	//$output .= '<script type="text/vbscript" src="js/Chart.js"></script>';
	$output .= '<script>';
	$output .= 'var pieData = [';
	foreach ($info as $key => $value) {
		if($value[0] > 0){
			$output .= '{';
			$output .= 'value:' . $value[0] . ',';
			$output .= 'color:"' . $value[1] . '",';
			$output .= 'highlight: "#FF5A5E",';
			$output .= 'label: "' . $key . '"';
			$output .= '},';
		}
	}
	$output .= '];';
	$output .= 'window.onload = function(){';
	
	$output .= 'document.getElementsByClassName("programs")[0].innerHTML += \'' . $leyenda . '\';';
	$output .= 'var ctx = document.getElementById("chart-area").getContext("2d");';
	$output .= 'window.myPie = new Chart(ctx).Doughnut(pieData);';
	$output .= '};';
	$output .= '</script>';

	echo $output;
}

