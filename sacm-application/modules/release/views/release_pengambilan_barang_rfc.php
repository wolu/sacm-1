<table id="dgrfc" class="easyui-datagrid" title="Pengambilan Barang | RFC"  style="height:500px;"
            url="<?=base_url('release/getJsonRfc?status=RA')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_rfc"
            singleSELECT = "true">  
        <thead>  
            <tr>
                <th field="NIK"          width="7" sortable="true" align="center">NIK</th>
                <th field="NomorRFC"     width="7" sortable="true" align="center">No RFC </th>  
                <th field="NomorService" width="5" sortable="true" align="center">No Service </th> 
                <th field="SerialNumber" width="10"sortable="true" align="center">Serial Number </th>
                <th field="TanggalRFC"   width="10"sortable="true" align="center">Tanggal RFC </th>  
                <th field="TanggalInput" width="10"sortable="true" align="center">Tanggal Input </th>
                <th field="CodeChange"   width="5" sortable="true" align="center">Kode Change </th>  
                <th field="RFCId"        width="5" sortable="true" align="center">RFC ID </th> 
                <th field="TanggalTarget"width="10"sortable="true" align="center">Tgl Target </th>  
                <th field="KodeStatus"   width="3" sortable="true" align="center">Status </th>  
            </tr>  
        </thead>  
    </table>
    <div id="tb_rfc" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:updateSatusRfc()" class="btn btn-small btn-info">
    <i class="icon-bookmark icon-large"></i>&nbsp;Update Status</a>
    </div>
<!---------------------------------------------------------------------------------------->   
<!---------------------------------------------------------------------------------------->
<script type="text/javascript">
function updateSatusRfc() {
    	var row = $('#dgrfc').datagrid('getSelected');
    	if (row) {
    		$.messager.confirm('Confirm', 'Status Akan di Update  ?', function(r) {
    			if (r){
    				$.post('<?= base_url('release/updateStatusRfc') ?>',{
    					id: row.NomorRFC, KodeStatus: 'PB'
    				}, function(result){
    					if (result.success) {
    					    $.messager.alert('Berhasil',result.success, 'info');
    						$('#dgrfc').datagrid({ url: '<?= base_url('release/getJsonRfc?status=RA') ?>'}, 'reload'); // reload the user data
    					}else{
    						$.messager.show({ // show error message
    							title: 'Error',
    							msg: result.msg
    						});
    					}
    				}, 'json');
    			}
    		});
    	}
    }
    $('#dgrfc').datagrid({  
    view: detailview,  
    detailFormatter:function(index,row){  
        return '<div style="padding:2px"><table id="ddvoc-' + index + '" style="width:600px" ></table></div>';  
    },  
    onExpandRow: function(index,row){  
        $('#ddvoc-'+index).datagrid({  
            url:'release/getDetJsonRfc?NomorRFC='+ row.NomorRFC,  
            fitColumns:true,  
            singleSelect:true,  
            rownumbers:true,  
            loadMsg:'',  
            height:'auto',  
            columns:[[  
                {field:'KodeKonfigurasi',title:'Kode Konfigurasi',width:20,align:'center'},  
                {field:'Konfigurasi',title:'Konfigurasi',width:60},
                {field:'TanggalInput',title:'Tanggal Input',width:60}  
            ]],  
            onResize:function(){  
                $('#dgrfc').datagrid('fixDetailRowHeight',index);  
            },  
                onLoadSuccess:function(){  
                    setTimeout(function(){  
                        $('#dgoc').datagrid('fixDetailRowHeight',index);  
                    },0);  
                }  
            });  
            $('#dgrfc').datagrid('fixDetailRowHeight',index);  
        }  
    });
    </script>  