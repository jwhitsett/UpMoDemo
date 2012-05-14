<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upmodemo extends CI_Controller {

	public function index()
	{
		$this->load->view('upmodemo_view');
	}
	
	public function dbview()
	{
		$data['query'] = $this->db->get('upmodemo'); // upmodemo
		$this->load->view('db_view', $data);
	}
}

/* End of file upmodemo.php */
/* Location: ./application/controllers/upmodemo.php */