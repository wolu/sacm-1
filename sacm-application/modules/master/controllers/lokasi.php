<?php defined('BASEPATH') or exit('No direct script access allowed');
class lokasi extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('lokasi_model');
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
    }
    function index() {
       $this->template->load('frontend','lokasi_all');
    }
	public function getJson()
    {
        $order  = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort   = $this->input->post('sort') ? $this->input->post('sort') : 'lokasi_kode';
        $page   = $this->input->post('page') ? $this->input->post('page') : 1;
        $rows   = $this->input->post('rows') ? $this->input->post('rows') : 15;
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output($this->lokasi_model->setJson($rows, $offset, $sort, $order));
    }
    function add()
    {
        $data = array(
        'lokasi_kode' => $this->input->post('lokasi_kode'), 
        'lokasi_desc' => $this->input->post('lokasi_desc')
        );
        $this->output->set_output($this->lokasi_model->insert($data));
    }
    function update()
    {
        $id = $this->input->post('lokasi_kode');
        $data = array(
        'lokasi_desc' => $this->input->post('lokasi_desc')
        );
        $this->output->set_output($this->lokasi_model->update($id, $data));
    }
    function delete()
    {
        $id = $this->input->post('id');
        $this->output->set_output(
        $this->lokasi_model->delete($id)
        );
    }
}