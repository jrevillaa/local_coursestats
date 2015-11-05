<?php
/*defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig && isset($ADMIN)) {
    include_once realpath(dirname(__FILE__)) . '/lib/base.php';
    moo_links::set_adminsettings($ADMIN);
}*/

$settings = null;

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {

    $taindex = new admin_externalpage('addlinks', "Add Links", "$CFG->wwwroot/local/links/index.php",
        'moodle/site:config');
        $ADMIN->add('localplugins',new admin_category('links','Links'));
        $ADMIN->add('links', $taindex);
}

