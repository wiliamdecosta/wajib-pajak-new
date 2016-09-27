<!-- breadcrumb -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php base_url();?>">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>Tapping Modem</span>
        </li>
    </ul>
</div>
<!-- end breadcrumb -->
<div class="space-4"></div>

<div>
	
	<div class="row">
		<div class="col-md-12">
			<table id="grid-table-modem"></table>
			<div id="grid-pager-modem"></div>
		</div>
	</div>

</div>

<script>
jQuery(function($) {
        var grid_selector = "#grid-table-modem";
        var pager_selector = "#grid-pager-modem";

        jQuery("#grid-table-modem").jqGrid({
            url: '<?php echo WS_JQGRID.'transaksi.transaksi_harian_controller/read'; ?>',
            datatype: "json",
            mtype: "POST",
			colModel: [
                {label: 'Bulan', name: 'code', sortable:false, align:'center',hidden: false},
                {label: 'Masa Pajak', name: '', sortable:false, width:180, align:'center',hidden: false, formatter:function(cellvalue, options, rowObject){
					return rowObject['start_period'] + ' s.d ' + rowObject['end_period'];
				}},
                {label: 'p_vat', name: 'p_vat_type_dtl_id', hidden: true},
                {label: 'Status', name: 'p_order_status_id', sortable:false, hidden: false, width:200, editable: true, align:'center', formatter:function(cellvalue, options, rowObject){
					if(cellvalue == "" || cellvalue == null){
						return 'Laporan Belum Dikirim';
					}else if(cellvalue == 1 || cellvalue == 2){
						return 'Belum Verifikasi';
					}else if(cellvalue == 3){
						return 'Sudah Verifikasi';
					}
				}},
                {label: 'Jumlah Transaksi (Rp)', name: 'jum_trans', sortable:false, width:200, hidden: false, formatter:'currency', formatoptions: {thousandsSeparator : '.', decimalPlaces: 0}, align:'right',editable: true},
                {label: 'Jumlah Pajak (Rp)' , name: 'jum_pajak', sortable:false, width:150, hidden: false, formatter:'currency', formatoptions: {thousandsSeparator : '.', decimalPlaces: 0}, align:'right', editable: true},
                {label: 'Start Date', name: 'start_period', sortable:false, hidden: true, align:'center', editable: true},
                {label: 'End Date', name: 'end_period', sortable:false, hidden: true, align:'center', editable: true}
			],
            height: '100%',
            autowidth: true,
            viewrecords: true,
            rowNum: 10,
            rowList: [10,20,50],
            rownumbers: true, // show row numbers
            rownumWidth: 35, // the width of the row numbers columns
            altRows: true,
            shrinkToFit: true,
            multiboxonly: true,
            onSelectRow: function (rowid) {

                /*do something when selected*/
                var grid_id = jQuery("#grid-table-modem");
                var grid_id2 = jQuery("#grid-table-detail");
				selRowId = grid_id.jqGrid ('getGridParam', 'selrow'),
				celValue = grid_id.jqGrid ('getCell', selRowId, 'p_vat_type_dtl_id');
				celValue1 = grid_id.jqGrid ('getCell', selRowId, 'start_period');
				celValue2 = grid_id.jqGrid ('getCell', selRowId, 'end_period');

                if (rowid != null) {
                    grid_id2.jqGrid('setGridParam', {
                        url: '<?php echo WS_JQGRID."transaksi.cust_acc_trans_controller/read_acc_trans"; ?>',
                        datatype: 'json',
                        postData: {p_vat_type_dtl_id: celValue,start_period: celValue1,end_period: celValue2}
                        // userData: {row: rowid}
                    });
                    // grid_id.jqGrid('setCaption', 'Aliran Prosedur');
                    jQuery("#tabledetails").show();
                    jQuery("#grid-table-detail").trigger("reloadGrid");
                }

            },
            sortorder:'',
            pager: '#grid-pager-modem',
            jsonReader: {
                root: 'rows',
                id: 'id',
                repeatitems: false
            },
            loadComplete: function (response) {
                if(response.success == false) {
                    swal({title: 'Attention', text: response.message, html: true, type: "warning"});
                }
				responsive_jqgrid(grid_selector,pager_selector);
            },
            //memanggil controller jqgrid yang ada di controller crud
            editurl: '',
            caption: "Tapping Modem"

        });

        jQuery('#grid-table-modem').jqGrid('navGrid', '#grid-pager-modem',
            {   //navbar options
                edit: false,
				excel: true,
                editicon: 'fa fa-pencil blue bigger-120',
                add: false,
                addicon: 'fa fa-plus-circle purple bigger-120',
                del: false,
                delicon: 'fa fa-trash-o red bigger-120',
                search: true,
                searchicon: 'fa fa-search orange bigger-120',
                refresh: true,
                afterRefresh: function () {
                    // some code here
                },

                refreshicon: 'fa fa-refresh green bigger-120',
                view: false,
                viewicon: 'fa fa-search-plus grey bigger-120'
            },

            {
                // options for the Edit Dialog
                closeAfterEdit: true,
                closeOnEscape:true,
                recreateForm: true,
                serializeEditData: serializeJSON,
                width: 'auto',
                errorTextFormat: function (data) {
                    return 'Error: ' + data.responseText
                },
                beforeShowForm: function (e, form) {
                    var form = $(e[0]);
                    style_edit_form(form);

                },
                afterShowForm: function(form) {
                    form.closest('.ui-jqdialog').center();
                },
                afterSubmit:function(response,postdata) {
                    var response = jQuery.parseJSON(response.responseText);
                    if(response.success == false) {
                        return [false,response.message,response.responseText];
                    }
                    return [true,"",response.responseText];
                }
            },
            {
                //new record form
                editData: {
                    p_finance_period_id: function() {
                        return <?php echo $this->input->post('p_finance_period_id'); ?>;
                    }
                },
                closeAfterAdd: false,
                clearAfterAdd : true,
                closeOnEscape:true,
                recreateForm: true,
                width: 'auto',
                errorTextFormat: function (data) {
                    return 'Error: ' + data.responseText
                },
                serializeEditData: serializeJSON,
                viewPagerButtons: false,
                beforeShowForm: function (e, form) {
                    var form = $(e[0]);
                    style_edit_form(form);
                },
                afterShowForm: function(form) {
                    form.closest('.ui-jqdialog').center();
                },
                afterSubmit:function(response,postdata) {
                    var response = jQuery.parseJSON(response.responseText);
                    if(response.success == false) {
                        return [false,response.message,response.responseText];
                    }

                    $(".tinfo").html('<div class="ui-state-success">' + response.message + '</div>');
                    var tinfoel = $(".tinfo").show();
                    tinfoel.delay(3000).fadeOut();


                    return [true,"",response.responseText];
                }
            },
            {
                //delete record form
                serializeDelData: serializeJSON,
                recreateForm: true,
                beforeShowForm: function (e) {
                    var form = $(e[0]);
                    style_delete_form(form);

                },
                afterShowForm: function(form) {
                    form.closest('.ui-jqdialog').center();
                },
                onClick: function (e) {
                    //alert(1);
                },
                afterSubmit:function(response,postdata) {
                    var response = jQuery.parseJSON(response.responseText);
                    if(response.success == false) {
                        return [false,response.message,response.responseText];
                    }
                    return [true,"",response.responseText];
                }
            },
            {
                //search form
                closeAfterSearch: false,
                recreateForm: true,
                afterShowSearch: function (e) {
                    var form = $(e[0]);
                    style_search_form(form);
                    form.closest('.ui-jqdialog').center();
                },
                afterRedraw: function () {
                    style_search_filters($(this));
                }
            },
            {
                //view record form
                recreateForm: true,
                beforeShowForm: function (e) {
                    var form = $(e[0]);
                }
            }
        )
    });

	function serializeJSON(postdata) {
        var items;
        if(postdata.oper != 'del') {
            items = JSON.stringify(postdata, function(key,value){
                if (typeof value === 'function') {
                    return value();
                } else {
                  return value;
                }
            });
        }else {
            items = postdata.id;
        }

        var jsondata = {items:items, oper:postdata.oper, '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'};
        return jsondata;
    }

    function style_edit_form(form) {

        //update buttons classes
        var buttons = form.next().find('.EditButton .fm-button');
        buttons.addClass('btn btn-sm').find('[class*="-icon"]').hide();//ui-icon, s-icon
        buttons.eq(0).addClass('btn-primary');
        buttons.eq(1).addClass('btn-danger');


    }

    function style_delete_form(form) {
        var buttons = form.next().find('.EditButton .fm-button');
        buttons.addClass('btn btn-sm btn-white btn-round').find('[class*="-icon"]').hide();//ui-icon, s-icon
        buttons.eq(0).addClass('btn-danger');
        buttons.eq(1).addClass('btn-default');
    }

    function style_search_filters(form) {
        form.find('.delete-rule').val('X');
        form.find('.add-rule').addClass('btn btn-xs btn-primary');
        form.find('.add-group').addClass('btn btn-xs btn-success');
        form.find('.delete-group').addClass('btn btn-xs btn-danger');
    }

    function style_search_form(form) {
        var dialog = form.closest('.ui-jqdialog');
        var buttons = dialog.find('.EditTable')
        buttons.find('.EditButton a[id*="_reset"]').addClass('btn btn-sm btn-info').find('.ui-icon').attr('class', 'fa fa-retweet');
        buttons.find('.EditButton a[id*="_query"]').addClass('btn btn-sm btn-inverse').find('.ui-icon').attr('class', 'fa fa-comment-o');
        buttons.find('.EditButton a[id*="_search"]').addClass('btn btn-sm btn-success').find('.ui-icon').attr('class', 'fa fa-search');
    }

    function responsive_jqgrid(grid_selector, pager_selector) {

        var parent_column = $(grid_selector).closest('[class*="col-"]');
        $(grid_selector).jqGrid( 'setGridWidth', $(".page-content").width() );
        $(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );

    }
</script>