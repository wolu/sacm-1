<?php defined('BASEPATH') or exit('No direct script access allowed');
class Release_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    function setJsonRfc($rows, $offset, $sort, $order, $stat)
    {
        $this->db->join('teknisi_pelaksana', 'teknisi_pelaksana.NomorDokumen = rfc.NomorService','left');
        $this->db->where('RFCId !=', '');
        $this->db->where('TanggalTarget !=', '0000-00-00 00:00:00');
        $this->db->where('KodeStatus', $stat);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows']  = $this->db->get('rfc')->result();
        $jsonevents['total'] = $this->db->get('rfc')->num_rows();
        return json_encode($jsonevents);
    }
    function setDetilJsonRfc($rows, $offset, $sort, $order, $noRFC)
    {
        
        $this->db->where('NomorRFC', $noRFC);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows']  = $this->db->get('rfc_konfigurasi')->result();
        $jsonevents['total'] = $this->db->get('rfc_konfigurasi')->num_rows();
        return json_encode($jsonevents);
    }
    function updateStatusRfc($id, $data)
    {
        $this->db->where('NomorRFC', $id);
        $rfc = array(
            'NomorRFC' => $id,
            'KodeStatus' => $data['KodeStatus'],
            'Tanggal' => date('Y-m-d H:i:s'));

        $this->db->trans_begin();
        $this->db->update('rfc', $data);
        $this->db->insert('status_rfc', $rfc);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return json_encode(array('msg' => 'Terjadi Beberapa Kesalahan'));
        } else {
            $this->db->trans_commit();
            return json_encode(array('success' => 'Data Berhasil Ditambahkan'));
        }
    }
    function setJsonReq($rows, $offset, $sort, $order,$stat)
    {
        $this->db->where('KodeStatus', $stat);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows']  = $this->db->get('request')->result();
        $jsonevents['total'] = $this->db->get('request')->num_rows();
        return json_encode($jsonevents);
    }
    function setDetilJsonReq($rows, $offset, $sort, $order, $noRec)
    {
        $this->db->where('NomorRequest', $noRec);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows']  = $this->db->get('request_konfigurasi')->result();
        $jsonevents['total'] = $this->db->get('request_konfigurasi')->num_rows();
        return json_encode($jsonevents);
    }
    
    function updateStatusReq($id, $data)
    {
        $this->db->where('NomorRequest', $id);
        $rfc = array(
            'NomorRequest' => $id,
            'KodeStatus' => $data['KodeStatus'],
            'Tanggal' => date('Y-m-d H:i:s')
            );

        $this->db->trans_begin();
        $this->db->update('request', $data);
        $this->db->insert('status_request', $rfc);

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return json_encode(array('msg' => 'Terjadi Beberapa Kesalahan'));
        } else {
            $this->db->trans_commit();
            return json_encode(array('success' => 'Data Berhasil Ditambahkan'));
        }
    }
}