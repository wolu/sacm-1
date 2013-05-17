<?php
class Service_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    function setJson($rows, $offset, $sort, $order, $cari)
    {
        $this->db->like('CONCAT(NomorService,Peminta, CC,  NomorSurat, TanggalSurat, PenanggungJawab, Pemakai,TeleponPemakai, KontakPerson,TeleponKontak, LokasiPasang, TanggalInput, KodeService, KodeStatus,KodeStatus,IncidentId,TanggalPemasangan,NomorRequest, NomorItemService )', $cari);
        $this->db->order_by($sort, $order);
        $this->db->limit($rows, $offset);
        $jsonevents = array();
        $jsonevents['rows']  = $this->db->get('service')->result();
        $jsonevents['total'] = $this->db->get('service')->num_rows();
        $this->output->set_output(json_encode($jsonevents));
    }
    
    function insert() {
           $data = array(
           'NomorService'       => $this->input->post('NomorService', TRUE),
            'Peminta'           => $this->input->post('Peminta', TRUE),
            'CC'                => $this->input->post('CC', TRUE),
            'NomorSurat'        => $this->input->post('NomorSurat', TRUE),
            'TanggalSurat'      => $this->input->post('TanggalSurat', TRUE),
            'PenanggungJawab'   => $this->input->post('PenanggungJawab', TRUE),
            'Pemakai'           => $this->input->post('Pemakai', TRUE),
            'TeleponPemakai'    => $this->input->post('TeleponPemakai', TRUE),
            'KontakPerson'      => $this->input->post('KontakPerson', TRUE),
            'TeleponKontak'     => $this->input->post('TeleponKontak', TRUE),
            'LokasiPasang'      => $this->input->post('LokasiPasang', TRUE),
            'TanggalInput'      => $this->input->post('TanggalInput', TRUE),
            'KodeService'       => $this->input->post('KodeService', TRUE),
            'KodeStatus'        => $this->input->post('KodeStatus', TRUE),
            'IncidentId'        => $this->input->post('IncidentId', TRUE),
            'TanggalPemasangan' => $this->input->post('TanggalPemasangan', TRUE),
            'NomorRequest'      => $this->input->post('NomorRequest', TRUE),
            'NomorItemService'  => $this->input->post('NomorItemService', TRUE),
        );
        $this->db->insert('service', $data);
    }
    function update($id) {
        $data = array(
       'Peminta' => $this->input->post('Peminta', TRUE),
       'CC' => $this->input->post('CC', TRUE),
       'NomorSurat' => $this->input->post('NomorSurat', TRUE),
       'TanggalSurat' => $this->input->post('TanggalSurat', TRUE),
       'PenanggungJawab' => $this->input->post('PenanggungJawab', TRUE),
       'Pemakai' => $this->input->post('Pemakai', TRUE),
       'TeleponPemakai' => $this->input->post('TeleponPemakai', TRUE),
       'KontakPerson' => $this->input->post('KontakPerson', TRUE),
       'TeleponKontak' => $this->input->post('TeleponKontak', TRUE),
       'LokasiPasang' => $this->input->post('LokasiPasang', TRUE),
       'TanggalInput' => $this->input->post('TanggalInput', TRUE),
       'KodeService' => $this->input->post('KodeService', TRUE),
       'KodeStatus' => $this->input->post('KodeStatus', TRUE),
       'IncidentId' => $this->input->post('IncidentId', TRUE),
       'TanggalPemasangan' => $this->input->post('TanggalPemasangan', TRUE),
       'NomorRequest' => $this->input->post('NomorRequest', TRUE),
       'NomorItemService' => $this->input->post('NomorItemService', TRUE),
        );
        $this->db->where('NomorService', $id);
        $this->db->update('service', $data);
    }
    function delete($id) {
        foreach ($id as $sip) {
            $this->db->where('NomorService', $sip);
            $this->db->delete('service');
        }
    }
}
?>