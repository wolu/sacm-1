<table id="dg" class="easyui-datagrid" title="Acceptance Request" style="height:500px;"
            url="<?=base_url('release/getJsonReq?status=IC')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_req"
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
    <div id="tb_req" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:updateSatusReq()" class="btn btn-small btn-info">
    <i class="icon-bookmark icon-large"></i>&nbsp;Update Status</a>
    </div> 
<script type="text/javascript">
function updateSatusReq() {
    	var row = $('#dg').datagrid('getSelected');
    	if (row) {
    		$.messager.confirm('Confirm', 'Status Akan di Update  ?', function(r) {
    			if (r){
    				$.post('<?=base_url('release/updateStatusReq')?>',{
    					id: row.NomorRequest, KodeStatus: 'OC'
    				}, function(result){
    					if (result.success) {
    					    $.messager.alert('Berhasil',result.success, 'info');
    						$('#dg').datagrid({ url: '<?=base_url('release/getJsonReq?status=O') ?>'}, 'reload'); // reload the user data
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
    $('#dg').datagrid({  
    view: detailview,  
    detailFormatter:function(index,row){  
        return '<div style="padding:2px"><table id="ddv-' + index + '" style="width:600px" ></table></div>';  
    },  
    onExpandRow: function(index,row){  
        $('#ddv-'+index).datagrid({  
            url:'release/getDetJsonReq?NomorRequest='+ row.NomorRequest,  
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
</script>  