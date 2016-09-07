<?php
/**
 * bphtb_registration
 * class model for table bds_bphtb_registration 
 *
 * @since 23-10-2012 12:07:20
 * @author hilman farid
 */
class History_transaksi extends Abstract_model{
    /* Table name */
    public $table = 't_vat_setllement';
    /* Alias for table */
    public $alias = '';
    /* List of table fields */
    public $fields = array();
                           
                           
    /* Display fields */
    public $displayFields = array();
    /* Details table */
    public $details = array();
    /* Primary key */
    public $pkey = '';
    /* References */    
    public $refs = array();
    
    /* select from clause for getAll and countAll */
    public $selectClause = " nvl(nama,company_name)as new_company_name, masa_awal, case 
												 when  kuitansi_pembayaran is not null
												 then 'Lunas' else 'Belum lunas' end												 
												 as lunas, masa_akhir,data_transaksi.* ";

    public $fromClause = " (select *
                        	from 
                        		(select c.npwd, 
                        					 a.t_vat_setllement_id,	
                        					 a.t_customer_order_id,
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
                        					 p_settlement_type e
                        			where a.p_finance_period_id = b.p_finance_period_id
                        						and a.t_cust_account_id = c.t_cust_account_id
                        					    %s
                        						and a.t_vat_setllement_id = d.t_vat_setllement_id (+) 
                        					and a.p_settlement_type_id = e.p_settlement_type_id) as hasil
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
                        		end";

    function __construct($t_cust_account_id = ''){
        if (!empty($t_cust_account_id)){
            $this->fromClause = sprintf($this->fromClause, "and a.t_cust_account_id = ".$t_cust_account_id);
        }else{
            $this->fromClause = sprintf($this->fromClause, 'and a.t_cust_account_id = -999');
        }

        parent::__construct();
   	}
    
    /**
     * validate
     * input record validator
     */
    public function validate(){
        
        if ($this->actionType == 'CREATE'){
            // TODO : Write your validation for CREATE here
            
        }else if ($this->actionType == 'UPDATE'){
            // TODO : Write your validation for UPDATE here
        }
        
        return true;
    }
}
?>