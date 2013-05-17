<?php defined('BASEPATH') or exit('No direct script access allowed');
class Master_config_model extends CI_Model
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
        $jsonevents['rows']  = $this->db->get('konfigurasi_master')->result();
        $jsonevents['total'] = $this->db->get('konfigurasi_master')->num_rows();
        return json_encode($jsonevents);
    }
    function getJsonDetil($rows, $offset, $sort, $order)
    {
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows']  = $this->db->get('konfigurasi_master_detil')->result();
        $jsonevents['total'] = $this->db->get('konfigurasi_master_detil')->num_rows();
        return json_encode($jsonevents);
    }
    function insert($data)
    {
        $result = $this->db->insert('konfigurasi_master', $data);
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil di Tambahkan'));
        } else {
            return json_encode(array('msg' =>$this->db->_error_message()));
        }
    }
    function insertDet($data)
    {
        $result = $this->db->insert('konfigurasi_master_detil', $data);
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil di Tambahkan'));
        } else {
            return json_encode(array('msg' =>$this->db->_error_message()));
        }
    }
    function update($id, $data)
    {
        $this->db->where('KodeKonfigurasi', $id);
        $result = $this->db->update('konfigurasi_master', $data);
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Diupdate'));
        } else {
            return json_encode(array('msg' =>$this->db->_error_message()));
        }
    }
    function updateDet($id, $data)
    {
        $this->db->where('KodeKonfig', $id);
        $result = $this->db->update('konfigurasi_master_detil', $data);
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Diupdate'));
        } else {
            return json_encode(array('msg' =>$this->db->_error_message()));
        }
    }
    function delete($id)
    {
        $this->db->where('KodeKonfigurasi', $id);
        $result = $this->db->delete('konfigurasi_master');
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Dihapus'));
        } else {
            return json_encode(array('msg' =>$this->db->_error_message()));
        }
    }
    function deleteDet($id)
    {
        $this->db->where('KodeKonfig', $id);
        $result = $this->db->delete('konfigurasi_master_detil');
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Dihapus'));
        } else {
            return json_encode(array('msg' =>$this->db->_error_message()));
        }
    }
}