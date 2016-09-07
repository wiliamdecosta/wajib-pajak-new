<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Users_controller
* @version 07/05/2015 12:18:00
*/
class T_vat_settlement_controller {

    function read() {
		
		$page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str',' updated_date desc, trans_date');
        $sord = getVarClean('sord','str','asc');

        $p_vat_id = getVarClean('p_vat_id','str',32);        
        $start_period = getVarClean('start_period','str',32);        
        $end_period = getVarClean('end_period','str',32);        

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {
			
			
            $ci = & get_instance();
            $ci->load->model('transaksi/t_vat_settlement');
            $table= $ci->T_vat_settlement;
						
            $req_param = array(
                "sort_by" => $sidx,
                "sord" => $sord,
                "limit" => null,
                "field" => null,
                "where" => null,
                "where_in" => null,
                "where_not_in" => null,
                "search" => $_REQUEST['_search'],
                "search_field" => isset($_REQUEST['searchField']) ? $_REQUEST['searchField'] : null,
                "search_operator" => isset($_REQUEST['searchOper']) ? $_REQUEST['searchOper'] : null,
                "search_str" => isset($_REQUEST['searchString']) ? $_REQUEST['searchString'] : null
            );
			
			
            // Filter Table
            $req_param['where'] = array();
			
            $table->setJQGridParam($req_param);
			// $table = $tables->transaction_query;
			$table->setCriteria('p_vat_type_dtl_id = '. $p_vat_id);
			$table->setCriteria( " (trunc(trans_date) BETWEEN '".$start_period."' AND '".$end_period."') ");
			// $table->setCriteria('end_period = '. $end_period);
            $count = $table->countAll();

            if ($count > 0) $total_pages = ceil($count / $limit);
            else $total_pages = 1;

            if ($page > $total_pages) $page = $total_pages;
            $start = $limit * $page - ($limit); // do not put $limit*($page - 1)

            $req_param['limit'] = array(
                'start' => $start,
                'end' => $limit
            );

            $table->setJQGridParam($req_param);

            if ($page == 0) $data['page'] = 1;
            else $data['page'] = $page;

            $data['total'] = $total_pages;
            $data['records'] = $count;

            $data['rows'] = $table->getAll();
            $data['success'] = true;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return $data;
    }
	
	 public static function createSptpd($args = array()){
        $jsonItems = getVarClean('items', 'str', '');        
        $item = jsonDecode($jsonItems);   
	
		$ci = & get_instance();
		$ci->load->model('transaksi/t_vat_settlement');
		$table= $ci->t_vat_settlement;
               
        $items = $item[0];
        $data = array('items' => array(), 'total' => 0, 'success' => true, 'message' => '');
        try {
			// print_r($items);exit;			
            $user_name = $ci->session->userdata('user_name');
			// print_r($user_name);exit;
            if(empty($items['p_vat_type_dtl_cls_id'])){
                $items['p_vat_type_dtl_cls_id'] = 'null';
            };

            $sql = "select o_mess,o_pay_key,o_cust_order_id,o_vat_set_id from f_vat_settlement_manual_wp( ". $items['t_cust_accounts_id'] ." ,".$items['finance_period'].",'".$items['npwd']."','".$items['start_period']."','".$items['end_period']."',null,".$items['total_trans_amount'].",".$items['total_vat_amount'].",".$items['p_vat_type_dtl_id'].",".$items['p_vat_type_dtl_cls_id'].", '".$user_name."')";
			
            $messageq = $table->db->query($sql);
			$message = $messageq->result_array();
			// print_r($message);exit;
            $sql = "select * from f_get_penalty_amt(".$items['total_vat_amount'].",".$items['finance_period'].",".$items['p_vat_type_dtl_id'].");";
            $q = $ci->db->query($sql);
			$penalty = $q->row_array();
            if($message[0]['o_vat_set_id'] == null ||empty($message[0]['o_vat_set_id'])){
                $data['success'] = false;
            }else{
                $data['success'] = true;
				$params = json_encode(array(
											't_vat_setllement_id'=>$message[0]['o_vat_set_id'],
											't_customer_order_id'=>$message[0]['o_cust_order_id']
											));
				$_POST ['items']= $params;
				$data = submitSptpd($items);
            }
            $data['items'] = $message[0];
            $data['message'] = $message[0]['o_mess'];
            echo json_encode($data);
            exit;
        }
		catch(Exception $e)
		{
            $data['success'] = false;
            $data['message'] = $e->getMessage();
            echo json_encode($data);
            exit;
        }
    }
	
	public static function submitSptpd($args = array(), $items){
        // $jsonItems = getVarClean('items', 'str', '');        
        // $items = jsonDecode($jsonItems);
		
		$ci = & get_instance();
		$ci->load->model('transaksi/t_vat_settlement');
		$table= $ci->t_vat_settlement;
        $table->actionType = 'CREATE';
        
        //$items = $item['items'];
        $data = array('items' => array(), 'total' => 0, 'success' => true, 'message' => '');
        try 
		{
            $data['success'] = false;
            $user_name = $ci->session->userdata('user_name');
            								
                $sql = "select sikp.f_before_submit_sptpd_wp(".$items['t_vat_setllement_id'].",'".$user_name."')";
                $messageq = $table->db->query($sql);
				$message = $messageq->row_array();
				// $message=$table->dbconn->GetOne($sql);
                //if(trim($message)=='OK'){
				if(true){
                    $sql="select o_result_msg from sikp.f_first_submit_engine(501,".$items['t_customer_order_id'].",'".$user_name."')";   
                    $messageq = $table->db->query($sql);
					$message = $messageq->row_array();
					// $message=$table->dbconn->GetOne($sql);
                    if($message=='OK'){
                        $sql="select f_gen_vat_dtl_trans(".$items['t_vat_setllement_id'].",'".$user_name."')";   
						$messageq = $table->db->query($sql);
						$message = $messageq->result_array();
						// $message=$table->dbconn->GetItem($sql);
                    }
                    $data['success'] = true;
                }
            // }
            $data['items'] = $items;
            $data['msg']=$message;
            $data['message'] = $message;
            return $data;
        }catch(Exception $e) {
            $data['success'] = false;
            $data['message'] = $e->getMessage();
            return $data;   
        }
    }
	
	function read_acc_trans() {
		
		$sidx = getVarClean('sidx','str','p_vat_type_dtl_id');
        $sord = getVarClean('sord','str','desc');
	
		
        $p_vat_id = getVarClean('vat_type_dtl','int',0);        
        $start_period = getVarClean('start_period','str','');        
        $end_period = getVarClean('end_period','str','');            

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {
			
			
            $ci = & get_instance();
            $ci->load->model('transaksi/cust_acc_trans');
            $table= $ci->cust_acc_trans;
						
           // $table = $tables->transaction_query;
			$table->setCriteria('p_vat_type_dtl_id = '. $p_vat_id);
			$table->setCriteria( " (trunc(trans_date) BETWEEN '".$start_period."' AND '".$end_period."') ");
			///$table->setCriteria('end_period = '. $end_period);
			// $table->setCriteria(to_char(trans_date,'DD-MM-YYYY') ilike '%'. $tanggal .'%');
            $count = $table->countAll();

            $data['page'] = 1;
            $data['total'] = 1;
            $data['records'] = $count;

            $data['rows'] = $table->getAll(0, -1, $sidx, $sord);
            $data['success'] = true;

        }catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }

        echo json_encode($data);
		exit;
    }
	
	
    function crud() {

        $data = array();
        $oper = getVarClean('oper', 'str', '');
        switch ($oper) {
            case 'add' :
                $data = $this->create();
            break;

            case 'edit' :
                $data = $this->update();
            break;

            case 'del' :
                $data = $this->destroy();
            break;

            default:
                $data = $this->read();
            break;
        }

        return $data;
    }

    function create() {
		$user_name = getVarClean('user_name','str',32);
        $ci = & get_instance();
        $ci->load->model('transaksi/cust_acc_trans');
        $table = $ci->cust_acc_trans;

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);
		
		$t_cust_account_id 		= getVarClean('t_cust_account_id', 'int', 0);
        $p_vat_type_dtl_id 		= getVarClean('p_vat_type_dtl_id', 'int', 0);
		$p_vat_type_dtl_cls_id 	= getVarClean('p_vat_type_dtl_cls_id', 'int', 0);

        if (!is_array($items)){
            $data['message'] = 'Invalid items parameter';
            return $data;
        }

        $table->actionType = 'CREATE';
        $errors = array();

        if (isset($items[0])){
            $numItems = count($items);
            for($i=0; $i < $numItems; $i++){
                try{

                    $table->db->trans_begin(); //Begin Trans
					$date_only = explode('T', $items[$i]["trans_date"]); 

					
					$tgl_trans = empty($items[$i]["i_tgl_trans"]) ? $date_only[0] : $items[$i]["i_tgl_trans"];
					$bill_no = empty($items[$i]["i_bill_no"]) ? $items[$i]["bill_no"] : $items[$i]["i_bill_no"];
					$bill_no_end = empty($items[$i]["i_bill_no_end"]) ? $items[$i]["bill_no_end"] : $items[$i]["i_bill_no_end"];
					$bill_count = empty($items[$i]["i_bill_count"]) ? $items[$i]["bill_count"] : $items[$i]["i_bill_count"];
					$serve_desc = empty($items[$i]["i_serve_desc"]) ? $items[$i]["service_desc"] : $items[$i]["i_serve_desc"];
					$serve_charge = empty($items[$i]["i_serve_charge"]) ? $items[$i]["service_charge"] : $items[$i]["i_serve_charge"];
					$description = empty($items[$i]["i_description"]) ? $items[$i]["description"] : $items[$i]["i_description"];
                       $ci->db->query("select o_result_code, o_result_msg from \n" .
                       "f_ins_cust_acc_dtl_trans_v2(" . $items[$i]["t_cust_account_id"]. ",\n" .
                       "                         '" . $tgl_trans . "',\n" .
                       "                         '" . $bill_no. "',\n" .
                       "                         '" . $serve_desc. "',\n" .
                       "                         " . $serve_charge. ",\n" .
                       "                         null,\n" .
                       "                         '" . $description. "',\n" .
                       "                         '" . $session['user_name']. "',\n" .
                       "                         '" . $p_vat_type_dtl_id. "',\n" .
                       "                         case when " . $p_vat_type_dtl_cls_id. " = 0 then null else " . $p_vat_type_dtl_cls_id. " end,".
					"                         " . $bill_count. ",".
					"                         '" . $bill_no_end. "')");
					
					// $tr_id = $ci->db->GetOne("select last_value from t_cust_acc_dtl_trans_seq");
					// $query = "select to_char(trans_date,'yyyy-mm-dd') as trans_date,t_cust_acc_dtl_trans_id, t_cust_account_id, bill_no,bill_no_end,bill_count, service_desc, service_charge, vat_charge, description
					// from sikp.f_get_cust_acc_dtl_trans_v2(".$items[$i]["t_cust_account_id"].",'".$tgl_trans."')AS tbl (t_cust_acc_dtl_trans_id) where t_cust_acc_dtl_trans_id = ?";
                    $table->db->trans_commit(); //Commit Trans

                }catch(Exception $e){

                    $table->db->trans_rollback(); //Rollback Trans
                    $errors[] = $e->getMessage();
                }
            }

            $numErrors = count($errors);
            if ($numErrors > 0){
                $data['message'] = $numErrors." from ".$numItems." record(s) failed to be saved.<br/><br/><b>System Response:</b><br/>- ".implode("<br/>- ", $errors)."";
            }else{
                $data['success'] = true;
                $data['message'] = 'Data added successfully';
            }
            $data['rows'] =$items;
        }else {

            try{
                $table->db->trans_begin(); //Begin Trans

                    $table->setRecord($items);
                    $table->create();

                $table->db->trans_commit(); //Commit Trans

                $data['success'] = true;
                $data['message'] = 'Data added successfully';

            }catch (Exception $e) {
                $table->db->trans_rollback(); //Rollback Trans

                $data['message'] = $e->getMessage();
                $data['rows'] = $items;
            }

        }
        return $data;

    }

    function update() {

        $ci = & get_instance();
        $ci->load->model('pelaporan/pelaporan_bulan');
        $table = $ci->users;

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        if (!is_array($items)){
            $data['message'] = 'Invalid items parameter';
            return $data;
        }

        $table->actionType = 'UPDATE';

        if (isset($items[0])){
            $errors = array();
            $numItems = count($items);
            for($i=0; $i < $numItems; $i++){
                try{
                    $table->db->trans_begin(); //Begin Trans

                        $table->setRecord($items[$i]);
                        $table->update();

                    $table->db->trans_commit(); //Commit Trans

                    $items[$i] = $table->get($items[$i][$table->pkey]);
                }catch(Exception $e){
                    $table->db->trans_rollback(); //Rollback Trans

                    $errors[] = $e->getMessage();
                }
            }

            $numErrors = count($errors);
            if ($numErrors > 0){
                $data['message'] = $numErrors." from ".$numItems." record(s) failed to be saved.<br/><br/><b>System Response:</b><br/>- ".implode("<br/>- ", $errors)."";
            }else{
                $data['success'] = true;
                $data['message'] = 'Data update successfully';
            }
            $data['rows'] =$items;
        }else {

            try{
                $table->db->trans_begin(); //Begin Trans

                    $table->setRecord($items);
                    $table->update();

                $table->db->trans_commit(); //Commit Trans

                $data['success'] = true;
                $data['message'] = 'Data update successfully';

                $data['rows'] = $table->get($items[$table->pkey]);
            }catch (Exception $e) {
                $table->db->trans_rollback(); //Rollback Trans

                $data['message'] = $e->getMessage();
                $data['rows'] = $items;
            }

        }
        return $data;

    }

    function destroy() {
        $ci = & get_instance();
        $ci->load->model('pelaporan/pelaporan_bulan');
        $table = $ci->users;

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

        try{
            $table->db->trans_begin(); //Begin Trans

            $total = 0;
            if (is_array($items)){
                foreach ($items as $key => $value){
                    if (empty($value)) throw new Exception('Empty parameter');

                    $table->remove($value);
                    $data['rows'][] = array($table->pkey => $value);
                    $total++;
                }
            }else{
                $items = (int) $items;
                if (empty($items)){
                    throw new Exception('Empty parameter');
                }

                $table->remove($items);
                $data['rows'][] = array($table->pkey => $items);
                $data['total'] = $total = 1;
            }

            $data['success'] = true;
            $data['message'] = $total.' Data deleted successfully';

            $table->db->trans_commit(); //Commit Trans

        }catch (Exception $e) {
            $table->db->trans_rollback(); //Rollback Trans
            $data['message'] = $e->getMessage();
            $data['rows'] = array();
            $data['total'] = 0;
        }
        return $data;
    }

	public static function upload_excel($args = array()){
		$data = array('success' => false, 'message' => '');
		//memanggil perintah sql delete DSR
		$ci = & get_instance();
		$ci->load->model('transaksi/t_vat_settlement');
		$table= $ci->t_vat_settlement;
		
		$t_cust_account_id = $table->session->userdata('cust_account_id');
		$start_period = getVarClean('start_period','str','');
		$end_period = getVarClean('end_period','str','');
		$p_vat_type_dtl_id = getVarClean('p_vat_type_dtl_id','int','');
		$p_vat_type_dtl_cls_id = getVarClean('p_vat_type_dtl_id','int','');
		
		global $_FILES;
		try {
			
			$sql = "DELETE FROM t_cust_acc_dtl_trans a
					WHERE a.t_cust_account_id = ". $t_cust_account_id ."
					and not exists (select 1 
						from t_vat_setllement_dtl x 
							where x.t_cust_acc_dtl_trans_id = a.t_cust_acc_dtl_trans_id)";
			$result = $table->db->query($sql);
			
			if(empty($_FILES['excel_trans_cust']['name'])){
				throw new Exception('File tidak boleh kosong');
			}
			
			$file_name = $_FILES['excel_trans_cust']['name']; // <-- File Name
			$file_location = 'upload_excel/'.$file_name; // <-- LOKASI Upload File
		
			if (!move_uploaded_file($_FILES['excel_trans_cust']['tmp_name'], $file_location)){
				throw new Exception("Upload file gagal");
			}
			
			include('excel/reader.php');
			$xl_reader = new Spreadsheet_Excel_Reader();
			$res = $xl_reader->_ole->read($file_location);
		
			if($res === false) {
				if($xl_reader->_ole->error == 1) {
					echo "File Harus Format Excel";
					exit;
				}
			}

			$xl_reader->read($file_location);
			$firstColumn = $xl_reader->sheets[0]['cells'][1][2];
			
			$jumlah_hari = substr($end_period,8,2) - substr($start_period,8,2) + 1;
			$tahun_bulan = substr($start_period,0,8);			

			if ($jumlah_hari != ($xl_reader->sheets[0]['numRows']-3)) {
				throw new Exception("Laporan masa pajak anda ini tidak sesuai dengan Laporan Rekapitulasi Penerimaan Harian");
			};
			
			$items = array();	
			$loop_hari = 1;
			for($i = 3; $i < $xl_reader->sheets[0]['numRows']; $i++) {
				$temp_date = $tahun_bulan.sprintf("%02d", ($i-3+substr($start_period,8,2)));
				// print_r($temp_date);exit;
				if ($temp_date != $xl_reader->sheets[0]['cells'][$i][1]){					
					throw new Exception("Laporan masa pajak anda ini tidak sesuai dengan Laporan Rekapitulasi Penerimaan Harian");
				}
			
				if($loop_hari <= $jumlah_hari) {
					$item['t_cust_account_id'] = $t_cust_account_id; 
					$item['i_tgl_trans'] =  $xl_reader->sheets[0]['cells'][$i][1]; 	
					$bills = explode("-", $xl_reader->sheets[0]['cells'][$i][2]);
					$item['i_bill_no'] =  $bills[0];
					$item['i_bill_no_end'] =  $bills[1];
					$item['i_bill_count'] =  $xl_reader->sheets[0]['cells'][$i][3];
					$item['i_serve_desc'] =  '';
					$item['i_serve_charge'] =  $xl_reader->sheets[0]['cells'][$i][4];
					$i_vat_charge = $xl_reader->sheets[0]['cells'][$i][4];
					$item['i_vat_charge'] = "null";
					$item['i_desc'] = $xl_reader->sheets[0]['cells'][$i][5];   
					$item['p_vat_type_dtl_id'] = $p_vat_type_dtl_id;                
					$item['p_vat_type_dtl_cls_id'] = $p_vat_type_dtl_cls_id;                
					$items[] = $item;
					$loop_hari++;
				}
			}

			$numItems = count($items);
			for($i=0; $i < $numItems; $i++)
			{
				$table->db->trans_begin();
				
				$tgl_trans 		= $items[$i]["i_tgl_trans"];
				$bill_no 		= $items[$i]["i_bill_no"];
				$bill_no_end 	= $items[$i]["i_bill_no_end"];
				$bill_count 	= $items[$i]["i_bill_count"];
				$serve_desc 	= $items[$i]["i_serve_desc"];
				$serve_charge 	= $items[$i]["i_serve_charge"];
				$description 	= $items[$i]["i_desc"];
				
				$ci->db->query("select o_result_code, o_result_msg from \n" .
                      "f_ins_cust_acc_dtl_trans_v2(" . $items[$i]["t_cust_account_id"]. ",\n" .
                      "                         '" . $tgl_trans . "',\n" .
                      "                         '" . $bill_no. "',\n" .
                      "                         '" . $serve_desc. "',\n" .
                      "                         " . $serve_charge. ",\n" .
                      "                         null,\n" .
                      "                         '" . $description. "',\n" .
                      "                         '" . $ci->session->userdata('user_name'). "',\n" .
                      "                         '" . $p_vat_type_dtl_id. "',\n" .
                      "                         case when " . $p_vat_type_dtl_cls_id. " = 0 then null else " . $p_vat_type_dtl_cls_id. " end,".
				"                         " . $bill_count. ",".
				"                         '" . $bill_no_end. "')");
					
				$table->db->trans_commit(); 
			};
			
			$data['success'] = true;
			$data['message'] = 'Upload file transaksi berhasil dilakukan';
		}catch(Exception $e) {
			$data['success'] = false;
			$data['message'] = $e->getMessage();
		}
		
		echo json_encode($data);
		exit;
    }
}