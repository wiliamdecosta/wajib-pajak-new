<?php
/**
 * bphtb_registration
 * class model for table bds_bphtb_registration 
 *
 * @since 23-10-2012 12:07:20
 * @author hilman farid
 */
class Cust_acc_trans extends Abstract_model{
    /* Table name */
    public $table = 't_cust_acc_dtl_trans';
    /* Alias for table */
    public $alias = 't_cust_acc_dtl_trans';
    /* List of table fields */
    public $fields = array(
	 't_cust_acc_dtl_trans_id' => array('pkey'=>true,'type' => 'int', 'nullable' => false, 'unique' => true, 'display' => 't_cust_acc_dtl_trans_id'),
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
    public $pkey = 't_cust_acc_dtl_trans_id';
    /* References */    
    public $refs = array();
    
    /* select from clause for getAll and countAll */
    public $selectClause = " *,  TO_CHAR(trans_date, 'DD-MM-YYYY') as date_match ";

    public $fromClause = " sikp.t_cust_acc_dtl_trans";

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
            $this->record['creation_date'] = date('Y-m-d');
            $this->record['created_by'] = $this->session->userdata('user_name');
            
            $this->record['updated_date'] = date('Y-m-d');
            $this->record['updated_by'] = $this->session->userdata('user_name');
			
        }else if ($this->actionType == 'UPDATE'){
            // TODO : Write your validation for UPDATE here
			$this->record['updated_date'] = date('Y-m-d');
            $this->record['updated_by'] = $this->session->userdata('user_name');
			
        }
        
        return true;
    }
}
?>