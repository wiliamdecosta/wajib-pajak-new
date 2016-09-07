 <!-- breadcrumb -->
<div class="page-bar">
    <ul class="page-breadcrumb">
        <li>
            <a href="<?php base_url();?>">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>History Transaksi</span>
        </li>
    </ul>
</div>
<!-- end breadcrumb -->
	
<div class="space-4"></div>

<div class="row">
	<div class="form-group">
		<label class="control-label col-md-1">Search</label>
		<div class="col-md-2">
			<select id="tpref" class="form-control">
				<option value="1">Customer Reference</option>
				<option value="2">Account Number</option>
				<option value="3">Account Name</option>
				<option value="4">Telephone Number</option>
			</select>									
		</div>		
		<div class="col-md-6" style="padding-left: 0px;">
			<a id="SPTPD" class="btn blue"> Cetak SPTPD </a>
			<a id="SSPD" class="btn blue"> Cetak SSPD </a>
			<a id="RekapPenjualan" class="btn blue"> Rekap Penjualan </a>
			<a id="CetakBayar" class="btn blue"><i class=""></i> Cetak Bayar </a>
		</div>                             
	</div>
</div>

<div class="space-4"></div>

<div class="row">
	<div class="tab-content col-md-12">
		<div class="tab-pane active">
			<table id="grid-table"></table>
			<div id="grid-pager"></div>
		</div>
	</div>	
</div>

<script>
	jQuery(function($) {
        var grid_selector = "#grid-table";
        var pager_selector = "#grid-pager";

        jQuery("#grid-table").jqGrid({
            url: '<?php echo WS_JQGRID."message.inbox_message_controller/read"; ?>',
            datatype: "json",
            mtype: "POST",            
            colModel: [             
                {label: 'Jenis Pesan', name: 'message_type', hidden: false, editable: true},                
                {label: 'Terkirim', name: 'update_date', hidden: false, editable: true},              
                {label: 'Status', name: 'message_status', hidden: false, editable: true}                
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
			// width:"100%",
            onSelectRow: function (rowid) {
                
            },
            sortorder:'',
            pager: '#grid-pager',
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
            caption: "Customer Details"

        });
		// jQuery("#grid-table").jqGrid('setGroupHeaders', {
          // useColSpanStyle: true, 
          // groupHeaders:[
            // {startColumnName: 'id', numberOfColumns: 1, titleText: '.'},
            // {startColumnName: 'date', numberOfColumns: 8, titleText: 'Nice'},
            // ]   
        // });
        // jQuery("#grid-table").jqGrid('setGroupHeaders', {
          // useColSpanStyle: true, 
          // groupHeaders:[
            // {startColumnName: 'id', numberOfColumns: 1, titleText: '.'},
            // {startColumnName: 'date', numberOfColumns: 4, titleText: 'rice'},
            // {startColumnName: 'total', numberOfColumns: 2, titleText: 'dice'}
            // ]   
        // });

        // jQuery("#grid-table").jqGrid('setGroupHeaders', {
          // useColSpanStyle: true, 
          // groupHeaders:[
            // {startColumnName: 'id', numberOfColumns: 1, titleText: '.'},
            // {startColumnName: 'date', numberOfColumns: 2, titleText: 'Price'},
            // {startColumnName: 'amount', numberOfColumns: 2, titleText: 'Shiping'},
            // {startColumnName: 'total', numberOfColumns: 2, titleText: 'bipping'}
            // ]   
        // });
        jQuery('#grid-table').jqGrid('navGrid', '#grid-pager',
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
        $(grid_selector).jqGrid( 'setGridWidth', $(".form-body").width() );
        $(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );

    }
</script>