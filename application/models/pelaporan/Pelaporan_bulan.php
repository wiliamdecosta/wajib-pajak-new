<?php

/**
 * Users Model
 *
 */
class Pelaporan_bulan extends Abstract_model {	
	
    public $table           = "p_finance_period";
    public $pkey            = "";
    public $alias           = "";

    public $fields          = array(
                                // 'id'                => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'ID User'),
                                // 'ip_address'        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'IP Address'),
                                // 'username'          => array('nullable' => false, 'type' => 'str', 'unique' => true, 'display' => 'Username'),
                                // 'password'          => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Password'),
                                // 'salt'              => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Salt'),
                                // 'email'             => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Email'),
                                // 'activation_code'   => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Activation Code'),
                                // 'forgotten_password_code'   => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Forgoten Password Code'),
                                // 'forgotten_password_time'   => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Forgoten Password Time'),
                                // 'remember_code'     => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Remember Code'),
                                // 'created_on'        => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Created On'),
                                // 'last_login'        => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Last Login'),
                                // 'active'            => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Active'),
                                // 'first_name'        => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'First Name'),
                                // 'last_name'         => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Last Name'),
                                // 'company'           => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Company'),
                                // 'phone'             => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Phone'),
                                // 'location_id'       => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'Location'),

                            );

    public $selectClause    =" *,to_char(start_date,'dd-mm-yyyy') as start_date_string,to_char(end_date,'dd-mm-yyyy') as end_date_string ";
    public $fromClause      = " view_finance_period_bayar finance ";


    function __construct() {        
		// $this->fromClause = sprintf($this->fromClause, $this->session->userdata('npwd'));
		$this->selectClause = sprintf($this->selectClause, $this->session->userdata('npwd'));
		
		parent::__construct();
	}

    public function get_pelaporan_data(){
			$whereClause = "
                        WHERE
                             
                        GROUP BY
                        		 t_cust_acc_dtl_trans.t_cust_account_id,
                                 t_cust_acc_dtl_trans.p_vat_type_dtl_id,
                        		 p_finance_period.p_finance_period_id,
                        		 p_finance_period.code,
                        		 t_customer_order.p_order_status_id,
                        		 case when t_vat_setllement.start_period is null then p_finance_period.start_date else t_vat_setllement.start_period END,
                             case when t_vat_setllement.end_period is null then p_finance_period.end_date else t_vat_setllement.end_period END";
		
	}
	
	public function getAllData($start = 0, $limit = 30, $orderby = '', $ordertype = 'ASC') {

		$this->db->select($this->selectClause);
		$this->db->from($this->fromClause);
		if(count($this->joinClause) > 0) {
			foreach($this->joinClause as $with) {
				if(empty($with['table_name']) or
					empty($with['on']) or empty($with['join_type'])) {
					throw new Exception('Error Join Clause');
				}

				$this->db->join($with['table_name'], $with['on'], $with['join_type']);
			}
		}

		$whereCondition = '';
		$condition = array();
		$condition = $this->getCriteria();

		$whereCondition = join(" AND ", $condition);
		if( isset($this->jqGridParamSearch['where']) and count($this->jqGridParamSearch['where']) > 0)
		    $whereCondition .= join(" AND ", $this->jqGridParamSearch['where']);

		$wh = "";
		if(count($this->jqGridParamSearch) > 0) {
		    if($this->jqGridParamSearch['search'] != null && $this->jqGridParamSearch['search'] === 'true'){
                $wh = "UPPER(".$this->jqGridParamSearch['search_field'].")";
                switch ($this->jqGridParamSearch['search_operator']) {
                    case "bw": // begin with
                        $wh .= " LIKE UPPER('".$this->jqGridParamSearch['search_str']."%')";
                        break;
                    case "ew": // end with
                        $wh .= " LIKE UPPER('%".$this->jqGridParamSearch['search_str']."')";
                        break;
                    case "cn": // contain %param%
                        $wh .= " LIKE UPPER('%".$this->jqGridParamSearch['search_str']."%')";
                        break;
                    case "eq": // equal =
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " = ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " = UPPER('".$this->jqGridParamSearch['search_str']."')";
                        }
                        break;
                    case "ne": // not equal
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " <> ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " <> UPPER('".$this->jqGridParamSearch['search_str']."')";
                        }
                        break;
                    case "lt":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " < ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " < '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    case "le":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " <= ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " <= '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    case "gt":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " > ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " > '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    case "ge":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " >= ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " >= '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    default :
                        $wh = "";
                }
            }
		}

		if(!empty($wh)) {
            if(!empty($whereCondition))
                $whereCondition .= " AND ".$wh;
            else
                $whereCondition = $wh;
        }

		if($whereCondition != "") {
		    $this->db->where($whereCondition, null, false);
		}

		$this->db->group_by("t_cust_acc_dtl_trans.t_cust_account_id,
							t_cust_acc_dtl_trans.p_vat_type_dtl_id,
							p_finance_period.p_finance_period_id,
							p_finance_period.code,
							t_customer_order.p_order_status_id,
							case when t_vat_setllement.start_period is null then p_finance_period.start_date else t_vat_setllement.start_period END,
							case when t_vat_setllement.end_period is null then p_finance_period.end_date else t_vat_setllement.end_period END");

        if(count($this->jqGridParamSearch) > 0) {
            $this->db->order_by($this->jqGridParamSearch['sort_by'], $this->jqGridParamSearch['sord']);
        }else {
            if(empty($orderby)) $orderby = $this->pkey;
		    $this->db->order_by($orderby, $ordertype);
        }		

        if(count($this->jqGridParamSearch) > 0) {
            $this->db->limit($this->jqGridParamSearch['limit']['end'], $this->jqGridParamSearch['limit']['start']);
        }else if($limit != -1) {
			$this->db->limit($limit, $start);
        }

		print_r($this->db->get_compiled_select());exit;
		$queryResult = $this->db->get();
		$items = $queryResult->result_array();

		$queryResult->free_result();

		return $items;

	}
	
	
	public function countAllData() {
	    //$this->db->_protect_identifiers = false;

		$query = "SELECT COUNT(1) AS totalcount FROM ".$this->fromClause;
		if(count($this->joinClause) > 0) {

			foreach($this->joinClause as $with) {
				if(empty($with['table_name']) or
						empty($with['on']) or empty($with['join_type'])) {
						throw new Exception('Error Join Clause');
				}
				$query.= " ".$with['join_type']." JOIN ".$with['table_name']." ON ".$with['on'];
			}
		}

		$whereCondition = '';
		$condition = array();
		$condition = $this->getCriteria();

		$whereCondition = join(" AND ", $condition);
		if(isset($this->jqGridParamSearch['where']) and count($this->jqGridParamSearch['where']) > 0)
		    $whereCondition .= join(" AND ", $this->jqGridParamSearch['where']);

		$wh = "";
		if(count($this->jqGridParamSearch) > 0) {
		    if($this->jqGridParamSearch['search'] != null && $this->jqGridParamSearch['search'] === 'true'){
                $wh = "UPPER(".$this->jqGridParamSearch['search_field'].")";
                switch ($this->jqGridParamSearch['search_operator']) {
                    case "bw": // begin with
                        $wh .= " LIKE UPPER('".$this->jqGridParamSearch['search_str']."%')";
                        break;
                    case "ew": // end with
                        $wh .= " LIKE UPPER('%".$this->jqGridParamSearch['search_str']."')";
                        break;
                    case "cn": // contain %param%
                        $wh .= " LIKE UPPER('%".$this->jqGridParamSearch['search_str']."%')";
                        break;
                    case "eq": // equal =
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " = ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " = UPPER('".$this->jqGridParamSearch['search_str']."')";
                        }
                        break;
                    case "ne": // not equal
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " <> ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " <> UPPER('".$this->jqGridParamSearch['search_str']."')";
                        }
                        break;
                    case "lt":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " < ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " < '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    case "le":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " <= ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " <= '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    case "gt":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " > ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " > '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    case "ge":
                        if(is_numeric($this->jqGridParamSearch['search_str'])) {
                            $wh .= " >= ".$this->jqGridParamSearch['search_str'];
                        } else {
                            $wh .= " >= '".$this->jqGridParamSearch['search_str']."'";
                        }
                        break;
                    default :
                        $wh = "";
                }
            }
		}
		
		

		if(!empty($wh)) {
            if(!empty($whereCondition))
                $whereCondition .= " AND ".$wh;
            else
                $whereCondition = $wh;
        }

        if(!empty($whereCondition)) {
            $query = $query. " WHERE ".$whereCondition ."";
        }

		$query.= " GROUP BY t_cust_acc_dtl_trans.t_cust_account_id,
							t_cust_acc_dtl_trans.p_vat_type_dtl_id,
							p_finance_period.p_finance_period_id,
							p_finance_period.code,
							t_customer_order.p_order_status_id,
							case when t_vat_setllement.start_period is null then p_finance_period.start_date else t_vat_setllement.start_period END,
							case when t_vat_setllement.end_period is null then p_finance_period.end_date else t_vat_setllement.end_period END";
		$query = $this->db->query($query);		
		$row = $query->row_array();

		$query->free_result();


		return $row['totalcount'];
	}


}

/* End of file Users.php */