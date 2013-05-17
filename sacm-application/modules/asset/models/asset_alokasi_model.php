<?php defined('BASEPATH') or exit('No direct script access allowed');
class Asset_alokasi_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function getJson($rows, $offset, $sort, $order)
    {
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows']  = $this->db->get('asset_alokasi')->result();
        $jsonevents['total'] = $this->db->get('asset_alokasi')->num_rows();
        return json_encode($jsonevents);
    }
}
?>