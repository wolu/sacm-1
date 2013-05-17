<?php (defined('BASEPATH')) OR exit('No direct script access allowed');
/**
 * Router Class
 *
 * Parses URIs and determines routing
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @author      ExpressionEngine Dev Team
 * @category    Libraries
 * @link        http://codeigniter.com/user_guide/general/routing.html
 */
// Load MX Router
require APPPATH."third_party/MX/Router.php";
/**
 * HMVC Router
 *
 * @package     CodeIgniter
 * @subpackage  Libraries
 * @author      Wahyu Kristianto <w.kristories@gmail.com>
 * @copyright   Copyright (c) 2012, Wahyu Kristianto
 * @license     http://creativecommons.org/licenses/by/3.0/
 * @link        https://gist.github.com/Kristories/5227732
 * @version     1.0.0
 */
class MY_Router extends MX_Router
{
     public function __construct()
     {
         parent::__construct();
     }
     function _set_routing()
     {
         // Are query strings enabled in the config file?  Normally CI doesn't utilize query strings
         // since URI segments are more search-engine friendly, but they can optionally be used.
         // If this feature is enabled, we will gather the directory/class/method a little differently
       $segments = array();
         if ($this->config->item('enable_query_strings') === TRUE AND isset($_GET[$this->config->item('controller_trigger')]))
         {
             if (isset($_GET[$this->config->item('directory_trigger')]))
             {
                 $this->set_directory(trim($this->uri->_filter_uri($_GET[$this->config->item('directory_trigger')])));
                 $segments[] = $this->fetch_directory();
             }
            if (isset($_GET[$this->config->item('controller_trigger')]))
             {
                 $this->set_class(trim($this->uri->_filter_uri($_GET[$this->config->item('controller_trigger')])));
                 $segments[] = $this->fetch_class();
             }
             if (isset($_GET[$this->config->item('function_trigger')]))
             {
                $this->set_method(trim($this->uri->_filter_uri($_GET[$this->config->item('function_trigger')])));
                 $segments[] = $this->fetch_method();
             }
         }
         // Load the routes.php file.
         if (defined('ENVIRONMENT') AND is_file(APPPATH.'config/'.ENVIRONMENT.'/routes.php'))
         {
             include(APPPATH.'config/'.ENVIRONMENT.'/routes.php');
         }
         elseif (is_file(APPPATH.'config/routes.php'))
         {
             include(APPPATH.'config/routes.php');
         }
         // Include routes every modules
         foreach (config_item('modules_locations') as $key => $value)
         {
             if ($handle = opendir($key))
             {
                 while (false !== ($entry = readdir($handle)))
                 {
                     if ($entry != "." && $entry != "..")
                     {
                         if(is_dir($key.$entry))
                         {
                             $rfile = Modules::find('routes'.EXT, $entry, 'config/');
                             if($rfile[0])
                             {
                                 include($rfile[0].$rfile[1]);
                             }
                         }
                     }
                 }
                 closedir($handle);
             }
         }
         $this->routes = ( ! isset($route) OR ! is_array($route)) ? array() : $route;
         unset($route);
         // Set the default controller so we can display it in the event
         // the URI doesn't correlated to a valid controller.
         $this->default_controller = ( ! isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? FALSE : strtolower($this->routes['default_controller']);
         // Were there any query string segments?  If so, we'll validate them and bail out since we're done.
         if (count($segments) > 0)
         {
             return $this->_validate_request($segments);
         }
        // Fetch the complete URI string
         $this->uri->_fetch_uri_string();
         // Is there a URI string? If not, the default controller specified in the "routes" file will be shown.
         if ($this->uri->uri_string == '')
         {
             return $this->_set_default_controller();
         }
         // Do we need to remove the URL suffix?
         $this->uri->_remove_url_suffix();
         // Compile the segments into an array
         $this->uri->_explode_segments();
         // Parse any custom routing that may exist
         $this->_parse_routes();
         // Re-index the segment array so that it starts with 1 rather than 0
         $this->uri->_reindex_segments();
     }
}