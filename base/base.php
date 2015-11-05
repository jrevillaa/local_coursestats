<?php

//define('INTERNAL_ACCESS',1);
/**
 *
 * @package    globalatypax
 * @subpackage lib
 * @copyright  2015 ATYPAX
 * @author     Jair Revilla
 * @version    1.0
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');

class moo_links
{
    protected $user;
    protected $config;
    protected $course;
    
    public function __construct(array $configs = null)
    {
        if ($configs !== null) {
            $this->set_configs($configs);
        }
    }

    public function set_configs(array $configs)
    {
        foreach ($configs as $name => $value) {
            if (property_exists($this, $name)) {
                $this->{$name} = $value;
            }
        }

        return $this;
    }
    
    /**
     * setup administrator links and settings
     *
     * @param object $admin
     */
    public static function set_adminsettings($admin)
    {
        global $CFG;
        
        $me = new self();
        $me->grab_moodle_globals();
        $context = context_course::instance(SITEID);
        
        $admin->add('localplugins', new admin_category('links', 'Links'));
//        $admin->add('links', new admin_externalpage('addlinks', "Add Links", $CFG->wwwroot. "/local/links/index.php",
//        'moodle/site:config'));
//
//        $taindex = new admin_externalpage('addlinks', "Add Links", $CFG->wwwroot. "/local/links/index.php",
//        'moodle/site:config');

//        $admin->add('links',$taindex);
    }
    
    
    public function grab_moodle_globals()
    {
        global $CFG, $USER, $COURSE;

        $this->user = $USER;
        $this->course = $COURSE;
        $this->config = $CFG;

        return $this;
    }
    
    public function get_string($name, $a = null)
    {
        return stripslashes(get_string($name, 'local_globalatypax', $a));
    }
    
    public function get_config($name = null)
    {
        if ($name !== null && isset($this->config->{$name})) {
            return $this->config->{$name};
        }
        return $this->config;
    }
    
    public function get_string_fromcore($name, $a = null)
    {
        return get_string($name, '', $a);
    }


}
