<?php defined('BASEPATH') or exit('No direct script access allowed');
class Unitkerja extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('unitkerja_model');
    }
    function index()
    {
        $this->template->load('frontend', 'unitkerja_all');
    }
    function getJson()
    {
        $order = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort = $this->input->post('sort') ? $this->input->post('sort') :
            'KodeUnitKerja';
        $page = $this->input->post('page') ? $this->input->post('page') : 1;
        $rows = $this->input->post('rows') ? $this->input->post('rows') : 15;
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output($this->unitkerja_model->getJson($rows, $offset, $sort,
            $order));
    }
    function add()
    {
        $data = array(
            'KodeUnitKerja' => $this->input->post('KodeUnitKerja'),
            'Nama' => $this->input->post('Nama'),
            'Abreviation' => $this->input->post('Abreviation'),
            'CC' => $this->input->post('CC'));
        $this->output->set_output($this->unitkerja_model->insert($data));
    }
    function update()
    {
        $id = $this->input->post('KodeUnitKerja');
        $data = array(
            'Nama' => $this->input->post('Nama'),
            'Abreviation' => $this->input->post('Abreviation'),
            'CC' => $this->input->post('CC'));
        $this->output->set_output($this->unitkerja_model->update($id, $data));
    }
    function delete()
    {
        $id = $this->input->post('id');
        $this->output->set_output($this->unitkerja_model->delete($id));
    }
}
