<script type="text/javascript">
$('#asset').attr('selected', true);
</script>
<table id="dg" class="easyui-datagrid" title="Asset" style="width:auto;"
            url="<?=base_url('asset/getJson')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_asset"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="SerialNumber" width="25%" sortable="true" align="center">Serial Number</th>  
                <th field="NomorKontrak" width="25%" sortable="true" align="center">Nomor Kontrak</th> 
                <th field="KodeService"  width="25"  sortable="true" align="center">Kode Service</th>
                <th field="KodeAlokasi"  width="25%" sortable="true" align="center">Kode Alokasi</th> 
            </tr>  
        </thead>  
    </table>  
    <div id="tb_asset" style="padding: 5px;">
    <a href="<?=base_url('master/status/getJson')?>" class="btn btn-small btn-danger"><i class="icon-plus-sign"></i>&nbsp;Add</a>
    <a href="<?=base_url('master/status/getJson')?>" class="btn btn-small btn-info"><i class="icon-edit"></i>&nbsp;Edit</a>
    <a href="<?=base_url('master/status/getJson')?>" class="btn btn-small btn-success"><i class="icon-remove-sign"></i>&nbsp;Remove</a>
    </div>   