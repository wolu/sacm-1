<?php defined('BASEPATH') or exit('No direct script access allowed');
class Rfc_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    //menampilkan tabel rfc 
    function setJson($rows, $offset, $sort, $order, $stat)
    {
        $this->db->where('RFCId', '');
        $this->db->where('KodeStatus', $stat);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows']  = $this->db->get('rfc')->result();
        $jsonevents['total'] = $this->db->get('rfc')->num_rows();
        return json_encode($jsonevents);
    }
    
    function setDetilJson($rows, $offset, $sort, $order, $noRFC)
    {
        $this->db->where('NomorRFC', $noRFC);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows']  = $this->db->get('rfc_konfigurasi')->result();
        $jsonevents['total'] = $this->db->get('rfc_konfigurasi')->num_rows();
        return json_encode($jsonevents);
    }
    function setJsonConfigDet($rows, $offset, $sort, $order, $cek)
    {
        $this->db->where('KodeKonfig', $cek);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows'] = $this->db->get('konfigurasi_master_detil')->result();
        $jsonevents['total'] = $this->db->get('konfigurasi_master_detil')->num_rows();
        return json_encode($jsonevents);
    }
    function insert($data)
    {
        //VALIDASI TANGGAL RFC TIDAK BOLEH LEBIH BESAR DARI TANGGAL INPUT
        if (strtotime($data['TanggalRFC']) > strtotime($data['TanggalInput'])) {
            return json_encode(array('msg' => 'Tanggal RFC > Tanggal Input'));
        }
        //VALIDASI NOMOR RFC
        if ($data['NomorRFC'] == '') {
            return json_encode(array('msg' => 'Nomor RFC Tidak Boleh Kosong'));
        }
        if ($data['NomorService'] == '') {
            return json_encode(array('msg' => 'Nomor Service Tidak Boleh Kosong'));
        }
        if ($data['SerialNumber'] == '') {
            return json_encode(array('msg' => 'Serial Number Tidak Boleh Kosong'));
        }
        if ($data['CodeChange'] == '') {
            return json_encode(array('msg' => 'Type Change Tidak Boleh Kosong'));
        }
        if ($this->tipe_change->getById($data['CodeChange']) == false) {
            return json_encode(array('msg' => 'Type Change Tidak Ada Dalam Daftar'));
        }

        $this->db->trans_begin();
        $this->db->insert('rfc', $data);
        $this->db->insert('status_rfc', array(
            'NomorRFC' => $data['NomorRFC'],
            'KodeStatus' => $data['KodeStatus'],
            'Tanggal' => date('Y-m-d H:i:s')));
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return json_encode(array('msg' => 'Terjadi Beberapa Kesalahan<br />' . $this->
                    db->_error_message()));
        } else {
            $this->db->trans_commit();
            return json_encode(array('success' => 'Data Berhasil Ditambahkan'));
        }
    }
    function insertConfig($data)
    {
        $result = $this->db->insert('rfc_konfigurasi', $data);
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Ditambahkan'));
        } else {
            return json_encode(array('msg' => $this->db->_error_message()));
        }
    }
    function getConfigRfc()
    {
        $this->db->get('konfigurasi_master');
    }
    function update($id, $data)
    {
        $this->db->where('NomorRFC', $id);
        $result = $this->db->update('rfc', $data);
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Diupdate'));
        } else {
            return json_encode(array('msg' => $this->db->_error_message()));
        }
    }
    function updateStatus($id, $data)
    {
        $this->db->trans_begin();
        $this->db->update('rfc', $data, array('NomorRFC' => $id));
        $this->db->insert('status_rfc', array(
            'NomorRFC' => $id,
            'KodeStatus' => $data['KodeStatus'],
            'Tanggal' => date('Y-m-d H:i:s')));

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return json_encode(array('msg' => 'Terjadi Bebeapa Kesalahan'));
        } else {
            $this->db->trans_commit();
            return json_encode(array('success' => 'Data Berhasil Ditambahkan'));
        }
    }
    function updateSnSerial($data)
    {
        $this->db->trans_begin();
        
        $this->db->update('rfc', array(
            'SerialNumber' => $data['SerialAsset'],
            'KodeStatus'   => 'OC',
            ), array('NomorRFC' => $data['NomorRFC']));

        $this->db->update('asset', array(
                'KodeAlokasi'  => 'OS', ), array(
                'SerialNumber' => $data['SerialAsset'])
                );

        $this->db->update('asset', array(
        'KodeAlokasi'  => $data['KodeAlokasi']), array(
        'SerialNumber' => $data['SerialNumber'])
        );

        $this->db->update('service', array(
        'NomorItemService' => $data['SerialAsset']),array(
        'NomorService'     => $data['NomorService'])
        );
        
        /*$this->db->insert('status_service', array(
                    'NomorService' => $data['SerialAsset'],
                    'KodeStatus'=>'OS',
                    'Tanggal'=>date('Y-m-d H:i:s')));*/

        $this->db->insert('status_rfc', array(
            'NomorRFC' => $data['NomorRFC'],
            'KodeStatus' => 'OC',
            'Tanggal' => date('Y-m-d H:i:s')));

        $this->db->insert('asset_alokasi', array(
            'NomorAsal'    => 'AA' . $data['NomorAsal'],
            'SerialNumber' => $data['SerialNumber'],
            'KodeAlokasi'  => $data['KodeAlokasi'],
            'Tanggal'      => date('Y-m-d H:i:s'),
            'NomorService' => $data['NomorService'],
            ));

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
        $this->db->where('NomorRFC', $id);
        $result = $this->db->delete('rfc');
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Dihapus'));
        } else {
            return json_encode(array('msg' => $this->db->_error_message()));
        }
    }
}
?>