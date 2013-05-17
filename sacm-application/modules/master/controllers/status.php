<?php defined('BASEPATH') or exit('No direct script access allowed');
class status extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('status_model');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }
    function index()
    {
        $this->template->load('frontend', 'status_all');
    }
    function getJson()
    {
        $order = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort = $this->input->post('sort') ? $this->input->post('sort') : 'KodeStatus';
        $page = $this->input->post('page') ? $this->input->post('page') : 1;
        $rows = $this->input->post('rows') ? $this->input->post('rows') : 15;
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output($this->status_model->getJson($rows, $offset, $sort, $order));
    }
    function add()
    {
        $data = array(
            'KodeStatus' => $this->input->post('KodeStatus'),
            'Status' => $this->input->post('Status'),
            'Kategori' => $this->input->post('Kategori'));
        $this->output->set_output($this->status_model->insert($data));
    }
    function update()
    {
        $id = $this->input->post('KodeStatus');
        $data = array('Status' => $this->input->post('Status'), 'Kategori' => $this->
                input->post('Kategori'));
        $this->output->set_output($this->status_model->update($id, $data));
    }
    function delete()
    {
        $id = $this->input->post('id');
        $this->output->set_output($this->status_model->delete($id));
    }
}
