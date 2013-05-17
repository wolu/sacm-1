<?php defined('BASEPATH') or exit('No direct script access allowed');
class Costcenter_model extends CI_Model
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
        $jsonevents['rows']  = $this->db->get('cost_center')->result();
        $jsonevents['total'] = $this->db->get('cost_center')->num_rows();
        return json_encode($jsonevents);
    }
    function insert($data)
    {
        $result = $this->db->insert('cost_center', $data);
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil di Tambahkan'));
        } else {
            return json_encode(array('msg' => $this->db->_error_message()));
        }
    }
    function update($id, $data)
    {
        $this->db->where('CC', $id);
        $result = $this->db->update('cost_center', $data);
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Diupdate'));
        } else {
            return json_encode(array('msg' => $this->db->_error_message()));
        }
    }
    function delete($id)
    {
        $this->db->where('CC', $id);
        $result = $this->db->delete('cost_center');
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Dihapus'));
        } else {
            return json_encode(array('msg' => $this->db->_error_message()));
        }
    }
}