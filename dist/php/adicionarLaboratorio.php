<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	if (!isset($_POST['nomeLaboratorio']))
	{
		echo '0;-;Insira o nome do laboratorio!';
		exit;
	}
	else
	{
		$nomeLaboratorio = $_POST['nomeLaboratorio'];
		
		$result = $conexaoPrincipal -> prepare('insert into tb_laboratorio(nm_laboratorio) values(:nomeLaboratorio)');
		$result -> bindParam(':nomeLaboratorio', $nomeLaboratorio);
		
		try
		{
			$result -> execute();
			
			echo '1;-;Laboratório adicionado com sucesso!';
			exit;
		}
		catch (PDOException $exe)
		{
			echo '0;-;Não foi possível adicionar o laboratório! Erro: '.$exe -> getMessage();
			exit;
		}
	}
?>