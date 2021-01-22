<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	$mes = date('m');
	$ano = date('Y');
	
	$diaSemana = date('w');
	$horas = date('H');
	$minutos = date('i');
	
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
	
	$result_Laboratorios = $conexaoPrincipal -> prepare('select cd_laboratorio, nm_laboratorio from tb_laboratorio order by nm_laboratorio');
	$result_Laboratorios -> execute();
	
	/*
	60 min -> 1 h
	$minutos min -> y h
	
	y = $minutos/60;
	
	1 h -> 60*60
	($horas + ($minutos/60)) -> $segundos
	*/
	
	$segundos = ($horas + ($minutos/60))*60*60;
	
	//echo $segundos.'<br/>';
	
	// 7h30 até 13h30
	if (27000 <= $segundos && $segundos < 48600)
	{
		$periodo = 'M';
		
		// 7h30 até 9h30
		if (27000 <= $segundos && $segundos < 34200)
		{
			$horario = '1';
		}
		// 9h30 até 11h30
		else if (34200 <= $segundos && $segundos < 41400)
		{
			$horario = '2';
		}
		// 11h30 até 13h30
		else if (41400 <= $segundos && $segundos < 48600)
		{
			$horario = '3';
		}
		else
		{
			$horario = '';
		}
	}
	// 13h30 até 18h00
	else if (48600 <= $segundos && $segundos < 64800)
	{
		$periodo = 'T';
		
		// 13h30 até 15h00
		if (48600 <= $segundos && $segundos < 54000)
		{
			$horario = '1';
		}
		// 15h00 até 18h00
		else if (54000 <= $segundos && $segundos < 64800)
		{
			$horario = '2';
		}
		else
		{
			$horario = '';
		}
	}
	// 18h00 até 22h30
	else if (64800 <= $segundos && $segundos <= 81000)
	{
		$periodo = 'N';
		
		// 18h00 até 20h30
		if (64800 <= $segundos && $segundos < 73800)
		{
			$horario = '1';
		}
		// 20h30 até 22h30
		else if (73800 <= $segundos && $segundos < 81000)
		{
			$horario = '2';
		}
		else
		{
			$horario = '';
		}
	}
	else
	{
		$periodo = '';
		$horario = '';
	}
?>

<?php
	if ($result_Laboratorios -> rowCount() != 0)
	{
		$cont = 0;
		
		while ($linha_Laboratorios = $result_Laboratorios -> fetch())
		{
			$codigoLaboratorio = $linha_Laboratorios['cd_laboratorio'];
			
			$result_Aula = $conexaoPrincipal -> prepare('select tb_curso.cd_curso,
															   tb_curso.nm_curso,
															   tb_curso.ds_cor_hex
														  from tb_aula inner join tb_curso
															on tb_aula.cd_curso = tb_curso.cd_curso
															where tb_aula.dd_semana_aula = :diaSemana
															  and tb_aula.ds_periodo = :periodo
															  and tb_aula.cd_horario = :horario
															  and tb_aula.cd_laboratorio = :codigoLaboratorio
															  and tb_aula.ds_semestre = :semestre limit 1');
			$result_Aula -> bindParam(':diaSemana', $diaSemana);
			$result_Aula -> bindParam(':periodo', $periodo);
			$result_Aula -> bindParam(':horario', $horario);
			$result_Aula -> bindParam(':codigoLaboratorio', $codigoLaboratorio);
			$result_Aula -> bindParam(':semestre', $semestre);
			
			$result_Aula -> execute();
			$linha_Aula = $result_Aula -> fetch();
			
			//echo $diaSemana.';-;'.$periodo.';-;'.$horario.';-;'.$codigoLaboratorio.';-;'.$semestre.';-;'.$linha_Aula['ds_cor_hex']."<br/>";
?>
			<div class="col-lg-2 col-xs-6">
				<a href="dadosLaboratorio.php?codigoLaboratorio=<?php echo $linha_Laboratorios['cd_laboratorio']; ?>&semestre=<?php echo $semestre; ?>"><div class="small-box bg-gray center" style="min-height: 50px; background-color: <?php if ($linha_Aula['ds_cor_hex'] == '') {echo '';} else {echo $linha_Aula['ds_cor_hex'].' !important';} ?>;"><?php echo $linha_Laboratorios['nm_laboratorio']; ?></div></a>
			</div>
<?php
		}
	}
	else
	{
?>	
<?php
	}
?>