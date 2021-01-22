<?php
	include 'dist/php/Conexao.php';
	include 'dist/php/ConexaoPrincipal.php';
	
	$mes = date('m');
	$ano = date('Y');
	
	if (!isset($_GET['semestre']))
	{
		$semestre = $mes/6;
		
		if ($semestre > 1)
		{
			$semestre = 2;
		}
		else
		{
			$semestre = 1;
		}
		
		$semestre = $semestre.'/'.$ano;
	}
	else
	{
		$semestre = $_GET['semestre'];
	}
	
	if (!isset($_GET['codigoLaboratorio']))
	{
		echo 'Laboratório não encontrado!';
	}
	else
	{
		$codigoLaboratorio = $_GET['codigoLaboratorio'];
	}
	
	$result_Laboratorio = $conexaoPrincipal -> prepare('select nm_laboratorio, ic_ativado from tb_laboratorio where cd_laboratorio = :codigoLaboratorio');
	$result_Laboratorio -> bindParam(':codigoLaboratorio', $codigoLaboratorio);
	
	$result_Laboratorio -> execute();
	$linha_Laboratorio = $result_Laboratorio -> fetch();
	
	$nomeLaboratorio = $linha_Laboratorio['nm_laboratorio'];
	$laboratorioAtivo = $linha_Laboratorio['ic_ativado'];
	
	$result_Softwares = $conexaoPrincipal -> prepare('select tb_software.cd_software,
															   tb_software.nm_software,
															   (select 1 from software_laboratorio where software_laboratorio.cd_software = tb_software.cd_software and software_laboratorio.cd_laboratorio = :codigoLaboratorio and software_laboratorio.ds_semestre = :semestre) as ic_adicionado
														  from tb_software
															order by tb_software.nm_software');
	$result_Softwares -> bindParam(':codigoLaboratorio', $codigoLaboratorio);
	$result_Softwares -> bindParam(':semestre', $semestre);
	
	$result_Softwares -> execute();
?>

<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php echo $nomeLaboratorio; ?> - Labs</title>
		<!-- Tell the browser to be responsive to screen width -->
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<!-- Bootstrap 3.3.6 -->
		<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		<!-- Font Awesome -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
		<!-- Ionicons -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
		<!-- Theme style -->
		<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. We have chosen the skin-blue for this starter
			page. However, you can choose any other skin. Make sure you
			apply the skin class to the body tag so the changes take effect.
		-->
		<link rel="stylesheet" href="dist/css/skins/skin-blue.min.css">
		
		<link rel="stylesheet" href="plugins/select2/select2.min.css">
		
		<style>
			.center
			{
				height: 100%;
				/*min-height: 100%;*/
				display: -webkit-flex;
				display: flex;
				-webkit-align-items: center;
				align-items: center;
				-webkit-justify-content: center;
				justify-content: center;
			}
		</style>
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<!--
	BODY TAG OPTIONS:
	=================
	Apply one or more of the following classes to get the
	desired effect
	|---------------------------------------------------------|
	| SKINS         | skin-blue                               |
	|               | skin-black                              |
	|               | skin-purple                             |
	|               | skin-yellow                             |
	|               | skin-red                                |
	|               | skin-green                              |
	|---------------------------------------------------------|
	|LAYOUT OPTIONS | fixed                                   |
	|               | layout-boxed                            |
	|               | layout-top-nav                          |
	|               | sidebar-collapse                        |
	|               | sidebar-mini                            |
	|---------------------------------------------------------|
	-->
	<body class="hold-transition skin-blue sidebar-mini">
		<div class="wrapper">
			<?php include('menu.php'); ?>

			<!-- Content Wrapper. Contains page content -->
			<div class="content-wrapper">
				<!-- Content Header (Page header) -->
				<section class="content-header">
					<h1>
						Labs
						<small><?php echo $nomeLaboratorio; ?></small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
						<li class=""><a href="laboratorios.php">Laboratórios</a></li>
						<li class="active"><?php echo $nomeLaboratorio; ?></li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content">

					<!-- Your Page Content Here -->
		  
					<div class="row">
						<div class="col-sm-4">
							<a href="#"><div class="info-box">
								<span class="info-box-icon bg-red">
									<i class="fa fa-trash">
									</i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										Excluir
									</span>
									<span class="info-box-number">
										Laboratório
									</span>
								</div></a>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->
					</div>
					
					<form role="form" method="POST" action="dist/php/atualizarDadosLaboratorio.php" target="_blank">
						<div class="row">
							<input type="submit" id="btn_atualizarDadosLaboratorio" style="display: none;"/>
							<input type="hidden" name="codigoLaboratorio" value="<?php echo $codigoLaboratorio; ?>"/>
							<input type="hidden" name="semestre" value="<?php echo $semestre; ?>"/>
							
							<div class="col-sm-4">
								<div class="form-group">
									<label id="lbl_nomeLaboratorio" for="txt_nomeLaboratorio">Nome do laboratório</label>
									<input type="text" id="txt_nomeLaboratorio" name="nomeLaboratorio" class="form-control" value="<?php echo $nomeLaboratorio; ?>"/>
								</div>
							</div>
						</div>
						
						<div class="row">
							<style>
								.select2-container--default .select2-selection--multiple .select2-selection__choice
								{
								    background-color: #3c8dbc;
								}
							</style>
							<div class="col-sm-4">
								<div class="form-group">
									<label id="lbl_nomeLaboratorio" for="cmb_softwares">Softwares</label>
									<select id="cmb_softwares" name="softwaresLaboratorio[]" class="form-control select2 select2-hidden-accessible" multiple="true" data-placeholder="Selecione os softwares" style="width: 100%;" tabindex="-1" aria-hidden="true">
										<?php
											while ($linha_Softwares = $result_Softwares -> fetch())
											{
										?>
												<option value="<?php echo $linha_Softwares['cd_software']; ?>" <?php echo (($linha_Softwares['ic_adicionado'] == 1) ? 'selected' : ''); ?>><?php echo $linha_Softwares['nm_software']; ?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group checkbox">
									<label id="lbl_laboratorioAtivo" for="chk_laboratorioAtivo"><input type="checkbox" id="chk_laboratorioAtivo" class="" name="laboratorioAtivo" value="1" <?php echo (($laboratorioAtivo == 1) ? 'checked' : ''); ?>/>Laboratório ativo</label>
								</div>
							</div>
						</div>
					</form>
					
					<div class="row">
						<div class="col-lg-2 col-xs-6" style="margin-top: 5px;" onclick="btn_atualizarDadosLaboratorio.click();">
							<button type="button" class="btn btn-block btn-success"><font><font class="">Atualizar dados</font></font></button>
						</div>
					</div>
					
					<div class="row">&nbsp;</div>
					
					<div class="row">
						<div class="col-xs-12">
							<div class="box box-default collapsed-box">
								<div class="box-header with-border">
									<h3 class="box-title">Legenda</h3>
									<div class="box-tools pull-right">
										<button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
									</div><!-- /.box-tools -->
								</div><!-- /.box-header -->
								<div class="box-body" id="legendaCursos">
									&nbsp;
								</div><!-- /.box-body -->
							</div><!-- /.box -->
						</div>
					</div>
					
					<div class="row">
                        <!-- Lista de laboratórios -->
                        <div class="col-xs-12">
							<div class="box box-solid box-default" style="border: none; box-shadow: none; min-height: 300px; background-color: transparent;">
								<div class="box-header" style="border: none; box-shadow: none; background-color: transparent;">
									<h3 class="box-title">Relação de aulas</h3>
								</div><!-- /.box-header -->
								
								<style>
									#aulas.table-hover>tbody>tr:hover
									{
										color: black;
									}
									
									#aulas.table > tbody > tr, #aulas.table > tbody > tr > td
									{
										border: 3px solid white;
									}
								</style>
								<div class="box-body table-responsive no-padding bg-blank">
									<table id="aulas" class="table table-hover bg-blue" cellspacing="3" cellpadding="0" valign="center">
										&nbsp;
									</table>
								</div>
								
								<div id="loading" class="overlay" style="display: none; background-color: transparent;">
								  <i class="fa fa-refresh fa-spin"></i>
								</div>
							</div>
                        </div>
                    </div>
				</section>
				<!-- /.content -->
			</div>
			<!-- /.content-wrapper -->

			<!-- Main Footer -->
			<footer class="main-footer">
				<!-- To the right -->
				<div class="pull-right hidden-xs">
					Anything you want
				</div>
				<!-- Default to the left -->
				<strong>Copyright &copy; 2016 <a href="#">Company</a>.</strong> All rights reserved.
			</footer>

			<!-- Control Sidebar -->
			<aside class="control-sidebar control-sidebar-dark">
				<!-- Create the tabs -->
				<ul class="nav nav-tabs nav-justified control-sidebar-tabs">
					<li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
					<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
				</ul>
				<!-- Tab panes -->
				<div class="tab-content">
					<!-- Home tab content -->
					<div class="tab-pane active" id="control-sidebar-home-tab">
						<h3 class="control-sidebar-heading">Recent Activity</h3>
						<ul class="control-sidebar-menu">
							<li>
								<a href="javascript:;">
									<i class="menu-icon fa fa-birthday-cake bg-red"></i>

									<div class="menu-info">
										<h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

										<p>Will be 23 on April 24th</p>
									</div>
								</a>
							</li>
						</ul>
						<!-- /.control-sidebar-menu -->

						<h3 class="control-sidebar-heading">Tasks Progress</h3>
						<ul class="control-sidebar-menu">
							<li>
								<a href="javascript:;">
									<h4 class="control-sidebar-subheading">
										Custom Template Design
										<span class="pull-right-container">
											<span class="label label-danger pull-right">70%</span>
										</span>
									</h4>

									<div class="progress progress-xxs">
										<div class="progress-bar progress-bar-danger" style="width: 70%"></div>
									</div>
								</a>
							</li>
						</ul>
						<!-- /.control-sidebar-menu -->
					</div>
					<!-- /.tab-pane -->
					<!-- Stats tab content -->
					<div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
					<!-- /.tab-pane -->
					<!-- Settings tab content -->
					<div class="tab-pane" id="control-sidebar-settings-tab">
						<form method="post">
							<h3 class="control-sidebar-heading">General Settings</h3>

							<div class="form-group">
								<label class="control-sidebar-subheading">
									Report panel usage
									<input type="checkbox" class="pull-right" checked>
								</label>

								<p>
									Some information about this general settings option
								</p>
							</div>
						<!-- /.form-group -->
						</form>
					</div>
				<!-- /.tab-pane -->
				</div>
			</aside>
			<!-- /.control-sidebar -->
			<!-- Add the sidebar's background. This div must be placed
				immediately after the control sidebar -->
			<div class="control-sidebar-bg"></div>
		</div>
		<!-- ./wrapper -->
	</body>
	<!-- REQUIRED JS SCRIPTS -->

	
	<!-- Optionally, you can add Slimscroll and FastClick plugins.
		 Both of these plugins are recommended to enhance the
		 user experience. Slimscroll is required when using the
		 fixed layout. -->
	<!-- jQuery 2.2.3 -->
	<script src="plugins/jQuery/jquery-2.2.3.min.js"></script>
	<!-- Bootstrap 3.3.6 -->
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<!-- AdminLTE App -->
	<script src="plugins/select2/select2.full.min.js"></script>
	<script type="text/javascript">
		$(".select2").select2();
	</script>
	<script src="dist/js/app.min.js"></script>
	<script src="dist/js/ajax.js"></script>
	<script>
		var semestre = '<?php echo $semestre; ?>';
		var codigoLaboratorio = '<?php echo $codigoLaboratorio; ?>';
		
		window.onload = function()
		{
			getLegendaCursos();
			getAulas(semestre, codigoLaboratorio);
		}
		
		function getLegendaCursos()
		{
			Ajax('GET', 'dist/php/getLegendaCursos.php', '', "var retorno = this.responseText; legendaCursos.innerHTML = retorno;");
		}
		
		function getAulas(_semestre, _codigoLaboratorio)
		{
			loading.style.display = 'inline-block';
			Ajax('GET', 'dist/php/getAulas.php', 'semestre=' + _semestre + '&codigoLaboratorio=' + codigoLaboratorio, "var retorno = this.responseText; aulas.innerHTML = retorno; loading.style.display = 'none';");
		}
		
		/*Frm_AdicionarLaboratorio.onsubmit = function()
		{
			AjaxForm(this, "btn_adicionarLaboratorio.disabled = true;", "btn_adicionarLaboratorio.disabled = false; var retorno = this.responseText; var indicador = retorno.split(';-;')[0]; var mensagem = retorno.split(';-;')[1]; if (indicador == 0 || indicador == 1) {alert(mensagem);} if (indicador == 1) {btn_fecharAdicionarLaboratorio.click(); txt_nomeLaboratorio.value = ''; getLaboratorios(semestre);}");
			
			return false;
		}*/
	</script>
</html>
