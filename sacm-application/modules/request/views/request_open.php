<table id="dgopen" class="easyui-datagrid" style="height:500px;"
            url="<?=base_url('request/getJson?status=O')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_rfc"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="NomorRequest"   sortable="true" align="center">No</th>  
                <th field="Peminta"        sortable="true" align="center">Peminta</th> 
                <th field="CC"             sortable="true" align="center">Cost Center</th>
                <th field="NomorSurat"     sortable="true" align="center">No. Surat</th>  
                <th field="TanggalSurat"   sortable="true" align="center">Tgl Surat</th>
                <th field="PenanggungJawab"sortable="true" align="left">P Jawab</th>  
                <th field="Pemakai"        sortable="true" align="left">Pemakai</th> 
                <th field="TeleponPemakai" sortable="true" align="center">Tlp Pemakai</th>  
                <th field="KontakPerson"   sortable="true" align="center">CP</th> 
                <th field="TeleponKontak"  sortable="true" align="center">Tlp Kontak</th> 
                <th field="LokasiPasang"   sortable="true" align="center">Lokasi</th>
                <th field="TanggalInput"   sortable="true" align="center">Tgl Input</th>
                <th field="KodeService"    sortable="true" align="center">Kode Service</th>
                <th field="KodeStatus"     sortable="true" align="center">Status</th> 
            </tr>  
        </thead>  
    </table>  
    <div id="tb_rfc" style="padding: 5px;">
    <a href="javascript:void(0)" onclick="javascript:configUpdate()" class="btn btn-small btn-info">
    <i class="icon-bookmark icon-large"></i>&nbsp;Update Status</a>
    </div>
    <script type="text/javascript">
        function configUpdate() {
        	var row = $('#dgopen').datagrid('getSelected');
        	if(row) {
        		$.messager.confirm('Confirm', 'Status Akan di Update  ?', function(r) {
        			if (r){
        				$.post('<?=base_url('request/updateStatus')?>',{
        					id: row.NomorRequest, KodeStatus: 'OC'
        				}, function(result){
        					if (result.success) {
        					    $.messager.alert('Berhasil',result.success, 'info');
        						$('#dgopen').datagrid({ url: '<?=base_url('request/getJson?status=O') ?>'}, 'reload'); // reload the user data
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
    function doSearch(value){
            $('#tt').datagrid('load',{    
            cari:value  
        });   	
          }
    </script>  