<?php defined('BASEPATH') or exit('No direct script access allowed');
class Costcenter extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('costcenter_model');
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
    }
    function index()
    {
        $this->template->load('frontend', 'costcenter_all');
    }
    function getJson()
    {
        $order = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort  = $this->input->post('sort')  ? $this->input->post('sort')  : 'CC';
        $page  = $this->input->post('page')  ? $this->input->post('page')  : 1;
        $rows  = $this->input->post('rows')  ? $this->input->post('rows')  : 15;
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output(
        $this->costcenter_model->getJson($rows, $offset, $sort, $order)
        );
    }
    function add()
    {
        $data = array(
        'CC' => $this->input->post('CC'), 
        'NamaCC' => $this->input->post('NamaCC')
        );
        $this->output->set_output(
        $this->costcenter_model->insert($data)
        );
    }
    function update()
    {
        $id = $this->input->post('CC');
        $data = array(
        'NamaCC' => $this->input->post('NamaCC')
        );
        $this->output->set_output(
        $this->costcenter_model->update($id, $data)
        );
    }
    function delete()
    {
        $id = $this->input->post('id');
        $this->output->set_output(
        $this->costcenter_model->delete($id)
        );
    }
}