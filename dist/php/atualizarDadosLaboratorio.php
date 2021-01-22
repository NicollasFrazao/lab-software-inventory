<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_POST['codigoLaboratorio']))
	{
		exit;
	}
	
	$codigoLaboratorio =  $_POST['codigoLaboratorio'];
	$nomeLaboratorio =  $_POST['nomeLaboratorio'];
	$semestre =  $_POST['semestre'];
	$laboratorioAtivo =  $_POST['laboratorioAtivo'];
	$softwaresLaboratorio =  $_POST['softwaresLaboratorio'];
	
	if ($laboratorioAtivo != 1)
	{
		$laboratorioAtivo = 0;
	}
	
	$result = $conexaoPrincipal -> prepare('update tb_laboratorio set nm_laboratorio = :nomeLaboratorio, ic
?>