<?php defined('BASEPATH') or exit('No direct script access allowed');

class Rfc extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('rfc_model');
        $this->load->model('master/tipe_change_model', 'tipe_change');
        $this->load->helper('date');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) {
            redirect('auth/login', 'refresh');
        }
    }
    //Page Index
    function index()
    {
        $this->template->load('frontend', 'rfc_form');
    }
    //Page Review
    function review()
    {
        $this->template->load('frontend', 'rfc_tab');
    }
    //Page Penjadwalan
    function penjadwalan()
    {
        $this->template->load('frontend', 'rfc_jadwal');
    }
    function getJson()
    {
        $page   = $this->input->post('page')  ? intval($this->input->post('page')) : 1;
        $rows   = $this->input->post('rows')  ? intval($this->input->post('rows')) : 10;
        $sort   = $this->input->post('sort')  ? strval($this->input->post('sort')) :'TanggalRFC';
        $order  = $this->input->post('order') ? strval($this->input->post('order')) :'ASC';
        $status = htmlentities($_REQUEST['status']);
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output(
        $this->rfc_model->setJson($rows, $offset, $sort, $order,$status)
        );
    }
    
    function getDetJson()
    {
        $page = $this->input->post('page') ? intval($this->input->post('page')) : 1;
        $rows = $this->input->post('rows') ? intval($this->input->post('rows')) : 10;
        $sort = $this->input->post('sort') ? strval($this->input->post('sort')) :'NomorRFC';
        $order = $this->input->post('order') ? strval($this->input->post('order')) :'ASC';
        $noRFC = $_REQUEST['NomorRFC'];
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output($this->rfc_model->setDetilJson($rows, $offset, $sort,$order, $noRFC));
    }
    function getJsonConfigDet()
    {
        $page   = $this->input->post('page') ? intval($this->input->post('page')) : 1;
        $rows   = $this->input->post('rows') ? intval($this->input->post('rows')) : 10;
        $sort   = $this->input->post('sort') ? strval($this->input->post('sort')) :'KodeKonfig';
        $order  = $this->input->post('order') ? strval($this->input->post('order')) :'ASC';
        $cek = $_REQUEST['cek'];
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output($this->rfc_model->setJsonConfigDet($rows, $offset, $sort,$order,$cek));
    }
    function add()
    {
        $data = array(
            'NomorRFC'      => random_string('numeric', 10),
            'NomorService'  => $this->input->post('NomorService'),
            'SerialNumber'  => $this->input->post('SerialNumber'),
            'TanggalRFC'    => $this->input->post('TanggalRFC'),
            'TanggalInput'  => date('Y-m-d H:i:s'),
            'CodeChange'    => $this->input->post('CodeChange'),
            'RFCId'         => $this->input->post('RFCId') ? $this->input->post('RFCId') : '',
            'TanggalTarget' => $this->input->post('TanggalTarget'),
            'KodeStatus'    => 'O');
        $this->output->set_output(
            $this->rfc_model->insert($data)
        );
    }
    //INSERT CONFIGURASI
    function addConfig()
    {
        $data = array(
            'NomorRFC'       => $this->input->post('NomorRFC'),
            'KodeKonfigurasi'=> $this->input->post('KodeKonfigurasi'),
            'Konfigurasi'    => $this->input->post('Konfigurasi'),
            'TanggalInput'   => date('Y-m-d H:i:s')
             );
        $this->output->set_output(
            $this->rfc_model->insertConfig($data)
            );
    }
    //UPDATE RECORD RFC
    function update()
    {
        $id = $this->input->post('NomorRFC');
        $data = array(
            'NomorService' => $this->input->post('NomorService'),
            'SerialNumber' => $this->input->post('SerialNumber'),
            'TanggalRFC'   => $this->input->post('TanggalRFC'),
            'TanggalInput' => $this->input->post('TanggalInput'),
            'CodeChange'   => $this->input->post('CodeChange'),
            'RFCId'        => $this->input->post('RFCId'),
            'TanggalTarget'=> $this->input->post('TanggalTarget'),
            'KodeStatus'   => $this->input->post('KodeStatus'));
        $this->output->set_output(
            $this->rfc_model->update($id, $data)
            );
    }
    //UPDATE STATUS MENJADI OPEN CONFIG
    function updateConfig()
    {
        $id = $this->input->post('id');
        $data = array(
        'KodeStatus' => $this->input->post('KodeStatus')
        );
        $this->output->set_output($this->rfc_model->updateStatus($id, $data));
    }
    //UPDATE RFC ID DAN TANGGAL TARGET UNTUK RELEASE MANAGEMENT
    function updateRelease()
    {
        $id     = $this->input->post('NomorRFC');                       
        $data   = array(
        'RFCId' => $this->input->post('RFCId'),
        'TanggalTarget' => $this->input->post('TanggalTarget')
        );
        if ($data['RFCId'] == '') {
            $this->output->set_output(json_encode(array('msg' => 'Nomor RFCId Tidak Boleh Kosong')));
            return false;
        }
        $this->output->set_output(
        $this->rfc_model->update($id, $data)
        );
    }
    function updateSerialNumber()
    {
       $data = array(
       'NomorRFC'      => $this->input->post('NomorRFC'),
       'SerialNumber'  => $this->input->post('SerialNumber'),
       'NomorService'  => $this->input->post('NomorService'),
       'KodeAlokasi'   => $this->input->post('KodeAlokasi'),
       'SerialAsset'   => $this->input->post('SerialAsset'),
       'NomorAsal'     => random_string('numeric',8)
       ); 
       header("Content-Type: application/json");
       $this->output->set_output(
       $this->rfc_model->updateSnSerial($data)
       );
    }
   //DELETE RECORD
    function delete()
    {
        $id = $this->input->post('id');
        $this->output->set_output(
        $this->rfc_model->delete($id)
        );
    }
}
?>