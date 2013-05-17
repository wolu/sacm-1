<?php defined('BASEPATH') or exit('No direct script access allowed');
class Status_asset extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('status_asset_model');
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
    }
    function index()
    {
        $this->template->load('frontend','status_asset_all');
    }
    function getJson()
    {
        $order  = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort   = $this->input->post('sort') ? $this->input->post('sort') : 'KodeAlokasi';
        $page   = $this->input->post('page') ? intval($this->input->post('page')) : 1;
        $rows   = $this->input->post('rows') ? intval($this->input->post('rows')) : 10;
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output($this->status_asset_model->getJson($rows, $offset, $sort, $order));
    }
    function getJsonCek()
    {
        $order  = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort   = $this->input->post('sort') ? $this->input->post('sort') : 'KodeAlokasi';
        $page   = $this->input->post('page') ? intval($this->input->post('page')) : 1;
        $rows   = $this->input->post('rows') ? intval($this->input->post('rows')) : 10;
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output($this->status_asset_model->getJsonCek($rows, $offset, $sort, $order));
    }
    function add()
    {
        $data = array(
        'KodeAlokasi' => $this->input->post('Kodelokasi'),
        'NamaAlokasi' => $this->input->post('NamaAlokasi')
        );
        $this->output->set_output($this->status_asset_model->insert($data));
    }
    function update()
    {
        $id = $this->input->post('KodeAlokasi');
        $data = array(
        'NamaAlokasi' => $this->input->post('NamaAlokasi')
        );
        $this->output->set_output($this->status_asset_model->update($id, $data));
    }
    function delete()
    {
        $id = $this->input->post('id');
        $this->output->set_output(
        $this->status_asset_model->delete($id)
        );
    }
}