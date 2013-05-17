<script type="text/javascript">
$('#rfc').attr('selected', true);
</script>
<table id="dgoc" class="easyui-datagrid" title="Review RFC"  style="height:500px;"
            url="<?=base_url('rfc/getJson?status=OC')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_open"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="NomorRFC"     width="7" sortable="true" align="center">No RFC </th>  
                <th field="NomorService" width="5" sortable="true" align="center">No Service </th> 
                <th field="SerialNumber" width="10"sortable="true" align="center">Serial Number </th>
                <th field="TanggalRFC"   width="10"sortable="true" align="center">Tanggal RFC </th>  
                <th field="TanggalInput" width="10"sortable="true" align="center">Tanggal Input </th>
                <th field="CodeChange"   width="5" sortable="true" align="center">Kode Change </th>  
                <!--th field="RFCId"        width="5" sortable="true" align="center">RFC ID </th> 
                <th field="TanggalTarget"width="10"sortable="true" align="center">Tgl Target </th-->  
                <th field="KodeStatus"   width="3" sortable="true" align="center">Status </th>  
            </tr>  
        </thead>  
    </table>
 <!--------------------------------------------------------------------------------------------------->   
    <div id="tb_open" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:configRFC()" class="btn btn-small btn-danger">
    <i class="icon-asterisk icon-large"></i>&nbsp;Configuration</a>
    </div>
        <div id="dlg" class="easyui-dialog"
        modal ="true" 
        iconCls="icon-save" 
        closed="true"  
        buttons="#dlg-buttons-config" 
        resizable="true"
        style="width:250px;height:300px; padding:10px;">
        <form id="fm" method="post">
        <table class="table-striped" style="width: 100%;">
        <tr>
        <td>
        <label>Nomor RFC:</label>
        <input type="text" id="NomorRFC" class="span3"  readonly="readonly" name="NomorRFC" />
        </td>
        </tr>
        <tr>
        <td>
        <label>Kode Konfigurasi:</label>
         <input type="text" id="code_config" class="span3"  name="KodeKonfigurasi"  onclick="javascript:openMasterConfig()" />
        </td>
        </tr>
        <tr>
        <td>
        <label>Konfigurasi:</label>
        <input type="text" id="config_detil" class="span3" name="Konfigurasi" onclick="javascript:openMasterConfigDetil()" />
        </td>
        </tr>
        </table>  
        </div>
         <div id="dlg-buttons-config" >
         <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:saveConfig()"><i class="icon-save icon-large"> Save</i></a>  
         <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>  
         </div>
<!---------------------------------------------------------------------------------------->
<script type="text/javascript">
    var url;
    function configRFC()
    {
        var roww = $('#dgoc').datagrid('getSelected');
    	if (roww) {
    		$('#dlg').dialog('open').dialog('setTitle', 'RFC Configuration');
   			$('#fm').form('clear');
            $('#fm').form('load', roww);
    		url = "<?php echo base_url('rfc/addConfig');?>";
    	}
    }
    function saveConfig(){
        $('#fm').form('submit',{
    		url: url,
    		onSubmit: function(){
    			return $(this).form('validate');
    		},
    		success: function(result) {
    			var result = eval('(' + result + ')');
    			if (result.success) {
    				$('#dlg').dialog('close');
    				    $.messager.alert('Berhasil',result.success, 'info');
    				$('#dgoc').datagrid({
    					url: '<?=base_url('rfc/getJson?status=OC')?>'
    				}, 'reload');
    			}else{
    			 $.messager.alert('Error',result.msg, 'error');
    			}
    		}
    	});
    }
    $('#dgoc').datagrid({  
    view: detailview,  
    detailFormatter:function(index,row){  
        return '<div style="padding:2px"><table id="ddvoc-' + index + '" style="width:600px" ></table></div>';  
    },  
    onExpandRow: function(index,row){  
        $('#ddvoc-'+index).datagrid({  
            url:'rfc/getDetJson?NomorRFC='+ row.NomorRFC,  
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
                $('#dgoc').datagrid('fixDetailRowHeight',index);  
            },  
                onLoadSuccess:function(){  
                    setTimeout(function(){  
                        $('#dgoc').datagrid('fixDetailRowHeight',index);  
                    },0);  
                }  
            });  
            $('#dgoc').datagrid('fixDetailRowHeight',index);  
        }  
    });
    
    function openMasterConfig(value){
                $('#code_config').combogrid({  
                panelWidth:500,  
                url: '<?= base_url('master/master_config/getJson') ?>',  
                idField:'KodeKonfigurasi',  
                textField:'NamaKonfigurasi',  
                mode:'local',  
                fitColumns:true,  
                columns:[[  
                    {field:'KodeKonfigurasi',title:'Kode', align:'center', width:10},  
                    {field:'NamaKonfigurasi',title:'Nama',align:'left',width:40},
                    {field:'Deskripsi',title:'Deskripsi',align:'left',width:40}
                ]]  
            });
            }
    function openMasterConfigDetil(){
                var g = $('#code_config').combogrid('grid');	// get datagrid object
                var r = g.datagrid('getSelected');	// get the selected row
                $('#config_detil').combogrid({  
                panelWidth:600,  
                url:'<?=base_url('rfc/getJsonConfigDet?cek=')?>'+r.KodeKonfigurasi,  
                idField:'NamaKonfigDetil',  
                textField:'NamaKonfigDetil',  
                mode:'local',  
                fitColumns:true,  
                columns:[[  
                    {field:'KodeKonfig',title:'Kode', align:'center', width:10},  
                    {field:'NamaKonfigDetil',title:'Nama',align:'left',width:50},
                    {field:'Deskripsi',title:'Deskripsi',align:'left',width:40}
                ]]  
            });  
            }
    </script>  