<?php defined('BASEPATH') or exit('No direct script access allowed');
class Request extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
       
        $this->load->model('request_model');
        $this->load->helper('date');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }
    }
    function index()
    {
        $this->template->load('frontend', 'request_form');
    }
    function review()
    {
        $this->template->load('frontend', 'request_tab');
    }
    function penjadwalan()
    {
        $this->template->load('frontend', 'request_jadwal');
    }
    function getJson()
    {
        $order  = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort   = $this->input->post('sort')  ? $this->input->post('sort')  : 'NomorRequest';
        $page   = $this->input->post('page')  ? $this->input->post('page')  : 1;
        $rows   = $this->input->post('rows')  ? $this->input->post('rows')  : 10;
        $status = htmlentities($_REQUEST['status']);
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output($this->request_model->setJson($rows, $offset, $sort, $order, $status));
    }
    
    function getDetJson()
    {
        $order = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort = $this->input->post('sort') ? $this->input->post('sort') : 'NomorRequest';
        $page = $this->input->post('page') ? $this->input->post('page') : 1;
        $rows = $this->input->post('rows') ? $this->input->post('rows') : 10;
        $noReq = htmlentities($_REQUEST['NomorRequest']);
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output($this->request_model->setDetilJson($rows, $offset, $sort,$order, $noReq));
    }
    function add()
    {
            $data = array(
            'NomorRequest'      => $this->input->post('KodeService').random_string('numeric', 6),
            'Peminta'           => $this->input->post('Peminta'),
            'CC'                => $this->input->post('CC'),
            'NomorSurat'        => $this->input->post('NomorSurat'),
            'TanggalSurat'      => $this->input->post('TanggalSurat'),
            'PenanggungJawab'   => $this->input->post('PenanggungJawab'),
            'Pemakai'           => $this->input->post('Pemakai'),
            'TeleponPemakai'    => $this->input->post('TeleponPemakai'),
            'KontakPerson'      => $this->input->post('KontakPerson'),
            'TeleponKontak'     => $this->input->post('TeleponKontak'),
            'LokasiPasang'      => $this->input->post('LokasiPasang'),
            'TanggalInput'      => date('Y-m-d H:i:s'),//$this->input->post('TanggalInput'),
            'KodeService'       => $this->input->post('KodeService'),
            'KodeStatus'        => 'O');//$this->input->post('KodeStatus')
            $this->output->set_output(
                $this->request_model->insert($data)
            );
    }
    //INSERT CONFIGURASI
    function addConfig()
    {
        $data = array(
            'NomorRequest' => $this->input->post('NomorRequest'),
            'KodeKonfigurasi' => $this->input->post('KodeKonfigurasi'),
            'Konfigurasi' => $this->input->post('Konfigurasi'),
            'TanggalInput'      => date('Y-m-d H:i:s'));
        $this->output->set_output($this->request_model->insertConfig($data));
    }
    function update()
    {
        $id = $this->input->post('NomorRequest');
        $data = array(
            'Peminta'           => $this->input->post('Peminta'),
            'CC'                => $this->input->post('CC'),
            'NomorSurat'        => $this->input->post('NomorSurat'),
            'TanggalSurat'      => $this->input->post('TanggalSurat'),
            'PenanggungJawab'   => $this->input->post('PenanggungJawab'),
            'Pemakai'           => $this->input->post('Pemakai'),
            'TeleponPemakai'    => $this->input->post('TeleponPemakai'),
            'KontakPerson'      => $this->input->post('KontakPerson'),
            'TeleponKontak'     => $this->input->post('TeleponKontak'),
            'LokasiPasang'      => $this->input->post('LokasiPasang'),
            'TanggalInput'      => $this->input->post('TanggalInput'),
            'KodeService'       => $this->input->post('KodeService'),
            'KodeStatus'        => $this->input->post('KodeStatus'));
        $this->output->set_output($this->request_model->update($id, $data));
    }
    function updateStatus()
    {
        $id = $this->input->post('id');
        $data = array(
        'KodeStatus' => $this->input->post('KodeStatus'),
        );
        $this->output->set_output($this->request_model->updateStatus($id, $data));
    }
    function delete()
    {
        $id = $this->input->post('id');
        $this->output->set_output($this->request_model->delete($id));
    }
}
