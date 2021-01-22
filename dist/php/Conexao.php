<?php
	ini_set('default_charset','UTF-8');

	setlocale( LC_ALL, 'pt_BR', 'pt_BR.iso-8859-1', 'pt_BR.utf-8', 'portuguese' );

	date_default_timezone_set( 'America/Sao_Paulo' );
	
	
			
	/*class Conexao
	{
		private $conexao;
		
		public function AbrirConexao($host, $user, $pass, $banco)
		{
			$this -> conexao = mysqli_connect($host, $user, $pass) or die(mysqli_error($this -> conexao));
			mysqli_select_db($this -> conexao, $banco) or die(mysqli_error($this -> conexao));
			
			$timeZone = mysqli_query($this -> conexao, "set time_zone = '-03:00'");
			mysqli_set_charset($this -> conexao, 'utf8');
			
			return true;
		}
		
		public function FecharConexao()
		{
			mysqli_close($this -> conexao);
			
			return true;
		}
		
		public function Query($query)
		{
			$result = mysqli_query($this -> conexao, $query);
			
			return $result;
		}
		
		public function getConexao()
		{
			return $this -> conexao;
		}
	}*/
?>
