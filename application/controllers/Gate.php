<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gate extends CI_Controller {

	public function index()
	{
		// $type = $this->input->get('type');

		$common_link_daftar = 'http://45.118.112.232:81/form_pendaftaran/#/form/form-pendaftaran';
		$common_link_login = base_url().'auth';

		// $jenis_pajak = array('hotel' => array('title' => 'Pajak Hotel',
										// 'link_pendaftaran' => $common_link_daftar,
										// 'link_login' => $common_link_login),
						// 'restoran' => array('title' => 'Pajak Restoran',
										// 'link_pendaftaran' => $common_link_daftar,
										// 'link_login' => $common_link_login),
						// 'hiburan' => array('title' => 'Pajak Hiburan',
										// 'link_pendaftaran' => $common_link_daftar,
										// 'link_login' => $common_link_login),
						// 'penjal' => array('title' => 'Pajak Penerangan Jalan',
										// 'link_pendaftaran' => $common_link_daftar,
										// 'link_login' => $common_link_login),
						// 'reklame' => array('title' => 'Pajak Reklame',
										// 'link_pendaftaran' => "#",
										// 'link_login' => "#"),
						// 'parkir' => array('title' => 'Pajak Parkir',
										// 'link_pendaftaran' => $common_link_daftar,
										// 'link_login' => $common_link_login),
						// 'pat' => array('title' => 'Pajak Air Tanah',
										// 'link_pendaftaran' => "#",
										// 'link_login' => "#"),
						// 'bphatb' => array('title' => 'Pajak Bea Perolehan Hak Atas Tanah dan Bangunan',
										// 'link_pendaftaran' => "#",
										// 'link_login' => "http://ippat.disyanjak.net"),
						// 'pbb' => array('title' => 'Pajak Bumi dan Bangunan',
										// 'link_pendaftaran' => 'http://49.236.219.74/disyanjak-sipp/daftar',
										// 'link_login' => 'http://49.236.219.74/disyanjak-sipp/ijinmasuk')
					  // );
		// try {
			// if(!array_key_exists($type, $jenis_pajak)) {
				// throw new Exception('error');
			// }

			// $title = $jenis_pajak[$type]['title'];
			// $link_daftar = $jenis_pajak[$type]['link_pendaftaran'];
			// $link_login = $jenis_pajak[$type]['link_login'];
			
			// $this->load->view('gate');
			// $this->load->view('gate', array('title' => $title,
											// 'link_daftar' => $link_daftar,
											// 'link_login' => $link_login));

			$this->load->view('gate', array('link_daftar' => 'http://45.118.112.232:81/form_pendaftaran/#/form/form-pendaftaran',
											'link_login' => $common_link_login)
							);

		// }catch(Exception $e) {
			// $this->load->view('error_404');
		// }
	}
}