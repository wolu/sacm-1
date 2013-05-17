<?php defined('BASEPATH') or exit('No direct script access allowed');
class Asset_model extends CI_Model
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
        $jsonevents['rows']  = $this->db->get('asset')->result();
        $jsonevents['total'] = $this->db->get('asset')->num_rows();
        return json_encode($jsonevents);
    }
    function getJsonAlokasi($rows, $offset, $sort, $order,$status)
    {
        $this->db->where('KodeAlokasi', $status);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows']  = $this->db->get('asset')->result();
        $jsonevents['total'] = $this->db->get('asset')->num_rows();
        return json_encode($jsonevents);
    }
   
    function insert()
    {
        $data = array(
            'NomorKontrak' => $this->input->post('NomorKontrak', true),
            'KodeService' => $this->input->post('KodeService', true),
            'KodeAlokasi' => $this->input->post('KodeAlokasi', true),
            );
        $this->db->insert('asset', $data);
    }
    function update($id)
    {
        $data = array(
            'NomorKontrak' => $this->input->post('NomorKontrak', true),
            'KodeService' => $this->input->post('KodeService', true),
            'KodeAlokasi' => $this->input->post('KodeAlokasi', true),
            );
        $this->db->where('SerialNumber', $id);
        $this->db->update('asset', $data);
    }
    function delete($id)
    {
        foreach ($id as $sip) {
            $this->db->where('SerialNumber', $sip);
            $this->db->delete('asset');
        }
    }
}
?>