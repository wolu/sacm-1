<script type="text/javascript">
$('#service').attr('selected', true);
</script>
<div class="container">
<table id="dg" class="easyui-datagrid table-striped" title="Service" style="width: auto; height: auto; margin: auto;"
            url="<?=base_url('service/getJson')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_service"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="NomorService"    sortable="true" align="center">Kode</th>  
                <th field="Peminta"         sortable="true" align="left">Peminta</th> 
                <th field="CC"              sortable="true" align="center">Cost Center</th>
                <th field="NomorSurat"      sortable="true" align="center">No Surat</th> 
                <th field="TanggalSurat"    sortable="true" align="center">Tgl Surat</th>
                <th field="PenanggungJawab" sortable="true" align="left">P.Jawab</th> 
                <th field="Pemakai"         sortable="true" align="left">Pemakai</th>
                <th field="TeleponPemakai"  sortable="true" align="center">Tlp Pemakai</th> 
                <th field="KontakPerson"    sortable="true" align="center">Kontak Person</th>
                <th field="TeleponKontak"   sortable="true" align="center">Status</th> 
                <th field="LokasiPasang"    sortable="true" align="left">Lokasi Pasang</th>
                <th field="TanggalInput"    sortable="true" align="center">Tgl Input</th> 
                <th field="KodeService"     sortable="true" align="center">Kode Service</th> 
                <th field="KodeStatus"      sortable="true" align="center">Kode Status</th> 
                <th field="IncidentId"      sortable="true" align="center">Incident ID</th> 
                <th field="TanggalPemasangan" sortable="true" align="center">Tgl Pemasangan</th> 
                <th field="NomorRequest"    sortable="true" align="center">No Request</th> 
                <th field="NomorItemService" sortable="true" align="center">No Item Service</th> 
                <th field="ClassService"    
                sortable="true" align="center">Class Service</th> 
            </tr>  
        </thead>  
</table> 
<div id="tb_service">
<form method="POST" id="fm" style="margin:10px">
<table  class="table-striped table-hover" style="width: 100%;">
  <tr>
    <td width="12%">Nomor Service</td>
    <td width="1%">:</td>
    <td width="20%"><input type="text" class="input-mini span2" name="NomorService" />&nbsp;
    <a href="javascript:void(0)" class="btn btn-mini" onclick="javascript:genSERV()"><i class="icon-copy"></i></a></td>
    <td width="11%">Pemakai</td>
    <td width="1%">:</td>
    <td width="20%"><input type="text" class="input-mini span2" name="Pemakai" /></td>
    <td width="13%">Kode Service</td>
    <td width="1%">:</td>
    <td width="20%"><input type="text" class="input-mini span2" name="KodeService" /></td>
  </tr>
  <tr>
    <td>Peminta</td>
    <td>:</td>
    <td><input type="text" class="input-mini span2" name="Peminta" /></td>
    <td>Telepon Pemakai</td>
    <td>:</td>
    <td><input type="text" class="input-mini span2" name="TeleponPemakai" /></td>
    <td>Kode Status</td>
    <td>:</td>
    <td><input type="text" class="input-mini span2" name="KodeStatus" /></td>
  </tr>
  <tr>
    <td>Cost Center</td>
    <td>:</td>
    <td><input type="text" class="input-mini span2" name="CC" /></td>
    <td>Kontak Person</td>
    <td>:</td>
    <td><input type="text" class="input-mini span2" name="KontakPerson" /></td>
    <td>Incident ID</td>
    <td>:</td>
    <td><input type="text" class="input-mini span2" name="IncidentId" /></td>
  </tr>
  <tr>
    <td>Nomor Surat</td>
    <td>:</td>
    <td><input type="text" class="input-mini span2" name="NomorSurat" /></td>
    <td>Telepon Kontak</td>
    <td>:</td>
    <td><input type="text" class="input-mini span2" name="TeleponKontak" /></td>
    <td>Tgl Pemasangan</td>
    <td>:</td>
    <td><input type="text" class="easyui-datebox input-mini span2" name="TanggalPemasangan" /></td>
  </tr>
  <tr>
    <td>Tanggal Surat</td>
    <td>:</td>
    <td><input type="text" class="easyui-datebox input-mini span2" name="TanggalSurat" /></td>
    <td>Lokasi Pasang</td>
    <td>:</td>
    <td><input type="text" class="input-mini span2" name="LokasiPasang" /></td>
    <td>Nomor Request</td>
    <td>:</td>
    <td><input type="text" class="input-mini span2" name="NomorRequest" /></td>
  </tr>
  <tr>
    <td>Penanggung Jawab</td>
    <td>:</td>
    <td><input type="text" class="input-mini span2" name="PenanggungJawab" /></td>
    <td>Tanggal Input</td>
    <td>:</td>
    <td><input type="text" class="easyui-datebox input-mini span2" name="TanggalInput" /></td>
    <td>Nomor Item Service</td>
    <td>:</td>
    <td><input type="text" class="input-mini span2" name="NomorItemService" /></td>
  </tr>
  <tr>
    <td colspan="9" style="text-align: center; padding-top: 10px;">
    <a href="javascript:void(0)" class="btn btn-small btn-success" onclick="javascript:save()"><i class="icon-plus-sign"></i>&nbsp;Add</a>
    <a href="javascript:void(0)" class="btn btn-small btn-info" onclick="javascript:edit()"><i class="icon-edit"></i>&nbsp;Edit</a>
    <a href="javascript:void(0)" class="btn btn-small btn-danger" onclick="javascript:remove()"><i class="icon-remove-sign"></i>&nbsp;Remove</a>
    </td>
  </tr>
</table>
</form>
<input type="text" class="easyui-searchbox" data-options="prompt:' Please Input Value',searcher:doSearch" style="width:300px"/>
</div>
</div>   
<script type="text/javascript">
function doSearch(value){
        $('#dg').datagrid('load',{    
        cari:value  
    });   	
}
function save(){
  $('#fm').form('submit', {
			url:'<?= base_url('service/add') ?>',
			onSubmit: function(){
			return $(this).form('validate');
			},
            success: function(result){
				var result = eval('(' + result + ')');
				if (result.success) {
				    $.messager.alert('Berhasil',result.success, 'info');
                    $('#dgrfc').datagrid({
    					url: '<?= base_url('service/getJson') ?>'
    				}, 'reload');
				}else{
				    $.messager.alert('Error',result.msg, 'error');	
				}
			}
		});  
}
function edit(){
    
}
function remove(){
    
}
</script>