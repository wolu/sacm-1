<?php
class Service extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('service_model');
        if (!$this->ion_auth->logged_in()){
            redirect('auth/login', 'refresh');
        }
    }
    function index(){
    $this->template->load('frontend','service_all');
    }
    function getJson()
    {
        $order  = $this->input->post('order') ? strval($this->input->post('order')) : 'asc';
        $sort   = $this->input->post('sort')  ?  strval($this->input->post('sort')) : 'NomorService';
        $page   = $this->input->post('page')  ?  intval($this->input->post('page')) : 1;
        $rows   = $this->input->post('rows')  ?  intval($this->input->post('rows')) : 10;
        $offset = ($page - 1) * $rows;
        $cari = $this->input->post('cari');
        header("Content-Type: application/json");
        $this->service_model->setJson($rows, $offset, $sort, $order, $cari);
    }
}
?>