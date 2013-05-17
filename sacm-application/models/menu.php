<?php
class Menu extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function get_data($induk = 0)
    {
        $data = array();
        $this->db->from('sys_menu');
        $this->db->where('parentnode', $induk);
        $result = $this->db->get();
        $tree = array();
        foreach ($result->result() as $row) {
            $data[]= array(
                'id' => $row->node,
                'nama' => $row->displaytext,
                'child' => $this->get_data($row->node));
        }
        $tree['tree'] = $data;
        return json_encode($tree);
    }
}
