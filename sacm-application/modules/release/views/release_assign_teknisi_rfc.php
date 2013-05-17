<table id="dgrfc" class="easyui-datagrid" title="Assign Teknisi RFC"  style="height:500px;"
            url="<?=base_url('release/getJsonRfc?status=OC')?>"
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
    <a href="javascript:void(0)" onclick="javascript:updateAssignTeknisi()" class="btn btn-small btn-success">
    <i class="icon-user-md icon-large"></i>&nbsp;Assign Teknisi</a>
    
    <a href="javascript:void(0)" onclick="javascript:updateSatusRfc()" class="btn btn-small btn-info">
    <i class="icon-bookmark icon-large"></i>&nbsp;Update Status</a>
    </div>
    <div id="dlgteknisi" class="easyui-dialog"
        modal ="true" 
        iconCls="icon-save" 
        closed="true"  
        buttons="#btn-teknisi" 
        resizable="true"
        style="width:250px;height:300px; padding:10px;">
          <form id="fmteknisi" method="post">
          <label>No RFC</label>
          <input type="text" id="sn" name="NomorRFC" /><br />
          <label>NIK</label>
          <input type="text" id="nik" name="NIK" onclick="javascript:openNIK()" /><br/>
          <label>Jabatan</label>
          <input type="text" name="Jabatan" /><br />
          </form>  
    </div>
    <div id="btn-teknisi">
    <a href="javascript:void(0)" onclick="javascript:saveAssignTeknisi()" class="btn btn-small btn-info">
    <i class="icon-bookmark icon-large"></i>&nbsp;Save</a>
    <a href="javascript:void(0)" onclick="javascript:$('#dlgteknisi').dialog('close')" class="btn btn-small btn-danger">
    <i class="icon-remove-sign icon-large"></i>&nbsp;Close</a>
    </div>
<!---------------------------------------------------------------------------------------->   
<!---------------------------------------------------------------------------------------->
<script type="text/javascript">
var url;
function updateSatusRfc(){
    	var row = $('#dgrfc').datagrid('getSelected');
    	if (row.NIK != null){
    		$.messager.confirm('Confirm', 'Status Akan di Update  ?', function(r) {
    			if (r){
    				$.post('<?= base_url('release/updateStatusRfc') ?>',{
    					id: row.NomorRFC, KodeStatus: 'AT'
    				}, function(result){
    					if (result.success) {
    					    $.messager.alert('Berhasil',result.success, 'info');
    						$('#dgrfc').datagrid({ url: '<?= base_url('release/getJsonRfc?status=OC') ?>'}, 'reload'); // reload the user data
    					}else{
    						$.messager.show({ // show error message
    							title: 'Error',
    							msg: result.msg
    						});
    					}
    				}, 'json');
    			}
    		});
    	}else{
    	   $.messager.alert('Info','Pilih Teknisi Belum dilakukan', 'info'); 
    	}
    }
function updateAssignTeknisi()
    {
      var row = $('#dgrfc').datagrid('getSelected');
    	if (row.NIK == null){
    	   	$('#dlgteknisi').dialog('open').dialog('setTitle', 'Assign Teknisi');
            $('#fmteknisi').form('clear');
    		$('#fmteknisi').form('load', row);
            url='<?=base_url('release/addTeknisi')?>';
           }else{
            $('#dlgteknisi').dialog('open').dialog('setTitle', 'Assign Teknisi');
    		$('#fmteknisi').form('load', row);
            url='<?=base_url('release/updateTeknisi')?>';
           }
    }
function saveAssignTeknisi()
    {
      $('#fmteknisi').form('submit',{
    		url: url,
    		onSubmit: function(){
    			return $(this).form('validate');
    		},
    		success: function(result) {
    			var result = eval('(' + result + ')');
    			if (result.success) {
    				$('#dlgteknisi').dialog('close');
    				    $.messager.alert('Berhasil',result.success, 'info');
    				$('#dgrfc').datagrid({
    					url: '<?= base_url('release/getJsonRfc?status=OC') ?>'
    				}, 'reload');
    			} else {
    			 $.messager.alert('Error',result.msg, 'error');
    			}
    		}
    	});  
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
function openNIK(){
                $('#nik').combogrid({  
                panelWidth:600,  
                url:'<?=base_url('master/teknisi/getJson')?>',  
                idField:'NIK',  
                textField:'NamaTeknisi',  
                mode:'local',  
                fitColumns:true,  
                columns:[[  
                    {field:'NIK',title:'N I K', align:'center', width:20},  
                    {field:'NamaTeknisi',title:'Nama Karyawan',align:'left',width:50}
                ]]  
            });  
            }
</script>  