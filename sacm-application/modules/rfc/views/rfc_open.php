<table id="dgopen" class="easyui-datagrid" style="height:500px;"
            url="<?=base_url('rfc/getJson?status=O')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_rfc"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="NomorRFC"     width="7" sortable="true" align="center">Nomor RFC</th>  
                <th field="NomorService" width="5" sortable="true" align="center">No Service</th> 
                <th field="SerialNumber" width="10"sortable="true" align="center">Serial Number</th>
                <th field="TanggalRFC"   width="10"sortable="true" align="center">Tanggal RFC</th>  
                <th field="TanggalInput" width="10"sortable="true" align="center">Tanggal Input</th>
                <th field="CodeChange"   width="5" sortable="true" align="center">Kode Change</th>  
                <!--th field="RFCId"        width="5" sortable="true" align="center">RFC ID</th> 
                <th field="TanggalTarget"width="10"sortable="true" align="center">Tgl Target</th-->  
                <th field="KodeStatus"   width="3" sortable="true" align="center">Status</th>  
            </tr>  
        </thead>  
    </table>  
    <div id="tb_rfc" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:configUpdate()" class="btn btn-small btn-info">
    <i class="icon-bookmark icon-large"></i>&nbsp;Update Status</a>
    <a href="javascript:void(0)" onclick="javascript:configRejected()" class="btn btn-small btn-danger">
    <i class="icon-remove icon-large"></i>&nbsp;Cancel</a>
    </div>
    <div id="ganti" class="easyui-dialog"
        modal ="true" 
        iconCls="icon-save" 
        closed="true"  
        buttons="#dlg-ganti" 
        resizable="true"
        style="width:500px;height:300px; padding:10px;">
        <form id="fmganti" method="post" enctype="">
        <table class="table-striped" style="width: 100%;">
        <tr>
        <td><label>Nomor RFC:</label>
        <input type="text" id="NomorRFC" class="span3"  readonly="readonly" name="NomorRFC" />
        </td>
        <td><label>Nomor Service:</label>
        <input type="text"  class="span3"  readonly="readonly" name="NomorService" />
        </td>
        </tr>
        <tr>
        <td><label>Serial Number Old:</label>
        <input type="text"   class="span3"  readonly="readonly" name="SerialNumber" />
        </td>
        <td><label>Status Alokasi:</label>
        <input type="text" id="status_alokasi"  class="span3"  name="KodeAlokasi" onclick="javascript:openStatusAlokasi()" />
        </td>
        </tr>
        <tr>
        <td>
        <label>Serial Number New:</label>
        <input type="text" id="sn_asset" class="span3" name="SerialAsset" onclick="javascript:openAsset()"/>
        </td>
        <td></td>
        </tr>
        </table>
        </form>  
        </div>
         <div id="dlg-ganti" >
         <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:saveUpdateSN()">
         <i class="icon-save icon-large"> Save</i></a>  
         <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:$('#ganti').dialog('close')">Cancel</a>  
         </div>
    
<script type="text/javascript">
var url;
    function configUpdate() {
    	var row = $('#dgopen').datagrid('getSelected');
    	if (row.CodeChange != 1) {
    		$.messager.confirm('Confirm', 'Status Akan di Update  ?', function(r) {
    			if (r){
    				$.post('<?= base_url('rfc/updateConfig') ?>',{
    					id: row.NomorRFC, KodeStatus: 'OC'
    				}, function(result){
	  		  		if (result.success) {
    					    $.messager.alert('Berhasil',result.success, 'info');
    						$('#dgopen').datagrid({ url: '<?= base_url('rfc/getJson?status=O') ?>'}, 'reload'); // reload the user data
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
    		$('#ganti').dialog('open').dialog('setTitle', 'RFC || Penggantian Serial Number');
            $('#fmganti').form('clear');
    		$('#fmganti').form('load', row);
            url = '<?=base_url('rfc/updateSerialNumber')?>';
    	}
    }
function configRejected()
{
 var row = $('#dgopen').datagrid('getSelected');
    	if (row.CodeChange != 1) {
    		$.messager.confirm('Confirm', 'RFC Rejected ?', function(r) {
    			if (r){
    				$.post('<?= base_url('rfc/updateConfig') ?>',{
    					id: row.NomorRFC, KodeStatus: 'R'
    				}, function(result){
	  		  		if (result.success) {
    					    $.messager.alert('Berhasil',result.success, 'info');
    						$('#dgopen').datagrid({ url: '<?= base_url('rfc/getJson?status=O') ?>'}, 'reload'); // reload the user data
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
function saveUpdateSN(){
        $('#fmganti').form('submit',{
    		url:url,
    		onSubmit: function(){
    			return $(this).form('validate');
    		},
    		success: function(result) {
    			var result = eval('(' + result + ')');
    			if (result.success) {
    				$('#ganti').dialog('close');
				    $.messager.alert('Berhasil',result.success, 'info');
    				$('#dgopen').datagrid({
    					url: '<?= base_url('rfc/getJson?status=O') ?>'
    				}, 'reload');
    			} else {
    			 $.messager.alert('Error',result.msg, 'error');
    			}
    		}
    	});
    }
function openStatusAlokasi(){
                $('#status_alokasi').combogrid({  
                panelWidth:600,  
                url        :'<?=base_url('master/status_asset/getJsonCek?s1=OB&s2=OR')?>',  
                idField    :'KodeAlokasi',  
                textField  :'NamaAlokasi',  
                mode:'local',  
                fitColumns:true,  
                columns:[[  
                    {field:'KodeAlokasi',title:'Kode', align:'center', width:20},  
                    {field:'NamaAlokasi',title:'Nama',align:'left',width:30}
                ]]  
            });  
            }  
  function openAsset(){
                $('#sn_asset').combogrid({  
                panelWidth:600,  
                url        :'<?=base_url('asset/asset/getJsonAlokasi?status=SA')?>', 
                pagination :true, 
                idField    :'SerialNumber',  
                textField  :'SerialNumber',  
                mode:'local',  
                fitColumns:true,  
                columns:[[  
                    {field:'SerialNumber',title:'Serial Number', align:'center', width:20},  
                    {field:'NomorKontrak',title:'No Kontrak',align:'left',width:30},
                    {field:'KodeService',title:'Service',align:'left',width:10}
                ]]  
            });  
            }  
function doSearch(value){
        $('#tt').datagrid('load',{    
        cari:value  
    });   	
      }

    </script>  