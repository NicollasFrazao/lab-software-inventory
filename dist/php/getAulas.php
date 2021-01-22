<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_GET['codigoLaboratorio']) || $_GET['codigoLaboratorio'] == '' || !isset($_GET['semestre']))
	{
		echo 'Laboratório não encontrado!';
		exit;
	}
	
	function getDiaSemana($dia)
	{
		switch ($dia)
		{
			case 1:
			{
				return 'Segunda-feira';
			}
			break;
			
			case 2:
			{
				return 'Terça-feira';
			}
			break;
			
			case 3:
			{
				return 'Quarta-feira';
			}
			break;
			
			case 4:
			{
				return 'Quinta-feira';
			}
			break;
			
			case 5:
			{
				return 'Sexta-feira';
			}
			break;
			
			case 6:
			{
				return 'Sábado';
			}
			break;
			
			default:
			{
				return '';
			}
		}
	}
	
	function getPeriodo($valor)
	{
		switch ($valor)
		{
			case 1:
			{
				return 'Manhã';
			}
			break;
			
			case 2:
			{
				return 'Tarde';
			}
			break;
			
			case 3:
			{
				return 'Noite';
			}
			break;
			
			default:
			{
				return '';
			}
		}
	}

	function getPeriodoSigla($valor)
	{
		switch ($valor)
		{
			case 1:
			{
				return 'M';
			}
			break;
			
			case 2:
			{
				return 'T';
			}
			break;
			
			case 3:
			{
				return 'N';
			}
			break;
			
			default:
			{
				return '';
			}
		}
	}
	
	function getHorario($valor)
	{
		switch ($valor)
		{
			case 1:
			{
				return 'Primeiro';
			}
			break;
			
			case 2:
			{
				return 'Segundo';
			}
			break;
			
			case 3:
			{
				return 'Terceiro';
			}
			break;
			
			default:
			{
				return '';
			}
		}
	}
	
	$codigoLaboratorio = $_GET['codigoLaboratorio'];
	$semestre = $_GET['semestre'];
?>

<?php
	for ($cont = 1; $cont <= 6; $cont = $cont + 1)
	{
?>
		<tr valign="center" style="background-color: #0D47A1;">
			<td colspan="4">
				<span><?php echo getDiaSemana($cont); ?></span>
			</td>
		</tr>
		<tr valign="center">
			<td>&nbsp;</td>
			<td><span>Primeiro horário</span></td>
			<td><span>Segundo horário</span></td>
			<td><span>Terceiro horário</span></td>
		</tr>
		<?php
			for ($i = 1; $i <= 3; $i = $i + 1)
			{
				$sigla = getPeriodoSigla($i);
		?>
				<tr valign="center">
					<td>
						<span><?php echo getPeriodo($i); ?></span>
					</td>
					<?php 
						for ($j = 1; $j <= 3; $j = $j + 1)
						{
							$query_Aulas = "select tb_aula.dd_semana_aula,
												   tb_aula.ds_periodo,
												   tb_aula.cd_horario,
												   tb_aula.ds_semestre,
												   (select tb_curso.ds_cor_hex from tb_curso where tb_curso.cd_curso = tb_aula.cd_curso) as ds_cor_hex,
												   (select tb_curso.nm_curso from tb_curso where tb_curso.cd_curso = tb_aula.cd_curso) as nm_curso,
												   (select tb_materia.nm_materia from tb_materia where tb_materia.cd_materia = tb_aula.cd_materia) as nm_materia,
												   (select tb_professor.nm_professor from tb_professor where tb_professor.cd_professor = tb_aula.cd_professor) as nm_professor
											  from tb_aula
												where tb_aula.cd_laboratorio = '$codigoLaboratorio'
												  and tb_aula.ds_semestre = '$semestre'
												  and tb_aula.dd_semana_aula = '$cont'
												  and tb_aula.ds_periodo = '$sigla'
												  and tb_aula.cd_horario = '$j'";
							$result_Aulas = $conexaoPrincipal -> prepare($query_Aulas);
							$result_Aulas -> execute();
							
							$aux = 0;
							
							if ($result_Aulas -> rowCount() > 0)
							{
								while ($linha_Aulas = $result_Aulas -> fetch())
								{
									if ($linha_Aulas['dd_semana_aula'] == $cont && $linha_Aulas['ds_periodo'] == getPeriodoSigla($i) && $linha_Aulas['cd_horario'] == $j)
									{
										$aux = 1;
						?>
										<td valign="center" style="background-color: <?php if ($linha_Aulas['ds_cor_hex'] == '') {echo '';} else {echo $linha_Aulas['ds_cor_hex'];} ?> !important;">	
											<table width="100%">
												<tr>
													<td>
														<span><b>Curso: </b> <?php echo $linha_Aulas['nm_curso']; ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<span><b>Disciplina: </b><?php echo $linha_Aulas['nm_materia']; ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<span><b>Professor: </b><?php echo $linha_Aulas['nm_professor']; ?></span>
													</td>
												</tr>
												<tr>
													<td>
														<span><b>Softwares: </b>4</span>
													</td>
												</tr>
												<tr>
													<td align="right">
														<button type="button" class="btn btn-default" onclick="AbrirEdicaoHorario('<?php echo $cont; ?>', '<?php echo getPeriodoSigla($i); ?>', '<?php echo $j; ?>', semestre);">Editar</button>
													</td>
												</tr>
											</table>
										</td>
						<?php
									}
								}
								
								if ($aux == 0)
								{
						?>
									<td valign="center" class="bg-gray">	
										<table width="100%">
											<tr>
												<td>
													<span>Horário Disponível</span>
												</td>
											</tr>
											<tr>
												<td align="right">
													<button type="button" class="btn btn-default" onclick="AbrirEdicaoHorario('<?php echo $cont; ?>', '<?php echo getPeriodoSigla($i); ?>', '<?php echo $j; ?>', semestre);">Agendar</button>
												</td>
											</tr>
										</table>
									</td>
						<?php
								}
							}
							else
							{
						?>
								<td valign="center" class="bg-gray">	
									<table width="100%">
										<tr>
											<td>
												<span>Horário Disponível</span>
											</td>
										</tr>
										<tr>
											<td align="right">
												<button type="button" class="btn btn-default" onclick="AbrirEdicaoHorario('<?php echo $cont; ?>', '<?php echo getPeriodoSigla($i); ?>', '<?php echo $j; ?>', semestre);">Agendar</button>
											</td>
										</tr>
									</table>
								</td>
						<?php
							}
						?>
					<?php
						}
					?>
				</tr>
		<?php
			}
		?>
		<tr style="background-color: transparent;"><td colspan="4">&nbsp;</td><tr/>
<?php
	}
?>

