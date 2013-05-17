<?php defined('BASEPATH') or exit('No direct script access allowed');
class Request_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function setJson($rows, $offset, $sort, $order, $stat)
    {
        $this->db->where('KodeStatus', $stat);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows']  = $this->db->get('request')->result();
        $jsonevents['total'] = $this->db->get('request')->num_rows();
        return json_encode($jsonevents);
    }
    
    function setDetilJson($rows, $offset, $sort, $order, $noRec)
    {
        $this->db->where('NomorRequest', $noRec);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows']  = $this->db->get('request_konfigurasi')->result();
        $jsonevents['total'] = $this->db->get('request_konfigurasi')->num_rows();
        return json_encode($jsonevents);
    }
    function insert($data)
    {
        if (strtotime($data['TanggalSurat']) > strtotime($data['TanggalInput'])) {
            return json_encode(array('msg' => 'Tanggal Surat > Tanggal Input'));
        }
        if ($data['Peminta'] == '') {
            return json_encode(array('msg' => 'Peminta Tidak Boleh Kosong'));
        }
      /*  if ($data['CC'] == '') {
            return json_encode(array('msg' => 'Cost Center Tidak Boleh Kosong'));
        }*/
        
        $req = array(
        'NomorRequest' => $data['NomorRequest'],
        'KodeStatus'   => $data['KodeStatus'],
        'Tanggal'      => $data['TanggalInput']
        );
        $this->db->trans_begin();
        $this->db->insert('request', $data);
        $this->db->insert('status_request', $req);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return json_encode(array('msg' => 'Terjadi Beberapa Kesalahan<br />'.$this->db->_error_message()));
        } else {
            $this->db->trans_commit();
            return json_encode(array('success' => 'Data Berhasil Ditambahkan'));
        }
    }
    function insertConfig($data)
    {
        $result = $this->db->insert('request_konfigurasi', $data);
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Ditambahkan'));
        } else {
            return json_encode(array('msg' => $this->db->_error_message()));
        }
    }
    function update($id, $data)
    {
        $this->db->where('NomorRequest', $id);
        $result = $this->db->update('request', $data);
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Diupdate'));
        } else {
            return json_encode(array('msg' => $this->db->_error_message()));
        }
    }
    
    function updateStatus($id, $data)
    {
        $this->db->where('NomorRequest', $id);
        $req = array(
            'NomorRequest' => $id,
            'KodeStatus' => $data['KodeStatus'],
            'Tanggal' => date('Y-m-d H:i:s')
            );

            $this->db->trans_begin();
            $this->db->update('request', $data);
            $this->db->insert('status_request', $req);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return json_encode(array('msg' => 'Terjadi Beberapa Kesalahan'));
        } else {
            $this->db->trans_commit();
            return json_encode(array('success' => 'Data Berhasil Ditambahkan'));
        }
    }
    function delete($id)
    {
        $this->db->where('NomorRequest', $id);
        $result = $this->db->delete('request');
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Dihapus'));
        } else {
            return json_encode(array('msg' => $this->db->_error_message()));
        }
    }
}
