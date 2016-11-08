<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Users_controller
* @version 07/05/2015 12:18:00
*/
class Cust_acc_trans_controller {

    function read() {
		
		$page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str',' updated_date desc, trans_date');
        $sord = getVarClean('sord','str','');

        $p_vat_id = getVarClean('p_vat_id','str',32);        
        $start_period = getVarClean('start_period','str',32);        
        $end_period = getVarClean('end_period','str',32);        

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {
			
			
            $ci = & get_instance();
            $ci->load->model('transaksi/cust_acc_trans');
            $table= $ci->cust_acc_trans;
						
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
	
	
	function read_acc_trans() {
		$ci = & get_instance();
		$sidx = getVarClean('sidx','str','t_cust_acc_dtl_trans_id');
        $sord = getVarClean('sord','str','desc');
	
		
        $p_vat_type_dtl_id = getVarClean('p_vat_type_dtl_id','int',$ci->session->userdata('vat_type_dtl'));        
        $start_period = getVarClean('start_period','str','');        
        $end_period = getVarClean('end_period','str','');            

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {
			
			
            
            $ci->load->model('transaksi/cust_acc_trans');
            $table= $ci->cust_acc_trans;
						
           // $table = $tables->transaction_query;
			$table->setCriteria('p_vat_type_dtl_id = '. $p_vat_type_dtl_id);
			$table->setCriteria( " (trunc(trans_date) BETWEEN '".$start_period."' AND '".$end_period."') ");
			if(empty($trans_date)){
        	    $trans_date = 'null';
        	}else{
        	    $trans_date = "'".$trans_date."'";
        	}
			$query = "select to_char(trans_date,'yyyy-mm-dd') as trans_date, to_char(trans_date,'dd-mm-yyyy') as trans_date_jqgrid,t_cust_acc_dtl_trans_id, t_cust_account_id, bill_no,bill_no_end,bill_count, service_desc, service_charge, vat_charge, tbl.description,p_vat_type_dtl_id,p_finance_period_id
                      from sikp.f_get_cust_acc_dtl_trans_v2(". $ci->session->userdata('cust_account_id') .",$trans_date)AS tbl (t_cust_acc_dtl_trans_id) 
                      left join p_finance_period on p_finance_period.start_date <= trans_date and p_finance_period.end_date >= trans_date
                      ".$table->getCriteriaSQL()." ORDER BY ". $sidx ." ". $sord;
			$temp_row = $table->db->query($query);
			// print_r($query);
		    $items_from_db = $temp_row->result_array();
			// $querycount = "SELECT COUNT(1) as total from sikp.f_get_cust_acc_dtl_trans_v2(". $ci->session->userdata('cust_account_id') .",$trans_date) ".$table->getCriteriaSQL();
            // $countitemsq = $table->db->query($querycount);
			// $countitems = $countitemsq->row_array();
			
			$items = array();
			for ($i = 0 ; $i < substr($end_period,8,2);$i++){
				$item_transdate = date('Y-m-d',strtotime("+".$i." day",strtotime(substr($start_period,0,10))));
				$items[$i] = array('trans_date' => $item_transdate, 
						't_cust_acc_dtl_trans_id' => '', 't_cust_account_id' => $ci->session->userdata('cust_account_id'), 'bill_no' => '',
						'bill_no_end' => '','bill_count' => '',
						'service_desc' => '','service_charge' => '','vat_charge' => '','description' => '',
						'p_vat_type_dtl_id' => $p_vat_type_dtl_id,'p_finance_period_id' => '');
				
				
				foreach($items_from_db as $item){
					if($item_transdate == $item['trans_date']){
						$items[$i] = $item;
					}
				}
			}
			

            $data['page'] = 1;
            $data['total'] = 1;
            $data['records'] = count($items);

            $data['rows'] = $items;
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

    function create_data() {
		$data = array( 'success' => false, 'message' => '', 'total' =>0);
        $ci = & get_instance();
        $ci->load->model('transaksi/t_vat_settlement');
        $table = $ci->t_vat_settlement;

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);
		
		$t_cust_account_id 		= $table->session->userdata('cust_account_id');
        $p_vat_type_dtl_id 		= getVarClean('p_vat_type_dtl_id', 'int', 0);
		$p_vat_type_dtl_cls_id 	= getVarClean('p_vat_type_dtl_cls_id', 'int', 0);

        if (!is_array($items)){
            $data['message'] = 'Invalid items parameter';
            return $data;
        }
		
        $errors = array();
		try{
			
		$sql = "DELETE FROM t_cust_acc_dtl_trans a
			WHERE a.t_cust_account_id = ". $t_cust_account_id ."
			and not exists (select 1 
				from t_vat_setllement_dtl x 
					where x.t_cust_acc_dtl_trans_id = a.t_cust_acc_dtl_trans_id)";
		$result = $table->db->query($sql);
        if (isset($items[0])){
            $numItems = count($items);
			$total = 0;
            for($i=0; $i < $numItems; $i++)
				{
				
					$table->db->trans_begin();
				
					$dates_format = date('Y-m-d',strtotime($items[$i]["trans_date"]));
					$date_t = $dates_format ."T00:00:00";
					$date_only = explode('T', $date_t);

					$tgl_trans = empty($items[$i]["i_tgl_trans"]) ? $date_only [0] : $items[$i]["i_tgl_trans"];
					$tgl_trans = date("Y-m-d", strtotime($tgl_trans));
					$bill_no = empty($items[$i]["i_bill_no"]) ? $items[$i]["bill_no"] : $items[$i]["i_bill_no"];
					$bill_no_end = empty($items[$i]["i_bill_no_end"]) ? $items[$i]["bill_no_end"] : $items[$i]["i_bill_no_end"];
					$bill_count = empty($items[$i]["i_bill_count"]) ? $items[$i]["bill_count"] : $items[$i]["i_bill_count"];
					$serve_desc = empty($items[$i]["i_serve_desc"]) ? $items[$i]["service_desc"] : $items[$i]["i_serve_desc"];
					$serve_charge = empty($items[$i]["i_serve_charge"]) ? $items[$i]["service_charge"] : $items[$i]["i_serve_charge"];
					$description = empty($items[$i]["i_description"]) ? $items[$i]["description"] : $items[$i]["i_description"];
                       
					$message = $ci->db->query("select o_result_code, o_result_msg from \n" .
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
					$mess = $message->row_array();
					print_r($mess);
					
					$total++;
                    $table->db->trans_commit();
                }
            }

            $numErrors = count($errors);
            if ($numErrors > 0){
                $data['message'] = $numErrors." from ".$numItems." record(s) failed to be saved.<br/><br/><b>System Response:</b><br/>- ".implode("<br/>- ", $errors)."";				
			}else{
                $data['success'] = true;
                $data['message'] = 'Data added successfully submitted';
                $data['total'] = $total;				
            }
        }catch(Exception $e)
		{
			$data['success'] = false;
			$data['message'] = $e->getMessage();			
		}
		echo json_encode($data);
		exit;

    }

    function update_data() {

        $ci = & get_instance();
        $ci->load->model('transaksi/cust_acc_trans');		
        $table = $ci->cust_acc_trans;

        $data = array('success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);
		$t_cust_account_id 	= getVarClean('t_cust_account_id', 'int', 0);
		$p_vat_type_dtl_cls_id 	= getVarClean('p_vat_type_dtl_cls_id', 'int', 0);
		$p_vat_type_dtl_id 		= getVarClean('p_vat_type_dtl_id', 'int', 0);
		//var_dump($items);exit;
        if (!is_array($items)){
            $data['message'] = 'Invalid items parameter';
            return $data;
        }

        $table->actionType = 'UPDATE';

        if (isset($items[0])){
			$date_only = explode('T', $items[0]["trans_date"]);
			$queryDate = date("Y-m-d", strtotime($date_only[0]));
            $errors = array();
			$numSaved = 0;
            $numItems = count($items);
			$savedItems = array();
            for($i=0; $i < $numItems; $i++){
                try{
					// print_r($items[0]);
                    $ci->db->trans_begin(); //Begin Trans
						
                        $table->setRecord($items[$i]);
                        $table->update();
						$numSaved++;
                    $ci->db->trans_commit(); //Commit Trans
					
                    $items[$i] = $table->get($items[$i][$table->pkey]);
					// print_r($items);exit;
                }catch(Exception $e){
                    $table->db->trans_rollback(); //Rollback Trans

                    $errors[] = $e->getMessage();
                }
				// print_r($items);exit;
				$query = "select to_char(trans_date,'yyyy-mm-dd') as trans_date,t_cust_acc_dtl_trans_id, t_cust_account_id, bill_no,bill_no_end,bill_count, service_desc, service_charge, vat_charge, description
                      from sikp.f_get_cust_acc_dtl_trans_v2(".$t_cust_account_id.",'". $queryDate ."')AS tbl (t_cust_acc_dtl_trans_id) where t_cust_acc_dtl_trans_id = ". $items[$i]['t_cust_acc_dtl_trans_id'];
					  // print_r(array($items[$i]['t_cust_acc_dtl_trans_id']));exit;
				// print_r($query);exit;
				$temp_row = $ci->db->query($query);
				$items[$i] = $temp_row->row_array();
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
			$query = "select to_char(trans_date,'yyyy-mm-dd') as trans_date,t_cust_acc_dtl_trans_id, t_cust_account_id, bill_no,bill_no_end,bill_count, service_desc, service_charge, vat_charge, description
                      from sikp.f_get_cust_acc_dtl_trans_v2(".$t_cust_account_id.",'".$date_only[0]."')AS tbl (t_cust_acc_dtl_trans_id) where t_cust_acc_dtl_trans_id = ?";
    	   $temp_row = $table->db->query($query,array($items[$i]['t_cust_acc_dtl_trans_id']));
		   $items[$i] = $temp_row->row_array();
		   // $data['items'] = $table->dbquery->GetItem($query,array($items['t_cust_acc_dtl_trans_id']));
        }
        return $data;

    }

    function destroy() {
        $ci = & get_instance();
        $ci->load->model('transaksi/cust_acc_trans');
        $table = $ci->cust_acc_trans;

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

	function getCustAccMonth(){
		
		$page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str','t_cust_account_id');
        $sord = getVarClean('sord','str','asc');

		
		$user_name = $this->session->userdata('user_name');    

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {

            $ci = & get_instance();
            $ci->load->model('transaksi/transaksi_harian');
            $table= $ci->transaksi_harian;
			
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
}