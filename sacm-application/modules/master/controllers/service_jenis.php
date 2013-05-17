<?php defined('BASEPATH') or exit('No direct script access allowed');
class service_jenis extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('service_jenis_model');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }
    function index()
    {
        $this->template->load('frontend', 'service_jenis_all');
    }
    function getJson()
    {
        $order = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort = $this->input->post('sort') ? $this->input->post('sort') : 'KodeService';
        $page = $this->input->post('page') ? $this->input->post('page') : 1;
        $rows = $this->input->post('rows') ? $this->input->post('rows') : 15;
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");

        $this->output->set_output($this->service_jenis_model->getJson($rows, $offset, $sort,
            $order));
    }
    function add()
    {
        $data = array('KodeService' => $this->input->post('KodeService'), 'Nama' => $this->
                input->post('Nama'));
        $this->output->set_output($this->service_jenis_model->insert($data));
    }
    function update()
    {
        $id = $this->input->post('KodeService');
        $data = array('Nama' => $this->input->post('Nama'));
        $this->output->set_output($this->service_jenis_model->update($id, $data));
    }
    function delete()
    {
        $id = $this->input->post('id');
        $this->output->set_output($this->service_jenis_model->delete($id));
    }
}
