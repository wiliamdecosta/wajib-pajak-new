<?php
/**
 * bphtb_registration
 * class model for table bds_bphtb_registration 
 *
 * @since 23-10-2012 12:07:20
 * @author hilman farid
 */
class T_vat_settlement extends Abstract_model{
    /* Table name */
    public $table = 't_cust_acc_dtl_trans';
    /* Alias for table */
    public $alias = 't_cust_acc_dtl_trans';
    /* List of table fields */
    public $fields = array(
	 't_cust_acc_dtl_trans_id' => array('type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 't_cust_acc_dtl_trans_id'),
                           'service_desc' => array('type' => 'str', 'nullable' => true, 'unique' => false, 'display' => 'service_desc'),
                           'trans_date' => array('type' => 'str', 'nullable' => true, 'unique' => false, 'display' => 'description'),
                           'description' => array('type' => 'str', 'nullable' => true, 'unique' => false, 'display' => 'description'),
                           'bill_no' => array('type' => 'str', 'nullable' => true, 'unique' => false, 'display' => 'bill_no'),
						   'bill_no_end' => array('type' => 'str', 'nullable' => true, 'unique' => false, 'display' => 'bill_no_end'),
						   'bill_count' => array('type' => 'str', 'nullable' => true, 'unique' => false, 'display' => 'bill_count'),
                           'service_charge' => array('type' => 'float', 'nullable' => true, 'unique' => false, 'display' => 'service_charge'),
                           'vat_charge' => array('type' => 'float', 'nullable' => true, 'unique' => false, 'display' => 'vat_charge'),
                           'p_vat_type_dtl_id' => array('type' => 'int', 'nullable' => false, 'unique' => false, 'display' => 'p_vat_type_dtl_id'),
                           'p_vat_type_dtl_cls_id' => array('type' => 'int', 'nullable' => true, 'unique' => false, 'display' => 'p_vat_type_dtl_cls_id'),
                           'updated_date' => array('type' => 'date', 'nullable' => true, 'unique' => false, 'display' => 'updated_date'),
                           'updated_by' => array('type' => 'date', 'nullable' => true, 'unique' => false, 'display' => 'updated_by')
						);
                           
                           
    /* Display fields */
    public $displayFields = array();
    /* Details table */
    public $details = array();
    /* Primary key */
    public $pkey = 't_vat_setllement_id';
    /* References */    
    public $refs = array();
    
    /* select from clause for getAll and countAll */
    public $selectClause = "cust_order.t_customer_order_id as t_customer_order_id,settlement.*,period.code as finance_period_code,cust_order.order_no,cust_order.p_rqst_type_id,to_char(settlement.settlement_date, 'yyyy-mm-dd') as settlement_date,to_char(settlement.start_period, 'yyyy-mm-dd') as start_period,to_char(settlement.end_period, 'yyyy-mm-dd') as end_period,to_char(settlement.payment_due_day, 'dd-mm-yyyy HH24:mi:ss') as pay_due_date";

    public $fromClause = " sikp.t_vat_setllement settlement
                          left join p_finance_period period on settlement.p_finance_period_id = period.p_finance_period_id
                          left join t_customer_order cust_order on cust_order.t_customer_order_id = settlement.t_customer_order_id
                          left join t_cust_account cust_acc on cust_acc.t_cust_account_id = settlement.t_cust_account_id";

    function __construct(){
		parent::__construct();
   	}
    
    /**
     * validate
     * input record validator
     */
    public function validate(){
        
        if ($this->actionType == 'CREATE'){
            // TODO : Write your validation for CREATE here
			// $this->record['creation_date'] = date('Y-m-d');
            // $this->record['created_by'] = $userInfo['user_name'];
			
			 // $this->record['updated_date'] = date('Y-m-d');
            // $this->record['updated_by'] = $userInfo['user_name'];
            
        }else if ($this->actionType == 'UPDATE'){
            // TODO : Write your validation for UPDATE here
        }
        
        return true;
    }
}
?>