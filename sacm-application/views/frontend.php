<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="System Asset Configuration Management" />
		<meta name="author" content="Heruno Utomo" />
		<title>
			SACM
		</title>
		<link rel="shortcut icon" href="<?= base_url('sacm-assets/images/favicon.png')?>"/>
		<link rel="stylesheet" type="text/css" href="<?= base_url('sacm-assets/css/themes/bootstrap/easyui.css')?>"/>
		<link rel="stylesheet" type="text/css" href="<?= base_url('sacm-assets/css/font-awesome/css/font-awesome.css')?>" />
		<link rel="stylesheet" type="text/css" href="<?= base_url('sacm-assets/css/bootstrap.css')?>"/>
		<link rel="stylesheet" type="text/css" href="<?= base_url('sacm-assets/css/style.css')?>"/>
        
		<script type="text/javascript" src="<?= base_url('sacm-assets/lib/jquery-1.8.0.min.js')?>"></script>
		<script type="text/javascript" src="<?= base_url('sacm-assets/lib/jquery.easyui.min.js" ')?>" ></script>
		<script type="text/javascript" src="<?= base_url('sacm-assets/lib/datagrid-detailview.js "')?>"></script>
		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
			<script src="<?= base_url('sacm-assets/lib/html5shiv.js')?>">
			</script>
		<![endif]-->
	</head>
	<body class="easyui-layout">
		<!--top-->
		<div class="bg-levis" data-options="region:'north'" style="width: 450px; overflow: hidden;">
			<span style="float: left; margin:10px">
				<img src="<?= base_url('sacm-assets/images/logo.png')?>" alt="logo" style="height:55px;" />
			</span>
		</div>
		<!--right <div data-options="region:'east',iconCls:'icon-user'" title="East" style="width:180px;"></div>
		-->
		<!--left-->
		<div class="bg-dot" data-options="region:'west',split:true,iconCls:'icon-th'" title="Menu" style="width:220px; overflow:hidden">
			<?php include( 'accordion.php'); 
            //$this->load->view('nav'); ?>
		</div>
		<div class="bg-dot" data-options="region:'center',iconCls:'icon-globe icon-large'" title="Welcome <?= $this->session->userdata('username') ? $this->session->userdata('username'):'Guest'?>">
			<div class="content">
				<?php echo $contents?>
			</div>
		</div>
        <div id="tbc">
        <a href="javascript:void(0)" class="btn btn-mini btn-danger">
        <i class="icon-lock"></i></a>\
        </div>
		<!--bottom-->
		<div class="bg-levis" data-options="region:'south'" style="height:20px; margin: 0px; padding: 0px; overflow: hidden; color: #fff;">
			<center>
				<small>
					&copy; PT. Krakatau Steel 2013
				</small>
			</center>
		</div>
        <div id="mm" class="easyui-menu" style="width:150px;">  
        <div onclick="javascript:$.messager.alert('About','Copyright &copy; PT. Krakatau Steel 2013','info')" data-options="iconCls:'icon-info-sign'">About</div>   
        <div onclick="javascript:window.location.reload(true)" data-options="iconCls:'icon-refresh'" >Reload</div>  
        <div  data-options="iconCls:'icon-print',disabled:true">Print</div>  
        <div class="menu-sep"></div>  
        <div  onclick="javascript:window.location.href='<?=base_url('auth/logout')?>'" data-options="iconCls:'icon-remove'">Exit</div>  
        </div>  
        <script>  
       $(function(){  
       $(document).bind('contextmenu',function(e){  
               e.preventDefault();  
               $('#mm').menu('show', {  
               left: e.pageX,  
               top: e.pageY  
                });  
            });  
       });           
        </script> 
	</body>
</html>