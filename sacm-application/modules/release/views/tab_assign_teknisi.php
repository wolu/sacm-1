<script type="text/javascript">
$('#rm').attr('selected', true);
</script>
<div class="container">
<div class="easyui-tabs" plain="true" style="height:auto">  
<div title="Request" style="padding:10px">
<?=$this->load->view('release_assign_teknisi_req')?>  
</div>  
<div title="R F C" style="padding:10px">
<?=$this->load->view('release_assign_teknisi_rfc')?>   
</div>  
</div>  
</div>