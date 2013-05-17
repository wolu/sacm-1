<table id="dgrejected" class="easyui-datagrid" style="height:500px;"
            url="<?=base_url('rfc/getJson?status=R')?>"
            iconCls ="icon-save icon-large"  
            rownumbers ="true" 
            pagination ="true"
            fitColumns ="true"
            singleSELECT = "true">  
        <thead>  
            <tr>  
                <th field="NomorRFC"     width="7" sortable="true" align="center">Nomor RFC</th>  
                <th field="NomorService" width="5" sortable="true" align="center">No Service</th> 
                <th field="SerialNumber" width="10"sortable="true" align="center">Serial Number</th>
                <th field="TanggalRFC"   width="10"sortable="true" align="center">Tanggal RFC</th>  
                <th field="TanggalInput" width="10"sortable="true" align="center">Tanggal Input</th>
                <th field="CodeChange"   width="5" sortable="true" align="center">Kode Change</th>  
                <!--th field="RFCId"        width="5" sortable="true" align="center">RFC ID</th> 
                <th field="TanggalTarget"width="10"sortable="true" align="center">Tgl Target</th-->  
                <th field="KodeStatus"   width="3" sortable="true" align="center">Status</th>  
            </tr>  
        </thead>  
    </table>