<script type="text/javascript" src="<?php echo base_url(); ?>/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>/assets/global/plugins/moment.min.js"></script>

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
		<div class="col-md-4 form-inline">Harian	:
			<input class="form-control date-picker" type="text" id="datepicker" readonly=""> 
			<a class="btn btn-primary" id="generatetable">Generate</a>
		</div>
		<div class="col-md-4 col-md-offset-1 form-inline">
			<label>Total <b>[Rp]</b>:</label>
			<input class="form-control" type="text" id="totaldata" readonly=""> 
		</div>
	</div>
	<div class="row space-4"></div>
	<div class="row" id="tablemodem">
		<div class="col-md-12">
			<table id="grid-table"></table>
			<div id="grid-pager"></div>
		</div>
	</div> 

</div>

<script>
$(document).ready(function(){
	// $('#tablemodem').hide();
});
$( "#datepicker" ).datepicker();
function formatRupiahCurrency(total) {
    var neg = false;
    if(total < 0) {
        neg = true;
        total = Math.abs(total);
    }
    return (neg ? "-$" : '') + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
}
$("#generatetable").on('click',function() 
	{
		$a = 
		jQuery("#grid-table").jqGrid('setGridParam', {
                        url: '<?php echo WS_JQGRID."pelaporan.pelaporan_pajak_controller/getdata"; ?>',
                        datatype: 'json',
                        postData: {nowdate:$('#datepicker').val()}
                        // userData: {row: rowid}
                    });
					jQuery("#grid-table").trigger("reloadGrid");
		
	});
	
	jQuery(function($) {
        var grid_selector = "#grid-table";
        var pager_selector = "#grid-pager";

        jQuery("#grid-table").jqGrid({

            mtype: "POST",
            colModel: [

                {label: 'ID', name: 'no', key: true, hidden: true},
                {label: 'Time', name: 'time', key: false, align:'center', hidden: false},
                {label: 'Device ID', name: 'device_id', align:'center', key: false, hidden: false},
                {label: 'Receipt No', name: 'receipt_no', align:'center', key: false, hidden: false},
                {label: 'Sub Total', name: 'sub_total', key: false, formatter:'currency', formatoptions: {thousandsSeparator : '.', decimalPlaces: 0}, align:'right', hidden: false},
                {label: 'Charge', name: 'charge', key: false, formatter:'currency', formatoptions: {thousandsSeparator : '.', decimalPlaces: 0}, align:'right', hidden: false},
                {label: 'Tax', name: 'tax', key: false, formatter:'currency', formatoptions: {thousandsSeparator : '.', decimalPlaces: 0}, align:'right', hidden: false},
                {label: 'Total', name: 'total', key: false, formatter:'currency', formatoptions: {thousandsSeparator : '.', decimalPlaces: 0}, align:'right', hidden: false},
				
			],
            height: '100%',
			width:'90%',
			cmTemplate: { sortable: false },
            autowidth: false,
            viewrecords: true,
            rowNum: 10000,
            rowList: [10000],
            rownumbers: true, // show row numbers
            // rownumWidth: 35, // the width of the row numbers columns
            altRows: true,
            shrinkToFit: false,
            multiboxonly: true,
			// width:'100%',
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
            gridComplete: function() {
				var $grid = $('#grid-table');
				var colSum = $grid.jqGrid('getCol', 'total', false, 'sum');
				$('#totaldata').val(formatRupiahCurrency(colSum));
			},
            editurl: '',
            caption: "Tax Details"

        });
        jQuery('#grid-table').jqGrid('navGrid', '#grid-pager',
            {   //navbar options
                edit: false,
				excel: true,
                editicon: 'fa fa-pencil blue bigger-120',
                add: false,
                addicon: 'fa fa-plus-circle purple bigger-120',
                del: false,
                delicon: 'fa fa-trash-o red bigger-120',
                search: false,
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
        $(grid_selector).jqGrid( 'setGridWidth', $(".portlet-body").width() );
        $(pager_selector).jqGrid( 'setGridWidth', parent_column.width() );

    }
</script>