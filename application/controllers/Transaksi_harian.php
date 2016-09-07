<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi_harian extends CI_Controller {

    function print_transaksi_harian() {

        $sort = 't_cust_acc_dtl_trans_id';
        $dir = 'DESC';

        $date_start = $this->input->get('date_start');
        $date_end = $this->input->get('date_end');

        $p_vat_type_dtl_id = $this->input->get('p_vat_type_dtl_id');
        $t_cust_account_id = $this->input->get('t_cust_account_id');

        $trans_date = $this->input->get('trans_date');

        $criteria = array();
        if(!empty($date_start)&&!empty($date_end)){
            $criteria[] = " (trunc(trans_date) BETWEEN '".$date_start."' AND '".$date_end."') ";
        }else if(!empty($date_start)&&empty($date_end)){
            $criteria[] = " trunc(trans_date) >= '".$date_start."' ";
        }else if(empty($date_start)&&!empty($date_end)){
            $criteria[] = " trunc(trans_date) <= '".$date_end."' ";
        }

        $criteria[] = " p_vat_type_dtl_id = ".$p_vat_type_dtl_id;
        $user_name = $this->session->userdata('user_name');

        if(empty($trans_date)){
            $trans_date = 'null';
        }else{
            $trans_date = "'".$trans_date."'";
        }

        $whereCondition = " where ".join(" AND ", $criteria);
        $sql = "select to_char(trans_date,'yyyy-mm-dd') as trans_date,t_cust_acc_dtl_trans_id, t_cust_account_id, bill_no,bill_no_end,bill_count, service_desc, service_charge, vat_charge, description,p_vat_type_dtl_id
                  from sikp.f_get_cust_acc_dtl_trans_exist_v2($t_cust_account_id,$trans_date) as tbl (t_cust_acc_dtl_trans_id) ".$whereCondition." order by $sort $dir";

        $items = $this->db->query($sql)->result_array();

        /*$sql = "";
        $sql = "select count(1) as total from sikp.f_get_cust_acc_dtl_trans_exist_v2($t_cust_account_id,$trans_date) ".$whereCondition;
        $row = $this->db->query($sql)->row_array();
        $countitems = $row['total'];
        */

        $this->print_laporan($items,array('username' => $user_name,'date_start' => $date_start,'date_end' => $date_end));
    }


    function print_laporan($param_arr,$param2){
        include "fpdf17/mc_table.php";
        $_BORDER = 0;
        $_FONT = 'Times';
        $_FONTSIZE = 10;
        $pdf = new PDF_MC_Table();
        $size = $pdf->_getpagesize('A4');
        $pdf->DefPageSize = $size;
        $pdf->CurPageSize = $size;
        $pdf->AddPage('Portrait', 'A4');
        $pdf->SetFont('helvetica', '', $_FONTSIZE);
        $pdf->SetRightMargin(5);
        $pdf->SetLeftMargin(9);
        $pdf->SetAutoPageBreak(false,0);

        $pdf->SetFont('helvetica', '',15);
        $pdf->SetWidths(array(200));
        $pdf->ln(1);
        $pdf->RowMultiBorderWithHeight(array("Laporan Transaksi Harian"),array('',''),6);
        $pdf->SetFont('helvetica', '',12);
        $pdf->SetWidths(array(40,10,200));
        $pdf->ln(1);
        $pdf->RowMultiBorderWithHeight(array("NPWP",":",$param2['username']),array('','',''),6);
        $pdf->RowMultiBorderWithHeight(array("Tanggal",":",$param2['date_start'].' s/d '.$param2['date_end']),array('','',''),6);
        $pdf->ln(8);
        $pdf->SetWidths(array(10,40,40,60));
        $pdf->SetAligns(array('C','C','C','C'));
        $pdf->RowMultiBorderWithHeight(array("No","Tanggal Transaksi","No Faktur","Nilai Transaksi"),array('LTBR','LTBR','LTBR'),6);
        $i=1;
        $pdf->SetAligns(array('L','L','L','R'));
        foreach($param_arr as $item){
            if ($item['bill_no_end']!=''){
                $bill_no = $item['bill_no'].'-'.$item['bill_no_end'];
            }else{
                $bill_no = $item['bill_no'];
            }
            $pdf->RowMultiBorderWithHeight(array($i,$item['trans_date'],$bill_no,'Rp. '.number_format($item['service_charge'],2,'.',',')),array('LTBR','LTBR','LTBR'),6);
            $i++;
        }
        $pdf->Output("","I");
        exit;
    }
}