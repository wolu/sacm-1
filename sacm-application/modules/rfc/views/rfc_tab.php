<script type="text/javascript">
$('#rfc').attr('selected', true);
</script>
<div class="easyui-tabs" plain="true" style="height:auto">  
<div title="Open" style="padding:10px">
<?=$this->load->view('rfc_open')?>  
</div>  
<div title="Open Configuration" style="padding:10px">
<?=$this->load->view('rfc_open_config')?>   
</div> 
<div title="Status Rejected" style="padding:10px">
<?=$this->load->view('rfc_rejected')?>   
</div>
</div>  