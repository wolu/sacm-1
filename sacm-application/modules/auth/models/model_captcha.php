<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
class Model_captcha extends CI_Model {
        function __construct(){
          parent::__construct();
 }
        function setCaptcha()
        {
          /***************************Captcha************************************/
            $configs = array(
            'img_url' => base_url().'sacm-assets/captcha/',
            'img_height' => '40',
            'expiration' => '3600');
        // membuat captcha image
        // memasukan image html ke variable
        // $this->data['captcha'] = $cap['image'];
        // set captcha dalam session
        // $this->session->set_userdata('mycaptcha', $cap['word']);
        /***************************************************************/
          $cap = $this->antispam->get_antispam_image($configs);
          if ($cap)
          {
                  $capdb = array(
                   'captcha_id'       => '',
                   'captcha_time'     => $cap['time'],
                   'ip_address'       => $this->input->ip_address(),
                   'word'             => $cap['word']
                  );
                  $query = $this->db->insert_string('captcha', $capdb);
                  $this->db->query($query);
          }else {
                return "Captcha not work" ;
                }
                        //$data['cap'] = $cap;     
          return $cap['image'];
 }   
}