<?php defined('BASEPATH') or exit('No direct script access allowed');
class Menu_tree extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('menu');
    }
    function index()
    {
        
        header("Content-Type: application/json");
        $this->output->set_output(
        $this->menu->get_data()
        );
    }
}