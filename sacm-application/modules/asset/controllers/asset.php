<?php
class asset extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('asset_model');
    }
    function index() {
        $this->template->load('frontend','asset_all');
    }
    function getJson()
    {
        $order  = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort   = $this->input->post('sort') ? $this->input->post('sort') : 'KodeService';
        $page   = $this->input->post('page') ? $this->input->post('page') : 1;
        $rows   = $this->input->post('rows') ? $this->input->post('rows') : 15;
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output($this->asset_model->getJson($rows, $offset, $sort, $order));
    }
    function getJsonAlokasi()
    {
        $order  = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort   = $this->input->post('sort') ? $this->input->post('sort') : 'KodeService';
        $page   = $this->input->post('page') ? $this->input->post('page') : 1;
        $rows   = $this->input->post('rows') ? $this->input->post('rows') : 15;
        $status = $_REQUEST['status'];
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output($this->asset_model->getJsonAlokasi($rows, $offset, $sort, $order, $status));
    }
}
?>