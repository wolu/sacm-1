<?php
class Asset_alokasi extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('asset/asset_alokasi_model','asset_alokasi');
    }
    function index()
    {
        $this->template->load('frontend', 'asset_alokasi_all');
    }
    function getJson()
    {
        $order = $this->input->post('order') ? $this->input->post('order') : 'asc';
        $sort = $this->input->post('sort')   ? $this->input->post('sort') : 'KodeAlokasi';
        $page = $this->input->post('page')   ? $this->input->post('page') : 1;
        $rows = $this->input->post('rows')   ? $this->input->post('rows') : 10;
        $offset = ($page - 1) * $rows;
        header("Content-Type: application/json");
        $this->output->set_output($this->asset_alokasi->getJson($rows, $offset, $sort, $order));
    }
}
?>