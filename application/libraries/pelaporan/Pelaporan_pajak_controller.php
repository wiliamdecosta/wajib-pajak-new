<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Json library
* @class Users_controller
* @version 07/05/2015 12:18:00
*/
class Pelaporan_pajak_controller {

    function read() {

        $user_name = getVarClean('user_name','str',32);        

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        try {

            $ci = & get_instance();
            $ci->load->model('transaksi/transaksi_harian');
            $table= $ci->transaksi_harian;
			// $table = $tables->transaction_query;

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
	function daily_transaction(){
		$ci = & get_instance();
		$ci->load->model('customer/search_customer');
		$table = $ci->search_customer;

		$reftype = getVarClean('user_name','str','');
		$sql = "select t_cust_account_id,npwd from sikp.f_get_npwd_by_username('". $user_name ."')";
		$query = $this->db->query($sql);
		
	}
	
    function crud() {

        $data = array();
        $oper = getVarClean('oper', 'str', '');
        switch ($oper) {
            case 'add' :
                // permission_check('add-user');
                $data = $this->create();
            break;

            case 'edit' :
                // permission_check('edit-user');
                $data = $this->update();
            break;

            case 'del' :
                // permission_check('delete-user');
                $data = $this->destroy();
            break;

            default:
                // permission_check('view-user');
                $data = $this->read();
            break;
        }

        return $data;
    }


    function create() {
		$user_name = getVarClean('user_name','str',32);
        $ci = & get_instance();
        $ci->load->model('transaksi/transaksi_harian');
        $table = $ci->users;

        $data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');

        $jsonItems = getVarClean('items', 'str', '');
        $items = jsonDecode($jsonItems);

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

                        $table->setRecord($items[$i]);
                        $table->create();

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
        $ci->load->model('transaksi/transaksi_harian');
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
        $ci->load->model('transaksi/transaksi_harian');
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

    function html_select_options_status() {
        try {
            echo '<select>';
            echo '<option value="1"> Active </option>';
            echo '<option value="0"> Not Active </option>';
            echo '</select>';
            exit;
        }catch (Exception $e) {
            echo $e->getMessage();
            exit;
        }
    }

    public function getCustAccMonth(){
		
		$data = array('rows' => array(), 'success' => false, 'message' => '');
		try{
			$result = "";
			$ci = & get_instance();
			$ci->load->model('transaksi/transaksi_harian');
			$table= $ci->transaksi_harian;
			
			$user_name = $ci->session->userdata('user_name');
			if(empty($t_cust_account_id))$qs = $table->db->query("select t_cust_account_id,npwd from sikp.f_get_npwd_by_username('".$user_name."')");
			$arr_npwd = $qs->row_array();
			// print_r($arr_npwd);exit;
			$q = " SELECT
                        		 '".$arr_npwd['npwd']."' as npwd,
                        		 t_cust_acc_dtl_trans.t_cust_account_id,
                        		 sum(t_cust_acc_dtl_trans.service_charge) as jum_trans,
                        		 sum(t_cust_acc_dtl_trans.vat_charge) as jum_pajak,
                                 t_cust_acc_dtl_trans.p_vat_type_dtl_id,
                        		 p_finance_period.p_finance_period_id,
                        		 p_finance_period.code,
                        		 t_customer_order.p_order_status_id,
                        		 case when t_vat_setllement.start_period is null then p_finance_period.start_date else t_vat_setllement.start_period END as start_period,
                             case when t_vat_setllement.end_period is null then p_finance_period.end_date else t_vat_setllement.end_period END as end_period
                        FROM
                             t_cust_acc_dtl_trans
                        LEFT JOIN p_finance_period on to_char(trans_date, 'YYYY-MM') = to_char(p_finance_period.start_date, 'YYYY-MM')
                        LEFT JOIN t_vat_setllement on t_cust_acc_dtl_trans.t_cust_account_id = t_vat_setllement.t_cust_account_id and  p_finance_period.p_finance_period_id = t_vat_setllement.p_finance_period_id 
                        LEFT JOIN t_customer_order on t_customer_order.t_customer_order_id = t_vat_setllement.t_customer_order_id
                        WHERE
                             t_cust_acc_dtl_trans.t_cust_account_id < ".$arr_npwd['t_cust_account_id']." AND 
                        		 trans_date >= CASE
                        				WHEN  t_vat_setllement.start_period is null THEN p_finance_period.start_date
                        				ELSE t_vat_setllement.start_period
                        			END
                        		AND 
                        		trans_date <= CASE
                        				WHEN  t_vat_setllement.end_period is null THEN p_finance_period.end_date
                        				ELSE t_vat_setllement.end_period
                        			END
                        GROUP BY
                        		 t_cust_acc_dtl_trans.t_cust_account_id,
                                 t_cust_acc_dtl_trans.p_vat_type_dtl_id,
                        		 p_finance_period.p_finance_period_id,
                        		 p_finance_period.code,
                        		 t_customer_order.p_order_status_id,
                        		 case when t_vat_setllement.start_period is null then p_finance_period.start_date else t_vat_setllement.start_period END,
                             case when t_vat_setllement.end_period is null then p_finance_period.end_date else t_vat_setllement.end_period END
                        ORDER BY 
                        		 case when t_vat_setllement.start_period is null then p_finance_period.start_date else t_vat_setllement.start_period END DESC";
			$q = $ci->db->query($q);
			$result = $q->row_array();
			
			$data['rows'] = $result;
			$data['success'] = true;
			$data['message'] = 'data suceeded';
		}
		catch (Exception $e) {
			$table->db->trans_rollback(); //Rollback Trans
            $data['message'] = $e->getMessage();
            $data['rows'] = array();
		}
		echo json_encode($data);
		exit;
	}
	
	public function pelaporan_bulan(){
		$data = array('rows' => array(), 'success' => false, 'message' => '');
		try{
			$result = "";
			$ci = & get_instance();
			$ci->load->model('transaksi/transaksi_harian');
			$table= $ci->transaksi_harian;
		
			// print_r($arr_npwd);exit;
			// $q 	= " SELECT *,to_char(start_date,'mm-dd-yyyy') as start_date_string,to_char(end_date,'mm-dd-yyyy') as end_date_string";
			// $q .= " FROM view_finance_period_bayar finance limit 36";
			$q = "SELECT *,to_char(start_date,'dd-mm-yyyy') as start_date_string,to_char(end_date,'dd-mm-yyyy') as end_date_string
		from view_finance_period_bayar
		where p_finance_period_id - 1<= (
		SELECT p_finance_period_id p_f_p_id
		from view_finance_period_bayar
		where  to_char(start_date,'yyyy-mm-dd') in (
		select start_period start_periods
				from (select *
									from 
										(select c.npwd, 
													a.t_vat_setllement_id,	
													a.t_customer_order_id,
													a.is_surveyed,
													
														a.payment_key,
														c.company_name, 
														b.code as periode_pelaporan, 
														to_char(a.settlement_date,'DD-MON-YYYY') tgl_pelaporan, 
														a.total_trans_amount as total_transaksi,
														a.total_vat_amount as total_pajak ,
													nvl(a.total_penalty_amount,0) as total_denda,
														d.receipt_no as kuitansi_pembayaran,
														to_char(payment_date,'DD-MON-YYYY HH24:MI:SS') tgl_pembayaran ,
														d.payment_amount,
														c.t_cust_account_id ,
														b.p_finance_period_id ,
														to_char(a.start_period, 'yyyy-mm-dd') as start_period,
														to_char(a.end_period, 'yyyy-mm-dd') as end_period,
														to_char(a.start_period,'DD-MON-YYYY') as periode_awal_laporan,
														to_char(a.end_period,'DD-MON-YYYY') as periode_akhir_laporan,
														e.code as type_code,
														nvl(A.debt_vat_amt,a.total_vat_amount) as debt_vat_amt,
														nvl(a.db_increasing_charge,0) as db_increasing_charge,
														nvl(A.debt_vat_amt,a.total_vat_amount) + nvl(a.db_increasing_charge,0) +nvl(a.db_interest_charge,0) + nvl(a.total_penalty_amount,0) as total_hrs_bayar,
														nvl(a.db_increasing_charge,0) as kenaikan,
														nvl(a.db_interest_charge,0) as kenaikan1,
														a.p_vat_type_dtl_id,
														a.no_kohir,
														to_char(a.due_date,'DD-MON-YYYY') as jatuh_tempo,
														settlement_date,
														b.start_date												 
											from t_vat_setllement a,
													p_finance_period b,
													t_cust_account c,
													t_payment_receipt d,
													p_settlement_type e,
													p_app_user f
											where a.p_finance_period_id = b.p_finance_period_id
											and start_period is not null 
														and a.t_cust_account_id = c.t_cust_account_id
													and a.t_cust_account_id =  ".$ci->session->userdata('cust_account_id')."
														and a.t_vat_setllement_id = d.t_vat_setllement_id (+) 
													and a.p_settlement_type_id = e.p_settlement_type_id
													and a.created_by = f.app_user_name(+) ) as hasil
									left join p_vat_type_dtl x on x.p_vat_type_dtl_id = hasil.p_vat_type_dtl_id) as data_transaksi
								
								left join t_cust_acc_masa_jab masa_jab 
									on masa_jab.t_cust_account_id = data_transaksi.t_cust_account_id
									and masa_awal <= settlement_date
									and
									case 
										when masa_akhir is NULL
											then true
										when masa_akhir >= settlement_date
											then masa_akhir >= settlement_date
									end                        		
		order by start_periods desc
		limit 1))
		limit 36";
			// print_r($q);exit;
			$q = $ci->db->query($q);
			$result = $q->result_array();
			if($result == null){
				$q = "SELECT *,to_char(start_date,'dd-mm-yyyy') as start_date_string,to_char(end_date,'dd-mm-yyyy') as end_date_string
						from view_finance_period_bayar
						limit 36";
							$q = $ci->db->query($q);
			$result = $q->result_array();
			};
			$data['rows'] = $result;
			$data['success'] = true;
			$data['message'] = 'data suceeded';
		}
		catch (Exception $e) {
			$table->db->trans_rollback(); //Rollback Trans
            $data['message'] = $e->getMessage();
            $data['rows'] = array();
		}
		echo json_encode($data);
		exit;	
	}
	
	public function p_vat_type_dtl(){
		$data = array('rows' => array(), 'success' => false, 'message' => '');
		try{
			$result = "";
			$ci = & get_instance();
			$ci->load->model('transaksi/transaksi_harian');
			$table= $ci->transaksi_harian;
		
			// print_r($arr_npwd);exit;
			$q 	= " select vat_type_dtl.* ";
			$q .= " FROM sikp.p_vat_type_dtl vat_type_dtl";
			$q .= " WHERE p_vat_type_dtl_id = ". $ci->session->userdata('vat_type_dtl');
			$q = $ci->db->query($q);
			$result = $q->result_array();
			
			$data['rows'] = $result;
			$data['success'] = true;
			$data['message'] = 'data suceeded';
		}
		catch (Exception $e) {
			$table->db->trans_rollback(); //Rollback Trans
            $data['message'] = $e->getMessage();
            $data['rows'] = array();
		}
		echo json_encode($data);
		exit;	
	}
	
	public function p_vat_type_dtl_cls(){
		$data = array('rows' => array(), 'success' => false, 'message' => '');
		try{
			$result = "";
			$ci = & get_instance();
			$ci->load->model('transaksi/transaksi_harian');
			$table= $ci->transaksi_harian;
		
			// print_r($arr_npwd);exit;
			$q 	= " select * ";
			$q .= " FROM sikp.p_vat_type_dtl_cls ";
			$q .= " WHERE p_vat_type_dtl_id = ". $ci->session->userdata('vat_type_dtl');
			$q = $ci->db->query($q);
			$result = $q->result_array();
			
			$data['rows'] = $result;
			$data['success'] = true;
			$data['message'] = 'data suceeded';
		}
		catch (Exception $e) {
			$table->db->trans_rollback(); //Rollback Trans
            $data['message'] = $e->getMessage();
            $data['rows'] = array();
		}
		echo json_encode($data);
		exit;	
	}
	
	public function get_fined_start(){
		$data = array('rows' => array(), 'success' => false, 'message' => '');
		
		$nowdate = getVarClean('nowdate', 'str', ''); 
		$getdate = getVarClean('getdate', 'str', ''); 
		try{
			$result = "";
			$ci = & get_instance();
			$ci->load->model('transaksi/transaksi_harian');
			$table= $ci->transaksi_harian;
			
			$q 	= 	"SELECT 
						DATE_PART('day', current_date::timestamp - TO_DATE('". $nowdate ."'||due_in_day)::timestamp) boolDenda, 
						ceiling(months_between(current_date::timestamp , TO_DATE('". $nowdate ."'||due_in_day)::timestamp)) boolDendaMonth
					from p_finance_period 
					where to_char(start_date,'MM-YYYY') = '". $getdate ."'";
			// $q 	= " select to_char(start_date,'MM-YYYY'), due_in_day ";
			// $q .= " FROM p_finance_period ";
			// $q .= " WHERE to_char(start_date,'MM-YYYY') = '". $nowdate ."'";
			$res = $ci->db->query($q);
			$result = $res->result_array();
			// print_r($q);exit;
			$data['rows'] = $result;
			$data['success'] = true;
			$data['message'] = 'data suceeded';
		}
		catch (Exception $e) {
			$table->db->trans_rollback(); //Rollback Trans
            $data['message'] = $e->getMessage();
            $data['rows'] = array();
		}
		echo json_encode($data);
		exit;	
	}	
	
	
	public function getdata(){
		$page = getVarClean('page','int',1);
        $limit = getVarClean('rows','int',5);
        $sidx = getVarClean('sidx','str',' updated_date desc, trans_date');
        $sord = getVarClean('sord','str','asc');
		
		$ci = & get_instance();
		$ci->load->model('transaksi/transaksi_harian');
		$table= $ci->transaksi_harian;

        $nowdate = getVarClean('nowdate','str',"");
		$url = "http://45.118.112.226/dashboard/page/print/print_data_daily_npwpd.php?tgl=".$nowdate."&npwpd=".$ci->session->userdata('npwd')."";
		$getDataJSON = file_get_contents($url);
		$dataArray = json_decode($getDataJSON,true);
		$data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');
		
		$data['total'] = count($dataArray['items']);
		$data['records'] = count($dataArray['items']);
	
		$data['rows'] = $dataArray['items'];
		$data['success'] = true;
		
		return $data;
	}
	public function getdata_bulanan(){
		
		$ci = & get_instance();
		$ci->load->model('transaksi/transaksi_harian');
		$table= $ci->transaksi_harian;

        $bulan = getVarClean('bulan','str',"");
        $tahun = getVarClean('tahun','str',"");
		// $url = "http://45.118.112.226/dashboard/page/print/print_data_daily_npwpd.php?tgl=".$nowdate."&npwpd=".$ci->session->userdata('npwd')."";
		$url ="http://45.118.112.226/dashboard/page/print/print_data_monthly_npwpd.php?bulan=".$bulan."&tahun=".$tahun."&npwpd=".$ci->session->userdata('npwd')."";
		// $url ="http://45.118.112.226/dashboard/page/print/print_data_monthly_npwpd.php?bulan=10&tahun=2016&npwpd=P200345750404";
		$getDataJSON = file_get_contents($url);
		$dataArray = json_decode($getDataJSON,true);
		
		$data = array('rows' => array(), 'page' => 1, 'records' => 0, 'total' => 1, 'success' => false, 'message' => '');
		
		$data['total'] = count($dataArray['items']);
		$data['records'] = count($dataArray['items']);
	
		$data['rows'] = $dataArray['items'];
		$data['success'] = true;
		
		return $data;
	}

	
}