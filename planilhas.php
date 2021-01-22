<?php
	include 'dist/php/Conexao.php';
	include 'dist/php/ConexaoPrincipal.php';
	
	/*session_start();
	
	if (!isset($_SESSION['SALU']['codigoUsuario']))
	{
		header('Location: login.php');
	}*/
	
	$result_Semestres = $conexaoPrincipal -> prepare('select distinct ds_semestre from tb_aula order by ds_semestre desc');
	$result_Semestres -> execute();
	
	$result_Secretarias = $conexaoPrincipal -> prepare('select cd_secretaria, nm_secretaria from tb_secretaria order by nm_secretaria');
	$result_Secretarias -> execute();
	
	$query_Cursos = 'select cd_curso, nm_curso from tb_curso order by nm_curso';
	$result_Cursos = $conexaoPrincipal -> prepare($query_Cursos);
	$result_Cursos -> execute();
	
	if (isset($_GET['secretaria']))
	{
		$secretaria = $_GET['secretaria'];
	}
	else
	{
		$secretaria = 0;
	}
	
	if (isset($_GET['curso']))
	{
		$curso = $_GET['curso'];
	}
	else
	{
		$curso = 0;
		
	}
	
	$whereCurso = '';
	$whereCursoAula = '';
	$tituloSecretaria = '';
	
	if ($secretaria == 0)
	{
		if ($curso != 0)
		{
			$result_Secretaria = $conexaoPrincipal -> prepare('select cd_curso, nm_curso from tb_curso where cd_curso = :curso');
			$result_Secretaria -> bindParam(':curso', $curso);
			
			$result_Secretaria -> execute();
			$linha_Secretaria = $result_Secretaria -> fetch();
			
			mb_internal_encoding('UTF-8');
			
			$tituloSecretaria = mb_strtoupper($linha_Secretaria['nm_curso']).' - ';
			
			$whereCurso = "and cd_curso = '$curso'";
			$whereCursoAula = "and cd_curso = '$curso'";
		}
	}
	else
	{
		$result_Secretaria = $conexaoPrincipal -> prepare('select cd_secretaria, nm_secretaria from tb_secretaria where cd_secretaria = :secretaria');
		$result_Secretaria -> bindParam(':secretaria', $secretaria);
		
		$result_Secretaria -> execute();
		$linha_Secretaria = $result_Secretaria -> fetch();
		
		mb_internal_encoding('UTF-8');
		
		$tituloSecretaria = mb_strtoupper($linha_Secretaria['nm_secretaria']).' - ';
		
		$whereCurso = "and cd_secretaria = '$secretaria'";
		$whereCursoAula = "and (select tb_curso.cd_secretaria from tb_curso where tb_curso.cd_curso = tb_aula.cd_curso) = '$secretaria'";
	}
	
	$result_Cores = $conexaoPrincipal -> prepare("select nm_curso,
													   ds_cor_hex
												  from tb_curso
													where ds_cor_hex is not null $whereCurso 
														order by nm_curso");
	$result_Cores -> execute();
	
	if (isset($_GET['planilha']) || isset($_GET['periodo']) || isset($_GET['semestre']))
	{
		$visualizar = 1;
		
		if (isset($_GET['planilha']))
		{
			$planilha = $_GET['planilha'];
		}
		else
		{
			$planilha = '';
		}
		
		if (isset($_GET['periodo']))
		{
			$periodo = $_GET['periodo'];
		}
		else
		{
			$periodo = '';
		}
		
		if (isset($_GET['semestre']))
		{
			$semestre = $_GET['semestre'];
		}
		else
		{
			$semestre = '';
		}
	}
	else
	{
		$visualizar = 0;
	}
	
	function getDiaSemana($dia)
	{
		switch ($dia)
		{
			case 1:
			{
				return 'SEGUNDA';
			}
			break;
			
			case 2:
			{
				return 'TERÇA';
			}
			break;
			
			case 3:
			{
				return 'QUARTA';
			}
			break;
			
			case 4:
			{
				return 'QUINTA';
			}
			break;
			
			case 5:
			{
				return 'SEXTA';
			}
			break;
			
			case 6:
			{
				return 'SÁBADO';
			}
			break;
			
			default:
			{
				return '';
			}
		}
	}
?>

<!DOCTYPE html>

<html>
	<head>
		<?php
			if ($planilha == 1 || $planilha == 2)
			{
				if ($periodo == 'M')
				{
					$horario = 'HORÁRIO MANHÃ';
				}
				else if ($periodo == 'T')
				{
					$horario = 'HORÁRIO TARDE';
				}
				else if ($periodo == 'N')
				{
					$horario = 'HORÁRIO NOITE';
				}
				
				$tSemestre = explode('/', $semestre);
				$tSemestre = $tSemestre[0].'º SEMESTRE '.$tSemestre[1];
			}
			else if ($planilha == 3)
			{
				$horario = 'HORÁRIO SÁBADO';
				
				$tSemestre = explode('/', $semestre);
				$tSemestre = $tSemestre[0].'º SEMESTRE '.$tSemestre[1];
			}
		?>
		
		<?php
			if ($visualizar == 1)
			{
		?>
				<title><?php echo $tituloSecretaria.$horario.' - '.$tSemestre; ?></title>
		<?php
			}
			else
			{
		?>
				<title>Planilhas</title>
		<?php
			}
		?>
		
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<?php
			if ($visualizar == 0)
			{
		?>	
				<div style="top: 0px; width: 100%;">
					<div class="u_top">
						<a href="index.php" title="Voltar ao início"><img class="u_logo" src="imagens/logo.png"></a>
					</div>
					<div class="u_control">
						<table class="u_tableGuide"  cellspacing="10px">
							<tr>
								<td width="70%" align="left">
									<label class="u_whiteTextN">Planilhas</label>
								</td>
								<td width="7%">
									<a href="index.php" target="_self"><input class="u_buttonBack" type="button" value="Voltar"/></a>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div style="padding: 25px;">
					<form method="GET" target="_blank">
						<table cellspacing="10px">
							<tr>
								<td>
									<label class="u_label">Planilha: </label> 
								</td>
								<td>
									<select class="u_select" name="planilha" required id="cmb_planilha" onchange="if (this.value == 1 || this.value == 2 || this.value == 3) {cmb_curso.disabled = false; cmb_secretaria.disabled = false;} else {cmb_curso.disabled = true; cmb_secretaria.disabled = true;} if (this.value == 1 || this.value == 2) {cmb_periodo.disabled = false;} else {cmb_periodo.disabled = true;} ">
										<option value="">Selecione...</option>
										<option value="1">Relação de aulas por laboratórios (um único período, segunda a sexta)</option>
										<option value="2">Relação de aulas por laboratórios (um único período, segunda a sábado)</option>
										<option value="3">Relação de aulas por laboratórios (todos os períodos, sábado)</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label class="u_label">Período: </label>
								</td>
								<td>
									<select class="u_select" name="periodo" id="cmb_periodo" disabled>
										<option value="">Selecione...</option>
										<option value="M">Manhã</option>
										<option value="T">Tarde</option>
										<option value="N">Noite</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label class="u_label">Semestre: </label>
								</td>
								<td>
									<select class="u_select" name="semestre" id="cmb_semestre">
										<option value="">Selecione...</option>
										<?php
											if ($total_Semestres > 0)
											{
												do
												{
										?>
													<option value="<?php echo $linha_Semestres['ds_semestre']; ?>"><?php echo $linha_Semestres['ds_semestre']; ?></option>
										<?php
												}
												while ($linha_Semestres = mysqli_fetch_assoc($result_Semestres));
											}
										?>		
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label class="u_label">Secretaria</label>
								</td>
								<td>
									<select class="u_select" name="secretaria" id="cmb_secretaria" required onchange="cmb_curso.value = 0;">
										<option value="0">Selecione...</option>
										<?php
											if ($total_Secretarias > 0)
											{
												do
												{
										?>
														<option value="<?php echo $linha_Secretarias['cd_secretaria']; ?>"><?php echo $linha_Secretarias['nm_secretaria']; ?></option>
										<?php
												}
												while ($linha_Secretarias = mysqli_fetch_assoc($result_Secretarias));
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<label class="u_label">Curso</label>
								</td>	
								<td>
									<select class="u_select" name="curso" required id="cmb_curso" onchange="cmb_secretaria.value = 0;">
										<option value="0">Todos</option>
										<?php
											if ($total_Cursos > 0)
											{
												do
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
												while ($linha_Cursos = mysqli_fetch_assoc($result_Cursos));
											}
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<input class="u_buttonBlue" type="submit" id="btn_visualizar" value="Visualizar"/>
								</td>
							</tr>
						</table>
					</form>
				</div>
		<?php
			}
			else
			{
				if ($planilha == 1 || $planilha == 2 || $planilha == 3)
				{
					$query_Laboratorios = "select cd_laboratorio,
												   nm_laboratorio
											  from tb_laboratorio
												where ic_ativado = 1
												order by nm_laboratorio";
					$result_Laboratorios = $conexaoPrincipal -> Query($query_Laboratorios);
					$linha_Laboratorios = mysqli_fetch_assoc($result_Laboratorios);
					$total_Laboratorios = mysqli_num_rows($result_Laboratorios);
				}
				
				if ($planilha == 1 || $planilha == 2)
				{
					if ($periodo == 'M')
					{
						$horario = 'HORÁRIO MANHÃ';
					}
					else if ($periodo == 'T')
					{
						$horario = 'HORÁRIO TARDE';
					}
					else if ($periodo == 'N')
					{
						$horario = 'HORÁRIO NOITE';
					}
					
					$tSemestre = explode('/', $semestre);
					$tSemestre = $tSemestre[0].'º SEMESTRE '.$tSemestre[1];
		?>
					<div style="margin-top: 25px; text-align: center;">
						<h2><?php echo $tituloSecretaria.$horario.' - '.$tSemestre; ?></h1>
						
						<style>
							*
							{
								font-family: monospace !important;
							}
							
							body
							{
								background-color: white;
							}
							
							.u_subtitle_color.itemLegenda
							{
								display: inline-block;
								margin-left: 25px;
							}
						</style>
						
						<div style="padding-top: 25px; padding-right: 25px; color: black; font-size: 0.9em;">
							<?php
								if ($total_Cores > 0)
								{
									do
									{
										$pos = strpos($linha_Cores['nm_curso'], 'Engenharia');
										
										if ($pos === false)
										{
											$aux = 2;
										}
										else
										{
											if ($linha_Cores['nm_curso'] == 'Engenharia')
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
											<div class="u_subtitle_color itemLegenda" style="">
												<div class="u_color" style="border: 1px solid white; display: inline-block; width: 30px; height: 30px; background-color: <?php if ($linha_Cores['ds_cor_hex'] == '') {echo '#aaa';} else {echo $linha_Cores['ds_cor_hex'];} ?>;">
													&nbsp;
												</div>
												<div style="display: inline-block; vertical-align: text-top;">
													<label class="u_text"><?php echo $linha_Cores['nm_curso']; ?></label>
												</div>
											</div>
							<?php
										}
									}
									while ($linha_Cores = mysqli_fetch_assoc($result_Cores));
								}
							?>
						</div>
						
						<br/>
						
						<style>
							table
							{
								border-collapse: collapse;
							}
							
							table, th, td 
							{
								border: 2px solid black;
							}
							
							.reduz
							{
								max-width: 40px;
								min-width: 100px;
								word-wrap: break-word;
								word-break: break-word;
							}
							
							.reduz.dia
							{
								height: 150px;
							}
							
							.reduz.aula.linha
							{
								height: 52px;
							}
						</style>
						
						<table class="principal" style="font-size: 0.9em; text-align: center; text-transform: uppercase;" cellpadding="2">
							<tr>
								<td class="reduz">
									&nbsp;
								</td>
								<?php
									if ($total_Laboratorios > 0)
									{
										do
										{
								?>
											<td class="reduz lab"><?php echo $linha_Laboratorios['nm_laboratorio']; ?></td>
								<?php
										}
										while ($linha_Laboratorios = mysqli_fetch_assoc($result_Laboratorios));
									}
								?>
							</tr>
							<?php
								if ($planilha == 1)
								{
									$limite = 5;
								}
								else if ($planilha == 2)
								{
									$limite = 6;
								}
								
								for ($cont = 1; $cont <= $limite; $cont = $cont + 1)
								{
							?>
									<tr>
										<td class="reduz dia">
											<?php echo getDiaSemana($cont); ?>
										</td>
										
										<?php
											$result_Laboratorios = $conexaoPrincipal -> Query($query_Laboratorios);
											$linha_Laboratorios = mysqli_fetch_assoc($result_Laboratorios);
											$total_Laboratorios = mysqli_num_rows($result_Laboratorios);
																
											if ($total_Laboratorios > 0)
											{
												do
												{
										?>
													<td class="reduz aula" style="padding: 0px;">
														<style>
															table.second
															{
																width: 100%;
																height: 100%;
																min-height: 100px;
															}
															
															table.second, table.second th, table.second td 
															{
																border: 0px;
															}
														</style>
														<table class="second" cellpadding="0">
															<?php										
																if ($periodo == 'N')
																{
																	$limiteHorario = 2;
																}
																else
																{
																	$limiteHorario = 3;
																}
																
																for ($i = 1; $i <= $limiteHorario; $i = $i + 1)
																{
															?>
																	<tr class="reduz aula linha" style="border-top: 0.5px solid black; <?php if ($periodo == 'N') {echo 'height: 77px !important;';} ?>">
																		<?php
																			$codigoLaboratorio = $linha_Laboratorios['cd_laboratorio'];
																			
																			$result_Aula = $conexaoPrincipal -> Query("select tb_aula.cd_aula,
																															   tb_aula.dd_semana_aula,
																															   tb_aula.ds_periodo,
																															   tb_aula.cd_horario,
																															   (select tb_laboratorio.nm_laboratorio from tb_laboratorio where tb_laboratorio.cd_laboratorio = tb_aula.cd_laboratorio) as nm_laboratorio,
																															   (select tb_curso.nm_curso from tb_curso where tb_curso.cd_curso = tb_aula.cd_curso) as nm_curso,
																															   (select tb_curso.ds_cor_hex from tb_curso where tb_curso.cd_curso = tb_aula.cd_curso) as ds_cor_hex,
																															   (select tb_professor.nm_professor from tb_professor where tb_professor.cd_professor = tb_aula.cd_professor) as nm_professor
																														  from tb_aula
																															 where dd_semana_aula = '$cont' and ds_periodo = '$periodo' and ds_semestre = '$semestre' and cd_laboratorio = '$codigoLaboratorio' and cd_horario = '$i' $whereCursoAula");
																			$linha_Aula = mysqli_fetch_assoc($result_Aula);
																			$total_Aula = mysqli_num_rows($result_Aula);
																			
																			if ($total_Aula > 0)
																			{
																		?>
																				<td style="background-color: <?php echo $linha_Aula['ds_cor_hex']; ?>">&nbsp;<?php echo strtoupper($linha_Aula['nm_professor']); ?></td>	
																		<?php
																			}
																			else
																			{
																		?>											
																				<td>&nbsp;</td>
																		<?php
																			}
																		?>
																	</tr>
															<?php
																}
															?>
														</table>
													</td>
										<?php
												}
												while ($linha_Laboratorios = mysqli_fetch_assoc($result_Laboratorios));
											}
										?>
									</tr>
							<?php
								}
							?>				
						</table>
					</div>
		<?php
				}
				else if ($planilha == 3)
				{
					$horario = 'HORÁRIO SÁBADO';
					
					$tSemestre = explode('/', $semestre);
					$tSemestre = $tSemestre[0].'º SEMESTRE '.$tSemestre[1];
		?>
					<div style="margin-top: 25px; text-align: center;">
						<h2><?php echo $tituloSecretaria.$horario.' - '.$tSemestre; ?></h1>
						
						<style>
							*
							{
								font-family: monospace !important;
							}
							
							body
							{
								background-color: white;
							}
							
							.u_subtitle_color.itemLegenda
							{
								display: inline-block;
								margin-left: 25px;
							}
						</style>
						
						<div style="padding-top: 25px; padding-right: 25px; color: black; font-size: 0.9em;">
							<?php
								if ($total_Cores > 0)
								{
									do
									{
										$pos = strpos($linha_Cores['nm_curso'], 'Engenharia');
										
										if ($pos === false)
										{
											$aux = 2;
										}
										else
										{
											if ($linha_Cores['nm_curso'] == 'Engenharia')
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
											<div class="u_subtitle_color itemLegenda" style="">
												<div class="u_color" style="border: 1px solid white; display: inline-block; width: 30px; height: 30px; background-color: <?php if ($linha_Cores['ds_cor_hex'] == '') {echo '#aaa';} else {echo $linha_Cores['ds_cor_hex'];} ?>;">
													&nbsp;
												</div>
												<div style="display: inline-block; vertical-align: text-top;">
													<label class="u_text"><?php echo $linha_Cores['nm_curso']; ?></label>
												</div>
											</div>
							<?php
										}
									}
									while ($linha_Cores = mysqli_fetch_assoc($result_Cores));
								}
							?>
						</div>
						
						<br/>
						
						<style>
							table
							{
								border-collapse: collapse;
							}
							
							table, th, td 
							{
								border: 2px solid black;
							}
							
							.reduz
							{
								max-width: 40px;
								min-width: 100px;
								word-wrap: break-word;
								word-break: break-word;
							}
							
							.reduz.dia
							{
								height: 150px;
							}
							
							.reduz.aula.linha
							{
								height: 52px;
							}
						</style>
						
						<table class="principal" style="font-size: 0.9em; text-align: center;" cellpadding="2">
							<tr>
								<td class="reduz">
									&nbsp;
								</td>
								<?php
									if ($total_Laboratorios > 0)
									{
										do
										{
								?>
											<td class="reduz lab"><?php echo $linha_Laboratorios['nm_laboratorio']; ?></td>
								<?php
										}
										while ($linha_Laboratorios = mysqli_fetch_assoc($result_Laboratorios));
									}
								?>
							</tr>
							<?php
								$limite = 2;
								
								for ($cont = 1; $cont <= $limite; $cont = $cont + 1)
								{
									if ($cont == 1)
									{
										$periodo = 'M';
										$periodoLinha = 'MANHÃ';
									}
									else if ($cont == 2)
									{
										$periodo = 'T';
										$periodoLinha = 'TARDE';
									}
							?>
									<tr>
										<td class="reduz dia">
											<?php echo $periodoLinha; ?>
										</td>
										
										<?php
											$result_Laboratorios = $conexaoPrincipal -> Query($query_Laboratorios);
											$linha_Laboratorios = mysqli_fetch_assoc($result_Laboratorios);
											$total_Laboratorios = mysqli_num_rows($result_Laboratorios);
																
											if ($total_Laboratorios > 0)
											{
												do
												{
										?>
													<td class="reduz aula" style="padding: 0px;">
														<style>
															table.second
															{
																width: 100%;
																height: 100%;
																min-height: 100px;
															}
															
															table.second, table.second th, table.second td 
															{
																border: 0px;
															}
														</style>
														<table class="second" cellpadding="0">
															<?php										
																for ($i = 1; $i <= 3; $i = $i + 1)
																{
															?>
																	<tr class="reduz aula linha" style="border-top: 0.5px solid black;">
																		<?php
																			$codigoLaboratorio = $linha_Laboratorios['cd_laboratorio'];
																			
																			$result_Aula = $conexaoPrincipal -> Query("select tb_aula.cd_aula,
																															   tb_aula.dd_semana_aula,
																															   tb_aula.ds_periodo,
																															   tb_aula.cd_horario,
																															   (select tb_laboratorio.nm_laboratorio from tb_laboratorio where tb_laboratorio.cd_laboratorio = tb_aula.cd_laboratorio) as nm_laboratorio,
																															   (select tb_curso.nm_curso from tb_curso where tb_curso.cd_curso = tb_aula.cd_curso) as nm_curso,
																															   (select tb_curso.ds_cor_hex from tb_curso where tb_curso.cd_curso = tb_aula.cd_curso) as ds_cor_hex,
																															   (select tb_professor.nm_professor from tb_professor where tb_professor.cd_professor = tb_aula.cd_professor) as nm_professor
																														  from tb_aula
																															 where dd_semana_aula = '6' and ds_periodo = '$periodo' and ds_semestre = '$semestre' and cd_laboratorio = '$codigoLaboratorio' and cd_horario = '$i' $whereCursoAula");
																			$linha_Aula = mysqli_fetch_assoc($result_Aula);
																			$total_Aula = mysqli_num_rows($result_Aula);
																			
																			if ($total_Aula > 0)
																			{
																		?>
																				<td style="background-color: <?php echo $linha_Aula['ds_cor_hex']; ?>">&nbsp;<?php echo strtoupper($linha_Aula['nm_professor']); ?></td>	
																		<?php
																			}
																			else
																			{
																		?>											
																				<td>&nbsp;</td>
																		<?php
																			}
																		?>
																	</tr>
															<?php
																}
															?>
														</table>
													</td>
										<?php
												}
												while ($linha_Laboratorios = mysqli_fetch_assoc($result_Laboratorios));
											}
										?>
									</tr>
							<?php
								}
							?>				
						</table>
					</div>
		<?php
				}
			}
		?>
	</body>
	<script>
		window.onload = function()
		{
			cmb_planilha.onchange();
		}
	</script>
</html>