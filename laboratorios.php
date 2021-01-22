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
		<title>Laboratórios - Labs</title>
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
						<small>Laboratórios</small>
					</h1>
					<ol class="breadcrumb">
						<li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
						<li class="active">Laboratórios</li>
					</ol>
				</section>

				<!-- Main content -->
				<section class="content">

					<!-- Your Page Content Here -->
		  
					<div class="row">
						<div class="col-sm-4">
							<a href="#"><div class="info-box">
								<span class="info-box-icon bg-black">
									<i class="fa fa-plus">
									</i>
								</span>
								<!-- Button trigger modal -->
								<div class="info-box-content" data-toggle="modal" data-target="#modalAdicionarLaboratorio">
									<span class="info-box-text">
										Novo
									</span>
									<span class="info-box-number">
										Laboratório
									</span>
								</div></a>
								<!-- Modal -->
								<div class="modal fade" id="modalAdicionarLaboratorio" tabindex="-1" role="dialog" aria-labelledby="modalLabelAdicionarLaboratorio">
									<form id="Frm_AdicionarLaboratorio" method="POST" action="dist/php/adicionarLaboratorio.php" role="form">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title" id="modalLabelAdicionarLaboratorio">Adicionar laboratório</h4>
												</div>
												<div class="modal-body">										
													<div class="row">
														<div class="col-lg-4 col-xs-6">
															<div class="form-group">
																<label for="txt_nomeLaboratorio">Nome do laboratório</label>
															</div>
														</div>
														<div class="col-lg-4 col-xs-6">
															<div class="form-group">
																<input type="text" id="txt_nomeLaboratorio" name="nomeLaboratorio" value=""/>
															</div>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" id="btn_fecharAdicionarLaboratorio" class="btn btn-default" data-dismiss="modal">Fechar</button>
													<button type="submit" id="btn_adicionarLaboratorio" class="btn btn-primary">Adicionar</button>
												</div>
											</div>
										</div>
									</form>	
								</div>
								<!-- /.info-box-content -->
							</div>
							<!-- /.info-box -->
						</div>
						<!-- /.col -->
					</div>
					
					<div class="row">
						<form role="form" method="GET" action="exibirPlanilha.php" target="_blank">
							<input type="hidden" id="txt_planilhaVisualizar" name="planilha" value="" required/>
							<input type="hidden" id="txt_periodoVisualizar" name="periodo" value="" required/>

							<div class="col-xs-3">
								<div class="form-group">
									<label id="lbl_semestre" for="txt_semestre">Semestre</label>
									<input type="text" id="txt_semestre" name="semestre" class="form-control" value="<?php echo $semestre; ?>"/>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label id="lbl_periodo" for="cmb_periodo">Período</label>
									<select id="cmb_periodo" class="form-control" required onchange="if (this.value == 1) {txt_periodoVisualizar.value = 'M';} else if (this.value == 2) {txt_periodoVisualizar.value = 'T';} else if (this.value == 3) {txt_periodoVisualizar.value = 'N';} else {txt_periodoVisualizar.value = '';} if (this.value == '') {txt_planilhaVisualizar.value = '';} else if (this.value == 4) {txt_planilhaVisualizar.value = 3;} else {txt_planilhaVisualizar.value = 1;}">
										<option value="">Selecione...</option>
										<option value="1">Manhã</option>
										<option value="2">Tarde</option>
										<option value="3">Noite</option>
										<option value="4">Sábado</option>
									</select>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label id="lbl_secretaria" for="cmb_secretaria">Secretaria</label>
									<select class="form-control" name="secretaria" id="cmb_secretaria" required onchange="cmb_curso.value = 0;">
										<option value="0">Selecione...</option>
										<?php
											$result_Secretarias = $conexaoPrincipal -> prepare('select cd_secretaria, nm_secretaria from tb_secretaria order by nm_secretaria');
											
											$result_Secretarias -> execute();
											
											if ($result_Secretarias -> rowCount() > 0)
											{
												while ($linha_Secretarias = $result_Secretarias -> fetch())
												{
										?>
													<option value="<?php echo $linha_Secretarias['cd_secretaria']; ?>"><?php echo $linha_Secretarias['nm_secretaria']; ?></option>
										<?php
												}
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-xs-3">
								<div class="form-group">
									<label id="lbl_semestre" for="cmb_curso">Curso</label>
									<select class="form-control" name="curso" required id="cmb_curso" onchange="cmb_secretaria.value = 0;">
										<option value="0">Todos</option>
										<?php										
											$result_Cursos = $conexaoPrincipal -> prepare('select cd_curso, nm_curso from tb_curso order by nm_curso');
											$result_Cursos -> execute();
											
											if ($result_Cursos -> rowCount() > 0)
											{
												while ($linha_Cursos = $result_Cursos -> fetch())
												{
													$pos = strpos($linha_Cursos['nm_curso'], 'Engenharia');
									
													if ($pos === false)
													{
														$aux = 2;
													}
													else
													{
														if ($linha_Cursos['nm_curso'] == 'Engenharia')
														{
															$aux = 1;
														}
														else
														{
															$aux = 0;
														}
													}
													
													if ($aux == 1 || $aux == 2)
													{
										?>
														<option value="<?php echo $linha_Cursos['cd_curso']; ?>"><?php echo $linha_Cursos['nm_curso']; ?></option>
										<?php
													}
												}
											}
										?>
									</select>
								</div>
							</div>
							<input type="submit" id="btn_visualizarPlanilha" style="display: none;"/>
						</form>
					</div>
					
					<div class="row">
						<div class="col-lg-2 col-xs-6" style="margin-top: 5px;" onclick="btn_visualizarPlanilha.click();">
							<button type="button" class="btn btn-block btn-success"><font><font class="">Visualizar planilha</font></font></button>
						</div>
						<div class="col-lg-2 col-xs-6" style="margin-top: 5px;">
							<button type="button" class="btn btn-block btn-primary"><font><font class="">Gerar possíveis aulas</font></font></button>
						</div>
						<div class="col-lg-2 col-xs-6" style="margin-top: 5px;">
							<button type="button" class="btn btn-block btn-danger"><font><font class="">Limpar semestre</font></font></button>
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
								<div class="box-body">
									<div id="laboratorios">
										&nbsp;
									</div>
								</div>
								
								<div id="loading" class="overlay" style="display: inline-block; background-color: transparent;">
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
	<script src="dist/js/app.min.js"></script>
	<script src="dist/js/ajax.js"></script>
	<script>
		var semestre = '<?php echo $semestre; ?>';
		
		window.onload = function()
		{
			getLegendaCursos();
			getLaboratorios(semestre);
		}
		
		txt_semestre.onblur = function()
		{
			alterarSemestre(txt_semestre.value.trim());
		}
		
		function alterarSemestre(_semestre)
		{
			semestre = _semestre;
			
			window.history.replaceState('laboratorios', 'Laboratórios - Labs', 'laboratorios.php?semestre=' + semestre);
			
			getLaboratorios(semestre);
		}
		
		function getLegendaCursos()
		{
			Ajax('GET', 'dist/php/getLegendaCursos.php', '', "var retorno = this.responseText; legendaCursos.innerHTML = retorno;");
		}
		
		function getLaboratorios(_semestre)
		{
			loading.style.display = 'inline-block';
			Ajax('GET', 'dist/php/getLaboratorios.php', 'semestre=' + _semestre, "var retorno = this.responseText; laboratorios.innerHTML = retorno; loading.style.display = 'none';");
		}
		
		Frm_AdicionarLaboratorio.onsubmit = function()
		{
			AjaxForm(this, "btn_adicionarLaboratorio.disabled = true;", "btn_adicionarLaboratorio.disabled = false; var retorno = this.responseText; var indicador = retorno.split(';-;')[0]; var mensagem = retorno.split(';-;')[1]; if (indicador == 0 || indicador == 1) {alert(mensagem);} if (indicador == 1) {btn_fecharAdicionarLaboratorio.click(); txt_nomeLaboratorio.value = ''; getLaboratorios(semestre);}");
			
			return false;
		}
	</script>
</html>
