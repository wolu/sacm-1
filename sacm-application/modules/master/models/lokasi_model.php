<?php defined('BASEPATH') or exit('No direct script access allowed');
class Lokasi_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    function setJson($rows, $offset, $sort, $order)
    {
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows'] = $this->db->get('lokasi')->result();
        $jsonevents['total'] = $this->db->get('lokasi')->num_rows();
        return json_encode($jsonevents);
    }
    function insert($data)
    {
        $result = $this->db->insert('lokasi', $data);
        if ($result) {
            return json_encode(array('success' =>'Data Berhasil di Tambahkan'));
        } else {
            return json_encode(array('msg' => $this->db->_error_message()));
        }
    }
    function update($id, $data)
    {
        $this->db->where('lokasi_kode', $id);
        $result = $this->db->update('lokasi', $data);
        if ($result) {
            return json_encode(array('success' =>'Data Berhasil Diupdate'));
        }else{
            return json_encode(array('msg' => $this->db->_error_message()));
        }
    }
    function delete($id)
    {
        $this->db->where('lokasi_kode', $id);
        $result = $this->db->delete('lokasi');
        if ($result) {
            return json_encode(array('success' =>'Data Berhasil Dihapus'));
        } else {
            return json_encode(array('msg' =>$this->db->_error_message()));
        }
    }
}