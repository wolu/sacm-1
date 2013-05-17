<?php defined('BASEPATH') or exit('No direct script access allowed');
class Karyawan extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model('Karyawan_model');
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
    }
    function index(){
        $this->template->load('frontend','karyawan_all');
    }
	public function getJson()
    {
        $order  = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort   = $this->input->post('sort') ? $this->input->post('sort') : 'cc';
        $page   = $this->input->post('page') ? $this->input->post('page') : 1;
        $rows   = $this->input->post('rows') ? $this->input->post('rows') :20;
        $cari   = $this->input->post('cari');
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output($this->Karyawan_model->setJson($rows, $offset, $sort, $order, $cari));
    }
   	    
    function add()
    {
        $data = array(
        'karyawan_unit'     => $this->input->post('karyawan_unit'),
        'karyawan_nik'      => $this->input->post('karyawan_nik'),
        'karyawan_nama'     => $this->input->post('karyawan_nama'),
        'cc'                => $this->input->post('cc'),
        'cc_nama'           => $this->input->post('cc_nama'),
        'karyawan_kota'     => $this->input->post('karyawan_kota')
        );
        $this->output->set_output($this->Karyawan_model->insert($data));
    }
    function update()
    {
        $id = $this->input->post('karyawan_nik');
        $data = array(
        'karyawan_unit'     => $this->input->post('karyawan_unit'),
        'karyawan_nama'     => $this->input->post('karyawan_nama'),
        'cc'                => $this->input->post('cc'),
        'cc_nama'           => $this->input->post('cc_nama'),
        'karyawan_kota'     => $this->input->post('karyawan_kota')
        );
        $this->output->set_output($this->Karyawan_model->update($id, $data));
    }
    function delete()
    {
        $id = $this->input->post('id');
        $this->output->set_output($this->Karyawan_model->delete($id));
    }
    public function like()
    {
        $id = $this->input->post('id');
        header("Content-Type: application/json");
        $this->output->set_output($this->Karyawan_model->setJson($id));
    }
}