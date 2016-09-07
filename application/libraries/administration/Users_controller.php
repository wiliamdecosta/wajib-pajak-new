<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Users_controller
* @version 07/05/2015 12:18:00
*/
class Users_controller {

    function updateProfile() {

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');
        $user_id = getVarClean('user_id','int',0);
        $user_email = getVarClean('user_email','str','');
        $password = getVarClean('password','str','');
        $password_confirmation = getVarClean('password_confirmation','str','');

        try {
            $ci = & get_instance();
            $ci->load->model('administration/users');
            $table = $ci->users;

            if(empty($user_id)) throw new Exception('ID tidak boleh kosong');
            if(empty($user_email)) throw new Exception('Email tidak boleh kosong');

            $item = $table->get($user_id);
            if($item == null) throw new Exception('ID tidak ditemukan');

            $record = array();
            if(!empty($password)) {
                if(strlen($password) < 4) throw new Exception('Min.Password 4 Karakter');
                if($password != $password_confirmation) throw new Exception('Password tidak sesuai');

                $record['user_pwd'] = md5($password);
            }
            $record['email_address'] = $user_email;
            $record['p_app_user_id'] = $user_id;

            $table->actionType = 'UPDATE';
            $table->db->trans_begin(); //Begin Trans
                $table->setRecord($record);
                $table->update();
            $table->db->trans_commit(); //Commit Trans


            $data['success'] = true;
            $data['message'] = 'Data profile berhasil diupdate';
        }catch (Exception $e) {
            $table->db->trans_rollback(); //Rollback Trans
            $data['message'] = $e->getMessage();
        }

        return $data;
    }
}

/* End of file Users_controller.php */