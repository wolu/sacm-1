<?php defined('BASEPATH') or exit('No direct script access allowed');
class Status_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function getJson($rows, $offset, $sort, $order)
    {
        $this->db->cache_on();
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows'] = $this->db->get('status')->result();
        $jsonevents['total'] = $this->db->get('status')->num_rows();
        return json_encode($jsonevents);
    }
    function insert($data)
    {
        $result = $this->db->insert('status', $data);
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil di Tambahkan'));
        } else {
            return json_encode(array('msg' => $this->db->_error_message()));
        }
    }
    function update($id, $data)
    {
        $this->db->where('KodeStatus', $id);
        $result = $this->db->update('status', $data);
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Diupdate'));
        } else {
            return json_encode(array('msg' => $this->db->_error_message()));
        }
    }
    function delete($id)
    {
        $this->db->where('KodeStatus', $id);
        $result = $this->db->delete('status');
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Dihapus'));
        } else {
            return json_encode(array('msg' => $this->db->_error_message()));
        }
    }
}
