<?php defined('BASEPATH') or exit('No direct script access allowed');
class Master_config extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('master_config_model');
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
    }
    function index()
    {
        $this->template->load('frontend','tab_master_config');
    }
    function getJson()
    {
        $order  = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort   = $this->input->post('sort')  ? $this->input->post('sort') : 'KodeKonfigurasi';
        $page   = $this->input->post('page')  ? $this->input->post('page') : 1;
        $rows   = $this->input->post('rows')  ? $this->input->post('rows') : 10;
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output(
        $this->master_config_model->getJson($rows, $offset, $sort, $order)
        );
    }
    function getJsonDetil()
    {
        $order  = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort   = $this->input->post('sort')  ? $this->input->post('sort') : 'KodeKonfig';
        $page   = $this->input->post('page')  ? $this->input->post('page') : 1;
        $rows   = $this->input->post('rows')  ? $this->input->post('rows') : 10;
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output(
        $this->master_config_model->getJsonDetil($rows, $offset, $sort, $order)
        );
    }
    function add()
    {
        $data = array(
        'KodeKonfigurasi' => $this->input->post('KodeKonfigurasi'), 
        'NamaKonfigurasi' => $this->input->post('NamaKonfigurasi'),
        'Deskripsi' => $this->input->post('Deskripsi')
        );
        $this->output->set_output(
        $this->master_config_model->insert($data)
        );
    }
    function addDet()
    {
        $data = array(
        'KodeKonfig'      => $this->input->post('KodeKonfig'), 
        'NamaKonfigDetil' => $this->input->post('NamaKonfigDetil'),
        'Deskripsi'       => $this->input->post('Deskripsi')
        );
        $this->output->set_output(
        $this->master_config_model->insertDet($data)
        );
    }
    function update()
    {
        $id   = $this->input->post('KodeKonfigurasi');
        $data = array( 
        'NamaKonfigurasi' => $this->input->post('NamaKonfigurasi'),
        'Deskripsi'       => $this->input->post('Deskripsi')
        );
        $this->output->set_output($this->master_config_model->update($id, $data)
        );
    }
    function updateDet()
    {
        $id = $this->input->post('KodeKonfig');
        $data = array( 
        'NamaKonfigDetil' => $this->input->post('NamaKonfigDetil'),
        'Deskripsi' => $this->input->post('Deskripsi') 
        );
        $this->output->set_output(
        $this->master_config_model->updateDet($id, $data)
        );
    }
    function delete()
    {
        $id = $this->input->post('id');
        $this->output->set_output($this->master_config_model->delete($id));
    }
    function deleteDet()
    {
        $id = $this->input->post('id');
        $this->output->set_output(
        $this->master_config_model->deleteDet($id)
        );
    }
}