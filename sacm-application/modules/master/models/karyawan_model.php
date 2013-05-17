<?php  defined('BASEPATH') or exit('No direct script access allowed');
class Karyawan_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    //menampilkan data dari tabel karyawan menggunakan format json
    function setJson($rows, $offset, $sort, $order, $cari)
    {
        $this->db->like('CONCAT(
                                karyawan_nik, karyawan_nama, karyawan_unit, cc, cc_nama, karyawan_kota 
                                )', $cari);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows']  = $this->db->get('v_karyawan')->result();
        $jsonevents['total'] = $this->db->count_all_results('v_karyawan');
        return json_encode($jsonevents);
    }
    
    //menamp
    function get_all(){
        return $this->db->get('karyawan');
    }
    //Insert data ke tabel karyawan
    function insert($data)
    {
        $result = $this->db->insert('karyawan', $data);
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil di Tambahkan'));
        } else {
            return json_encode(array('msg' =>$this->db->_error_message()));
        }
    }
    function update($id, $data)
    {
        $this->db->where('karyawan_nik', $id);
        $result = $this->db->update('karyawan', $data);
        if ($result) {
            return json_encode(array('success' => 'Data Berhasil Diupdate'));
        } else {
            return json_encode(array('msg' =>$this->db->_error_message()));
        }
    }
    function delete($id)
    {
        $this->db->where('karyawan_nik', $id);
        $result = $this->db->delete('karyawan');
        if ($result) {
         return json_encode(array('success' => 'Data Berhasil Dihapus'));
        } else {
         return json_encode(array('msg' =>$this->db->_error_message()));
        }
    }
}