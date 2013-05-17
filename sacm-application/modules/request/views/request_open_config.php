<table id="dgoc" class="easyui-datagrid" title="Review Request"  style="height:500px;"
            url="<?=base_url('request/getJson?status=OC')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_open"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="NomorRequest"   width="10" sortable="true" align="center" >No</th>  
                <th field="Peminta"        width="10" sortable="true" align="center" >Peminta</th> 
                <th field="CC"             width="10" sortable="true" align="center" >Cost Center</th>
                <th field="NomorSurat"     width="15" sortable="true" align="center" >No. Surat</th>  
                <th field="TanggalSurat"   width="20" sortable="true" align="center" >Tgl Surat</th>
                <th field="PenanggungJawab"width="10" sortable="true" align="left"   >P Jawab</th>  
                <th field="Pemakai"        width="10" sortable="true" align="left"   >Pemakai</th> 
                <th field="TeleponPemakai" width="10" sortable="true" align="center" >Tlp Pemakai</th>  
                <th field="KontakPerson"   width="15" sortable="true" align="center" >CP</th> 
                <th field="TeleponKontak"  width="10" sortable="true" align="center" >Tlp Kontak</th> 
                <th field="LokasiPasang"   width="15" sortable="true" align="center" >Lokasi</th>
                <th field="TanggalInput"   width="20" sortable="true" align="center" >Tgl Input</th>
                <th field="KodeService"    width="10" sortable="true" align="center" >Kode Service</th>
                <th field="KodeStatus"     width="10" sortable="true" align="center" >Status</th>  
            </tr>  
        </thead>  
    </table>
 <!--------------------------------------------------------------------------------------------------->   
    <div id="tb_open" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:configRequest()" class="btn btn-small btn-danger">
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
        <label>Nomor Request:</label>
        <input type="text" id="NomorRequest" class="span3"  readonly="readonly" name="NomorRequest" />
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
    function configRequest()
    {
        var roww = $('#dgoc').datagrid('getSelected');
    	if (roww) {
    		$('#dlg').dialog('open').dialog('setTitle', 'RFC Configuration');
    		$('#fm').form('load', roww);
    		url = "<?php echo base_url('request/addConfig');?>";
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
    					url: '<?=base_url('request/getJson?status=OC')?>'
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
            url:'request/getDetJson?NomorRequest='+ row.NomorRequest,  
            fitColumns:true,  
            singleSelect:true,  
            rownumbers:true,  
            loadMsg:'',  
            height:'auto',  
            columns:[[  
                {field:'KodeKonfigurasi',title:'Kode Konfigurasi',width:20,align:'center'},  
                {field:'Konfigurasi',title:'Konfigurasi',width:60},
                {field:'TanggalInput',title:'Tanggal',width:30}  
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