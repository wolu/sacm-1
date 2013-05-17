<script type="text/javascript">
$('#rfc').attr('selected', true);
</script>
<div class="container">
<div id="tb_rfc">
<form method="post" id="fm" style="margin:10px">
        <table class="table table-striped table-hover">
        <tr>
        <td>Nomor Service</td><td>:</td>
        <td><input id="NomorService" type="text" readonly="readonly"   name="NomorService"  maxlength="50" class="span3" />
        <a href="javascript:void(0)" class="btn btn-mini" onclick="$('#dlg').dialog('open')"><i class="icon-copy"></i></a>
        </td>
        <td>Type Change</td><td>:</td>
        <td><input id="CodeChange" type="text" readonly="readonly" name="CodeChange" maxlength="10" onclick="javascript:openKodeChange()" />
        </tr>
        <tr>
        <td>Serial Number </td><td>:</td>
        <td><input id="SerialNumber" type="text" readonly="readonly" name="SerialNumber" onclick="javascript:openSerialNumber()" maxlength="30" class="span3"/>
        </td>
        <td><!-- RFC Id-->Tanggal RFC</td><td>:</td><td>
        <input  type="text" name="TanggalRFC" class="easyui-datebox"/>
        <!--input id="RFCId" type="text" name="RFCId" placeholder="Nomor Ticket Codesk" maxlength="10" /--></td>
        </td>
        </tr>
        <tr>
        <td colspan="6"><a href="javascript:void(0)" class="btn btn-primary" onclick="javascript:save()">Save</a>&nbsp;
        <a href="javascript:void(0)" class="btn btn-danger" onclick="javascript:reset()">Reset</a><!--input id="TanggalTarget" type="text" name="TanggalTarget" class="easyui-datetimebox"  /--></td>
         </tr>
        </table>        
</form>
</div>
<table id="dgrfc" class="easyui-datagrid" title="Realease For Change (RFC)" style="height:auto;"
            url="<?= base_url('rfc/getJson?status=O') ?>"
            iconCls ="icon-save icon-large"
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_rfc"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="NomorRFC"     width="5" sortable="true" align="center">Nomor RFC</th>  
                <th field="NomorService" width="5" sortable="true" align="center">No Service</th> 
                <th field="SerialNumber" width="10"sortable="true" align="center">Serial Number</th>
                <th field="TanggalRFC"   width="10"sortable="true" align="center">Tanggal RFC</th>  
                <th field="TanggalInput" width="10"sortable="true" align="center">Tanggal Input</th>
                <th field="CodeChange"   width="5" sortable="true" align="center">KodeChange</th>  
                <!--th field="RFCId"        width="5" sortable="true" align="center">RFC ID</th> 
                <th field="TanggalTarget"width="10"sortable="true" align="center">Tgl Target</th>  
                <th field="KodeStatus"   width="3" sortable="true" align="center">Status</th-->  
            </tr>  
        </thead>  
    </table> 
</div>
<div id="dlg" class="easyui-dialog"  title="Service" style="width:800px;" 
            closed="true"  
            data-options="  
                iconCls: 'icon-user',  
                toolbar: '#dlg-toolbar',  
                buttons: '#dlg-buttons'">  
        <table id="dg" class="easyui-datagrid" style="width: auto; height: auto;"
            url="<?= base_url('service/getJson') ?>"
            iconCls ="icon-app"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="NomorService" sortable="true" align="center" width="auto">Kode</th>  
                <th field="Peminta" sortable="true" align="left">Peminta</th> 
                <th field="CC" sortable="true" align="center">Cost Center</th>
                <th field="NomorSurat" sortable="true" align="center">No Surat</th> 
                <th field="TanggalSurat" sortable="true" align="center">Tgl Surat</th>
                <th field="PenanggungJawab" sortable="true" align="left">P.Jawab</th> 
                <th field="Pemakai" sortable="true" align="left">Pemakai</th>
                <th field="TeleponPemakai" sortable="true" align="center">Tlp Pemakai</th> 
                <th field="KontakPerson" sortable="true" align="center">Kontak Person</th>
                <th field="TeleponKontak" sortable="true" align="center">Status</th> 
                <th field="LokasiPasang" sortable="true" align="center">Lokasi Pasang</th>
                <th field="TanggalInput" sortable="true" align="center">Tgl Input</th> 
                <th field="KodeService" sortable="true" align="center">Kode Service</th> 
                <th field="KodeStatus" sortable="true" align="center">Kode Status</th> 
                <th field="IncidentId" sortable="true" align="center">Incident ID</th> 
                <th field="TanggalPemasangan" sortable="true" align="center">Tgl Pemasangan</th> 
                <th field="NomorRequest" sortable="true" align="center">No Request</th> 
                <th field="NomorItemService" sortable="true" align="center">No Item Service</th>
            </tr>  
        </thead>  
    </table>     
    </div> 
<div id="tb">
<input class="easyui-searchbox" prompt="Please Input Value" searcher="doSearch" style="width:300px" />
</div>
  <div id="dlg-buttons">  
        <a href="javascript:void(0)" class="btn btn-info" onclick="javascript:getService()">Select</a>  
        <a href="javascript:void(0)" class="btn btn-danger" onclick="javascript:$('#dlg').dialog('close');">Close</a>  
  </div> 
<script type="text/javascript">
function doSearch(value){
        $('#dg').datagrid('load',{    
        cari:value  
    });   	
}
function reset(){
        	$('#fm').form('clear');     
    }
   function save(){
		$('#fm').form('submit', {
			url:'<?= base_url('rfc/add') ?>',
			onSubmit: function(){
			return $(this).form('validate');
			},
            success: function(result){
                $('#fm').form('clear'); 
				var result = eval('(' + result + ')');
				if (result.success){                
                $.messager.alert('Succes', result.success, 'info');
                    $('#dgrfc').datagrid({
    					url: '<?= base_url('rfc/getJson?status=O') ?>'
    				}, 'reload');
				}else{
				    $.messager.alert('Error',result.msg, 'error');	
				}
			}
		});
	}
    
 function openKodeChange(){
            $('#CodeChange').combogrid({  
            panelWidth:500,  
            url: '<?= base_url('master/tipe_change/getJson') ?>',  
            idField:'CodeChange',  
            textField:'Desc',  
            mode:'local',  
            fitColumns:true,  
            columns:[[  
                {field:'CodeChange',title:'Kode Change', align:'left', width:10},  
                {field:'Desc',title:'Deskripsi',align:'left',width:40}
            ]]  
        });  
        }
        function openKodeStatus(){
            $('#KodeStatus').combogrid({  
            panelWidth:500,  
            url: '<?= base_url('master/status/getJson') ?>',  
            idField:'KodeStatus',  
            textField:'Status',  
            mode:'local',  
            fitColumns:true,  
            columns:[[  
                {field:'KodeStatus',title:'Kode Status', align:'left', width:10},  
                {field:'Status',title:'Status',align:'left',width:40}
            ]]  
        });  
        }
 function getService(){
        var row = $('#dg').datagrid('getSelected');  
        if (row){  
            $('#NomorService').val(row.NomorService);
            $('#SerialNumber').val(row.NomorItemService);
        }
        $('#dlg').dialog('close')  
    }
    </script>