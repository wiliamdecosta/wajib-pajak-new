<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function index() {

        if($this->session->userdata('logged_in')) {
            //go to default page
            redirect(base_url().'panel');
        }

        $data = array();
        $data['login_url'] = base_url()."auth/login";

		$this->load->view('auth/login', $data);
	}

    public function login() {
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));

        if(empty($username) or empty($password)) {
            $this->session->set_flashdata('error_message','Username atau password harus diisi');
            redirect(base_url().'auth');
        }

        /*$sql = "select p_app_user_id as user_id,
                    app_user_name as user_name,
                    user_pwd as user_password,
                    email_address as user_email,
                    full_name as user_realname,
                    p_user_status_id as user_status
                    from sikp.p_app_user where app_user_name = ?";*/
					
		$sql = "SELECT a.t_customer_user_id as user_id, 
					a.user_name, 
					a.user_pwd as user_password,
					b.email_address as user_email,
					c.company_brand as user_realname,
					a.p_user_status_id as user_status 
                    from sikp.t_customer_user a 
					join t_customer b on b.t_customer_id = a.t_customer_id
					join t_cust_account c on c.t_customer_id = a.t_customer_id
					join p_app_user d on a.p_app_user_id = d.p_app_user_id
					where d.app_user_name = ?";								

        $query = $this->db->query($sql, array($username));
        $row = $query->row_array();

        $md5pass = md5(trim($password));

        if( strcmp($md5pass, trim($row['user_password'])) != 0 ) {
            $this->session->set_flashdata('error_message','Username atau password Anda salah');
            redirect(base_url().'auth');
        }

        if($row['user_status'] != 1) {
            $this->session->set_flashdata('error_message','Maaf, User yang bersangkutan sudah tidak aktif. Silahkan hubungi administrator.');
            redirect(base_url().'auth');
        }

		$sql = "select * from sikp.f_get_npwd_by_username('".$row['user_name']."')";

        $query = $this->db->query($sql);
        $row2 = $query->row_array();

        $userdata = array(
                        'user_id'           => $row['user_id'],
                        'user_name'         => $row['user_name'],
                        'user_email'        => $row['user_email'],
                        'user_realname'     => $row['user_realname'],
                        'cust_account_id'  	=> $row2['t_cust_account_id'],
                        'npwd'     			=> $row2['npwd'],
                        'company_name'     => $row2['company_name'],
                        'vat_type_dtl'     => $row2['p_vat_type_dtl_id'],
                        'logged_in'         => true,
                      );

        $this->session->set_userdata($userdata);
        redirect(base_url().'panel');

    }

    public function logout() {

        $userdata = array(
                        'user_id'           => '',
                        'user_name'         => '',
                        'user_email'        => '',
                        'user_realname'     => '',
                        'cust_account_id'   => '',
                        'npwd'     			=> '',
                        'company_name'     	=> '',
                        'logged_in'         => false,
                      );

        $this->session->unset_userdata($userdata);
        $this->session->sess_destroy();
        // redirect(base_url().'home');
		redirect('http://disyanjak.bandung.go.id');

    }

}
