<div class="container">
<div id="tb_req" style="padding: 10px;">
<form class="form-horizontal" id="fm" method="post">
        <table class="table-striped table-hover" style="width: 100%;">
        <tr>
        <td>Kode Service</td>
        <td><input type="text" id="opserv" name="KodeService" class="span3" onclick="javascript:open_service()"/>
        </td>
        <td>Cost Center</td><td>
        <input type="text" id="cc" name="CC"/>
        </td>
        <td>Kontak Person</td><td>
        <input type="text" class="span3" name="KontakPerson"/></td>
        </tr>
        
        <tr>
        <td>Peminta (NIK)</td>
        <td><input type="text" class="span3" name="Peminta" id="nik_peminta" onclick="javascript:open_karyawan('peminta');pool='peminta'"/></td>
        <td>Penanggung Jawab</td>
        <td><input type="text" id="nik_pb" class="span3" name="PenanggungJawab" onclick="javascript:open_karyawan('pb');pool='pb'"/></td>
        <td>Telepon Kontak</td>
        <td><input type="text" class="span3" name="TeleponKontak"/></td>
        </tr>
        
        <tr>
        <td>No Surat</td>
        <td><input type="text" name="NomorSurat"/></td>
        <td>Pemakai</td>
        <td>
        <input type="text" id="nik_pemakai" class="span3" name="Pemakai" onclick="javascript:open_karyawan('pemakai');pool='pemakai'"/>
        </td>
        <td>Lokasi Pasang</td>
        <td><input type="text" name="LokasiPasang" id="lokasi" onclick="javascript:open_lokasi()"/></td>
        </tr>
        
        <tr>
        <td>Tanggal Surat</td>
        <td><input type="text" id="tanggal" name="TanggalSurat" class="easyui-datebox"/></td>
        <td>Telepon Pemakai</td>
        <td><input type="text" name="TeleponPemakai" class="span3" /></td>
        <td><!----></td>
        <td><!---->
        </td>
        </tr>
        
        <tr>
        <td colspan="9" >&nbsp;</td></tr>
        <tr>
        <td colspan="9" align="center">
        <a href="javascript:void(0)" onclick="javascript:save()" class="btn btn-primary">Save</a>
        <a href="javascript:void(0)" onclick="javascript:reset()" class="btn btn-warning">Clear</a>
        </td></tr>
        </table>
  </form>
  </div>
  <table id="dg-req" class="easyui-datagrid" title="Request" style="height:auto;"
            url="<?=base_url('request/getJson?status=O')?>"
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
                <!--th field="KodeStatus"     sortable="true" align="center" >Status</th-->
            </tr>  
        </thead>  
</table>
   <div id="dlg-cal" class="easyui-dialog" title="Calendar" closed="true" style="width: 195px;">
   <div id="cc" class="easyui-calendar"></div>    
   </div>
  <div id="dlg" class="easyui-dialog"  title="Karyawan" style="width:600px;height:430px;" 
            closed="true"  
            data-options="  
                iconCls: 'icon-user',  
                toolbar: '#dlg-toolbar',  
                buttons: '#dlg-buttons'">  
        <table id="dg" class="easyui-datagrid" style="width: auto; height: auto;"
            url="<?=base_url('master/karyawan/getJson')?>"
            iconCls ="icon-app"  
            rownumbers ="true" 
            pagination ="false"
            fitColumns ="true"
            toolbar ="#tb_absen"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <!--th field="id" width="10%" sortable="true">Absen ID</th-->  
                <th field="karyawan_nik" width="10%">NIK</th> 
                <th field="karyawan_nama" width="40%">Nama</th> 
            </tr>  
        </thead>  
    </table>     
    </div>  
    <div id="dlg-toolbar" style="padding:2px 0">  
        <table cellpadding="0" cellspacing="0" style="width:100%">  
            <tr>   
                <td style="text-align:right;padding-right:2px">  
                    <input class="easyui-searchbox" data-options="prompt:'Please input somthing'" style="width:150px"/>  
                </td>  
            </tr>  
        </table>  
    </div>  
    <div id="dlg-buttons">  
        <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:getSelected()">Select</a>  
        <a href="javascript:void(0)" class="easyui-linkbutton" onclick="javascript:$('#dlg').dialog('close');getNik('123')">Close</a>  
    </div>
</div>

<script type="text/javascript">
    var pool;
    function reset(){
        	$('#fm').form('clear');    
    }
    function save(){
		$('#fm').form('submit', {
			url:'<?= base_url('request/add') ?>',
			onSubmit: function(){
			return $(this).form('validate');
			},
            success: function(result){
				var result = eval('(' + result + ')');
				if (result.success) {                
                $.messager.alert('Succes', result.success, 'info');
                    $('#dgrfc').datagrid({
    					url: '<?= base_url('request/getJson') ?>'
    				}, 'reload');
				}else{
				    $.messager.alert('Error',result.msg, 'error');	
				}
			}
		});
	}
    function open_karyawan(nik){
        $('#nik_'+nik).combogrid({  
            panelWidth:700,
            panelHeight:310,
            pagination:true,  
            url: '<?=base_url('master/karyawan/getJson')?>',  
            idField:'karyawan_nik',  
            textField:'karyawan_nama',  
            mode:'local',  
            fitColumns:true,  
            columns:[[  
                {field:'karyawan_nik',title:'NIK', align:'left', sortable:'true', width:15},  
                {field:'karyawan_nama',title:'Nama',align:'left', sortable:'true', width:40},
                {field:'karyawan_unit',title:'Unit', sortable:'true', width:40}
            ]]  
        });  
    }  
    function open_lokasi(){
        $('#lokasi').combogrid({  
            panelWidth:500,  
            url: '<?=base_url('master/lokasi/getJson')?>',  
            idField:'lokasi_kode',  
            textField:'lokasi_desc',  
            mode:'local',  
            fitColumns:true,  
            columns:[[  
                {field:'lokasi_kode',title:'Kode Lokasi',sortable:'true', align:'center', width:5},  
                {field:'lokasi_desc',title:'Deskripsi',sortable:'true', align:'left',width:25}
            ]]  
        });  
    }  
    function open_service(){
        $('#opserv').combogrid({  
            panelWidth:500,  
            url: '<?=base_url('master/service_jenis/getJson')?>',  
            idField:'KodeService',  
            textField:'Nama',  
            mode:'local',  
            fitColumns:true,  
            columns:[[  
                {field:'KodeService',title:'Kode', sortable:'true', align:'left', width:5},  
                {field:'Nama',title:'Deskripsi', sortable:'true', align:'left',width:25}
            ]]  
        });  
    }  
    </script>