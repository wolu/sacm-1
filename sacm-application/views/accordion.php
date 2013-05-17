<div class="panel-header accordion-header accordion-header-selected" style="height: 20px; width: 203px;">
<a href="javascript:void(0)" class="btn btn-mini btn-info">
<i class="icon-user">
&nbsp;Hello-<?= $this->session->userdata('username') ? $this->session->userdata('username'):'Guest'?> </i>
</a>
<a href="<?=base_url('auth/logout')?>" class="btn btn-mini btn-danger">
<i class="icon-lock">&nbsp;
<?php
if(!$this->ion_auth->logged_in()){
   echo 'Login';
}else{
   echo 'Logout';
}?>
</i></a>
</div>
<div class="easyui-accordion"  animate="true" style="width:215px;height:450px;"> 
 
        <div title="1. Request " id="req" onclick="javascript:pilih('req')"  data-options="iconCls:'icon-file'"  style="overflow:auto;padding:10px;">
            <ul class="easyui-tree" animate="true">
    			<li><a href="<?=base_url('request')?>">1.1 Input Request</a></li>
    			<li><a href="<?=base_url('request/review')?>">1.2 Review Request</a></li>
    			<li><a href="<?=base_url('request/penjadwalan')?>">1.3 Penjadwalan Request</a></li>
           </ul>  
       </div> 
         
        <div title="2. Change Management" id="rfc" onclick="javascript:pilih('rfc')" data-options="iconCls:'icon-edit'" id="coba" style="padding:10px;"> 
            <ul class="easyui-tree" animate="true">
    			<li><a href="<?=base_url('rfc')?>">2.1 Input RFC</a></li>
    			<li><a href="<?=base_url('rfc/review')?>">2.2 Review RFC</a></li>
    			<li><a href="<?=base_url('rfc/penjadwalan')?>">2.3 Penjadwalan RFC</a>
    			</li>
    		</ul> 
        </div> 
        
        <div title="3. Service" id="service" data-options="iconCls:'icon-cogs'" style="overflow:auto;padding:10px;"> 
        <ul class="easyui-tree" animate="true">
			<li><a href="<?=base_url('service')?>">Service</a></li>
			<!--li><a href="<?=base_url('')?>">View Permintaan</a></li>
			<li><a href="<?=base_url('')?>">Analisa Permintaan</a></li-->
		</ul>  
        </div>
          
        <div title="4. Asset" id="asset" data-options="iconCls:'icon-money'" style="padding:10px;"> 
        <ul class="easyui-tree" animate="true">
			<li><a href="<?=base_url('asset')?>">Assets</a></li>
			<li><a href="<?=base_url('asset/asset_alokasi')?>">Assets Alokasi</a></li>
		</ul> 
        </div>
        
        <div title="5. Release Management" id="rm" data-options="iconCls:'icon-gift'" style="overflow:auto;padding:10px;">
        <ul class="easyui-tree" animate="true">
			<li><a href="<?=base_url('release/assign_teknisi')?>">5.1 Assign ke Teknisi</a></li>
            <li><a href="<?=base_url('release/install_config')?>">5.2 Instalasi Konfigurasi</a></li>
			<li><a href="<?=base_url('release/acceptance')?>">5.3 Release Acceptance Test</a></li>
            <li><a href="<?=base_url('release/pengambilan_barang')?>">5.4 Pengambilan Barang</a></li>
            <li><a href="<?=base_url('release/closing')?>">5.5 Closing</a></li>
            <li><a href="<?=base_url('release/disposal')?>">5.6 Data Disposal</a></li>
		</ul>   
        </div>
          
        <div title="Setting" id="set" data-options="iconCls:'icon-wrench'" style="padding:10px;"> 
        <ul class="easyui-tree" animate="true">
			<li><a href="<?=base_url('auth')?>">User</a></li>
			<li><a href="<?=base_url('auth/create_user')?>">Create User</a></li>
			<li><a href="<?=base_url('auth/create_group')?>">Create Group</a></li>
		</ul> 
        </div>
         
        <div title="Master" id="master" data-options="iconCls:'icon-tasks'" style="overflow:auto; padding:10px;">
        <ul class="easyui-tree" animate="true">
			<li><a href="<?=base_url('master/status')?>">Status</a></li>
			<li><a href="<?=base_url('master/karyawan')?>">Karyawan</a></li>
			<li><a href="<?=base_url('master/lokasi')?>">Lokasi</a></li>
			<li><a href="<?=base_url('master/costcenter')?>">Cost Center</a></li>
			<li><a href="<?=base_url('master/unitkerja')?>">Unit Kerja</a></li>
			<li><a href="<?=base_url('master/status_asset')?>">Status Asset</a></li>
			<li><a href="<?=base_url('master/tipe_change')?>">Type Change</a></li>
			<li><a href="<?=base_url('master/teknisi')?>">Teknisi</a></li>
			<li><a href="<?=base_url('master/service_jenis')?>">Jenis Service</a></li>
			<li><a href="<?=base_url('master/master_config')?>">Master Configuration</a></li>
		</ul>  
        </div>
        <div title="Third Party" id="tp" data-options="iconCls:'icon-hdd'" style="padding:10px;"> 
        <ul class="easyui-tree" animate="true">
			<li><a href="<?=base_url('master/export')?>">Report Data Karyawan</a></li>
		</ul> 
        </div> 
</div> 