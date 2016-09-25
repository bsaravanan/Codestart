<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Maindashboard extends CI_Controller {

	public function __construct()
	{
		parent::__construct ();
		$this->data=array();
	}

	public function index()
	{
		$this->load->view('includes/header');
		$data['title']='DASHBOARD';
		$this->load->view('maindashboard',$data);
		$this->load->view('includes/footer');
	}
}
