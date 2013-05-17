<?php defined('BASEPATH') or exit('No direct script access allowed');
class tipe_change extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('tipe_change_model');
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
    }
    function index() {
         $this->template->load('frontend','tipe_change_all');
    }
    function getJson()
    {
        $order  = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort   = $this->input->post('sort') ? $this->input->post('sort') : 'CodeChange';
        $page   = $this->input->post('page') ? $this->input->post('page') : 1;
        $rows   = $this->input->post('rows') ? $this->input->post('rows') : 15;
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output($this->tipe_change_model->getJson($rows, $offset, $sort, $order));
    }
    function add()
    {
        $data = array(
        'CodeChange' => $this->input->post('CodeChange'), 
        'Desc' => $this->input->post('Desc')
        );
        $this->output->set_output($this->tipe_change_model->insert($data));
    }
    function update()
    {
        $id = $this->input->post('CodeChange');
        $data = array(
        'Desc' => $this->input->post('Desc')
        );
        $this->output->set_output($this->tipe_change_model->update($id, $data));
    }
    function delete()
    {
        $id = $this->input->post('id');
        $this->output->set_output($this->tipe_change_model->delete($id));
    }
}