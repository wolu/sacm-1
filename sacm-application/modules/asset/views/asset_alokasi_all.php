<script type="text/javascript">
$('#asset').attr('selected', true);
</script>
<table id="dg" class="easyui-datagrid" title="Asset Alokasi" style="width:auto;"
            url="<?=base_url('asset/asset_alokasi/getJson')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            toolbar ="#tb_asset_alokasi"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="NomorAsal" width="25%" sortable="true" align="center">No Asal</th>  
                <th field="SerialNumber" width="25%" align="center">Serial Number</th> 
                <th field="KodeAlokasi" width="25" align="center">Kode Alokasi</th>
                <th field="Tanggal" width="25%" align="center">Tanggal</th> 
                <th field="NomorService" width="25%" align="center">Service</th> 
            </tr>  
        </thead>  
    </table>  
    <div id="tb_asset_alokasi" style="padding: 5px;">
    <a href="<?=base_url('master/status/getJson')?>" class="btn btn-small btn-danger"><i class="icon-plus-sign"></i>&nbsp;Add</a>
    <a href="<?=base_url('master/status/getJson')?>" class="btn btn-small btn-info"><i class="icon-edit"></i>&nbsp;Edit</a>
    <a href="<?=base_url('master/status/getJson')?>" class="btn btn-small btn-success"><i class="icon-remove-sign"></i>&nbsp;Remove</a>
    </div>   