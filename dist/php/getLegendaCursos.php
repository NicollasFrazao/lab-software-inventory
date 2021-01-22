<?php
	include 'Conexao.php';
	include 'ConexaoPrincipal.php';
	
	$result_Cores = $conexaoPrincipal -> prepare('select nm_curso,
													   ds_cor_hex
												  from tb_curso
													where ds_cor_hex is not null
														order by nm_curso');
	$result_Cores -> execute();
?>

<?php
	if ($result_Cores -> rowCount() > 0)
	{
		while ($linha_Cores = $result_Cores -> fetch())
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
				<div class="col-md-3 col-sm-6 col-xs-12">
					<div class="info-box" style="min-height: 50px;">
						<!-- Apply any bg-* class to to the icon to color it -->
						<span class="info-box-icon bg-gray" style="width: 50px; height: 50px; background-color: <?php if ($linha_Cores['ds_cor_hex'] == '') {echo '#aaa';} else {echo $linha_Cores['ds_cor_hex'];} ?> !important;"></span>
						<div class="info-box-content" style="margin-left: 55px !important;">
							<span class="info-box-text"><?php echo $linha_Cores['nm_curso']; ?></span>
						</div><!-- /.info-box-content -->
					</div><!-- /.info-box -->
				</div>
<?php
			}
		}
	}
?>