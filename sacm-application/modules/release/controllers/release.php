<?php defined('BASEPATH') or exit('No direct script access allowed');
class Release extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('release_model');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } 
        /**elseif (!$this->ion_auth->is_admin()) {
            redirect('auth/login', 'refresh');
        }*/
    }
    //Page Index
    function assign_teknisi()
    {
        $this->template->load('frontend', 'tab_assign_teknisi');
    }
    function acceptance()
    {
        $this->template->load('frontend', 'tab_acceptance');
    }
    function install_config()
    {
        $this->template->load('frontend', 'tab_install_config');
    }
    function pengambilan_barang()
    {
        $this->template->load('frontend', 'tab_pengambilan_barang');
    }
    function closing()
    {
        $this->template->load('frontend', 'tab_closing');
    }
    function disposal()
    {
        $this->template->load('frontend', 'tab_closing');
    }
    function getJsonRfc()
    {
        $page   = $this->input->post('page') ? intval($this->input->post('page')) : 1;
        $rows   = $this->input->post('rows') ? intval($this->input->post('rows')) : 10;
        $sort   = $this->input->post('sort') ? strval($this->input->post('sort')) :'TanggalRFC';
        $order  = $this->input->post('order') ? strval($this->input->post('order')) :'ASC';
        $status = htmlentities($_REQUEST['status']);
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output(
        $this->release_model->setJsonRfc($rows, $offset, $sort, $order, $status)
        );
    }
    function getDetJsonRfc()
    {
        $page   = $this->input->post('page')  ? intval($this->input->post('page')) : 1;
        $rows   = $this->input->post('rows')  ? intval($this->input->post('rows')) : 10;
        $sort   = $this->input->post('sort')  ? strval($this->input->post('sort')) :'NomorRFC';
        $order  = $this->input->post('order') ? strval($this->input->post('order')) :'ASC';
        $noRFC  = $_REQUEST['NomorRFC'];
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output(
        $this->rfc_model->Rfc($rows, $offset, $sort,$order, $noRFC)
        );
    }
    function getJsonReq()
    {
        $order  = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort   = $this->input->post('sort')  ? $this->input->post('sort')  : 'NomorRequest';
        $page   = $this->input->post('page')  ? $this->input->post('page')  : 1;
        $rows   = $this->input->post('rows')  ? $this->input->post('rows')  : 10;
        $status = htmlentities($_REQUEST['status']);
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output(
        $this->release_model->setJsonReq($rows, $offset, $sort, $order, $status)
        );
    }
    function getDetJsonReq()
    {
        $order = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort = $this->input->post('sort') ? $this->input->post('sort') : 'NomorRequest';
        $page = $this->input->post('page') ? $this->input->post('page') : 1;
        $rows = $this->input->post('rows') ? $this->input->post('rows') : 10;
        $noReq = htmlentities($_REQUEST['NomorRequest']);
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output(
        $this->release_model->setDetilJsonReq($rows, $offset, $sort,$order, $noReq)
        );
    }
    function addTeknisi()
    {
        $data = array(
        'NomorDokumen' =>$this->input->post('NomorRFC'),
        'NIK' =>$this->input->post('NIK'),
        'Jabatan'=> $this->input->post('Jabatan')
        );
        
        $this->output->set_output(
        $this->release_model->insertTeknisi($data)
        );
    }
    function updateTeknisi()
    {
        $id =$this->input->post('NomorRFC');
        $data = array(
        'NIK' =>$this->input->post('NIK'),
        'Jabatan'=> $this->input->post('Jabatan')
        );
        $this->output->set_output(
        $this->release_model->updateTeknisi($id , $data)
        );
    }
    function updateStatusRfc()
    {
        $id = $this->input->post('id');
        $data = array('KodeStatus' => $this->input->post('KodeStatus'));
        $this->output->set_output($this->release_model->updateStatusRfc($id, $data));
    }
    function updateStatusReq()
    {
        $id = $this->input->post('id');
        $data = array('KodeStatus' => $this->input->post('KodeStatus'));
        $this->output->set_output($this->release_model->updateStatusReq($id, $data));
    }
}
