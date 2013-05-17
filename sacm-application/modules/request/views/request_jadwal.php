<table id="dg" class="easyui-datagrid" title="Penjadwalan Request" style="height:500px;"
            url="<?=base_url('request/getJson?status=OC')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_rfc"
            singleSELECT = "true">  
        <thead>  
             <tr>  
                <th field="NomorRequest"   sortable="true" align="center" >No</th>  
                <th field="Peminta"        sortable="true" align="center" >Peminta</th> 
                <th field="CC"             sortable="true" align="center" >Cost Center</th>
                <th field="NomorSurat"     sortable="true" align="center" >No. Surat</th>  
                <th field="TanggalSurat"   sortable="true" align="center" >Tgl Surat</th>
                <th field="PenanggungJawab"sortable="true" align="left"   >P Jawab</th>  
                <th field="Pemakai"        sortable="true" align="left"   >Pemakai</th> 
                <th field="TeleponPemakai" sortable="true" align="center" >Tlp Pemakai</th>  
                <th field="KontakPerson"   sortable="true" align="center" >CP</th> 
                <th field="TeleponKontak"  sortable="true" align="center" >Tlp Kontak</th> 
                <th field="LokasiPasang"   sortable="true" align="center" >Lokasi</th>
                <th field="TanggalInput"   sortable="true" align="center" >Tgl Input</th>
                <th field="KodeService"    sortable="true" align="center" >Kode Service</th>
                <th field="KodeStatus"     sortable="true" align="center" >Status</th>  
            </tr>   
        </thead>  
    </table>  
    <!--div id="tb_rfc" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:configRFC()" class="btn btn-small btn-danger">
    <i class="icon-asterisk icon-large"></i>&nbsp;Configuration</a>
    <a href="javascript:void(0)" onclick="javascript:configUpdate()" class="btn btn-small btn-info">
    <i class="icon-bookmark icon-large"></i>&nbsp;Update Status</a>
    </div-->
    <div id="dlg" class="easyui-dialog"
        modal ="true" 
        iconCls="icon-save" 
        closed="true"  
        buttons="#dlg-buttons-config" 
        resizable="true"
        style="width:300px;height:430px; padding:10px;">
    <div class="well">
        <form id="fm" method="post">
         <label>Nomor RFC:</label>
         <input type="text" id="NomorRFC" class="span3" data-options="required:true" readonly="readonly" name="NomorRFC" />
         <label>Kode Konfigurasi:</label>
         <input type="text" id="code_config" class="span3" data-options="required:true" readonly="readonly" name="KodeKonfigurasi" onclick="javascript:openMasterConfig()" />
         <label>Konfigurasi:</label>
         <textarea class="easyui-validatebox span3" name="Konfigurasi" cols="3" rows="6"></textarea>
       </form>  
    </div>  
    </div>
         <div id="dlg-buttons-config" >  
                <a href="javascript:void(0)"  class="btn btn-small btn-info" onclick="javascript:saveConfig()"><i class="icon-save icon-large"> Save</i></a>  
                <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>  
         </div>
<script type="text/javascript">
    function configRFC()
    {
        var roww = $('#dg').datagrid('getSelected');
    	if (roww) {
    		$('#dlg').dialog('open').dialog('setTitle', 'RFC Configuration');
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
    				$('#dg').datagrid({
    					url: '<?= base_url('rfc/getJson') ?>'
    				}, 'reload');
    			} else {
    			 $.messager.alert('Error',result.msg, 'error');
    			}
    		}
    	});
    }
    $('#dg').datagrid({  
    view: detailview,  
    detailFormatter:function(index,row){  
        return '<div style="padding:2px"><table id="ddv-' + index + '" style="width:600px" ></table></div>';  
    },  
    onExpandRow: function(index,row){  
        $('#ddv-'+index).datagrid({  
            url:'request/getDetJson?NomorRequest='+ row.NomorRequest,  
            fitColumns:true,  
            singleSelect:true,  
            rownumbers:true,  
            loadMsg:'',  
            height:'auto',  
            columns:[[  
                {field:'KodeKonfigurasi',title:'Kode Konfigurasi',width:20,align:'center'},  
                {field:'Konfigurasi',title:'Konfigurasi',width:60},
                {field:'TanggalInput',title:'Tanggal',width:60}  
            ]],  
            onResize:function(){  
                $('#dg').datagrid('fixDetailRowHeight',index);  
            },  
            onLoadSuccess:function(){  
                setTimeout(function(){  
                    $('#dg').datagrid('fixDetailRowHeight',index);  
                },0);  
            }  
        });  
        $('#dg').datagrid('fixDetailRowHeight',index);  
    }  
});
function openMasterConfig(){
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
    </script>  