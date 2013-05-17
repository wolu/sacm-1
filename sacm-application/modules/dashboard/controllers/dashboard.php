<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends CI_Controller {
	public function index()
	{
		$this->template->load('frontend','view_dashboard');
	}
}
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */