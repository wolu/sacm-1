<?php defined('BASEPATH') or exit('No direct script access allowed');
class Tipe_change_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function getJson($rows, $offset, $sort, $order)
    {
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $total = $this->db->count_all();
        $jsonevents = array();
        $jsonevents['rows'] = $this->db->get('tipe_change')->result();
        $jsonevents['total'] = $this->db->get('tipe_change')->num_rows();
        return json_encode($jsonevents);
    }
    function getById($id)
    {
        $this->db->where('CodeChange', $id);
        $result = $this->db->get('tipe_change');
        if ($result->num_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }
    function insert($data)
    {
        $result = $this->db->insert('tipe_change', $data);
        if ($result) {
            return json_encode(array('success' =>
                    'Data Berhasil di Tambahkan'));
        } else {
            return json_encode(array('msg' =>$this->db->_error_message()));
        }
    }
    function update($id, $data)
    {
        $this->db->where('CodeChange', $id);
        $result = $this->db->update('tipe_change', $data);
        if ($result) {
            return json_encode(array('success' =>
                    'Data Berhasil Diupdate'));
        } else {
            return json_encode(array('msg' =>$this->db->_error_message()));
        }
    }
    function delete($id)
    {
        $this->db->where('CodeChange', $id);
        $result = $this->db->delete('tipe_change');
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Dihapus'));
        } else {
            return json_encode(array('msg' =>$this->db->_error_message()));
        }
    }
}
