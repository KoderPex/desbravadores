<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header">Consulta de Aniversariantes</h3>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div class="row">
		<?php FILTERS::Data(
			array(
				"filterTo" => "#birthTable",
				"filters" =>
					array(
						array( "id" => "X", "ds" => "Sexo", "icon" => "fa fa-venus-mars" ),
						array( "id" => "C", "ds" => "Classe", "icon" => "fa fa-graduation-cap" ),
						array( "id" => "G", "ds" => "Grupo", "icon" => "fa fa-group" ),
						array( "id" => "MA", "ds" => "Mês de Aniversário", "icon" => "fa fa-calendar-o" ),
						array( "id" => "U", "ds" => "Unidade", "icon" => "fa fa-universal-access" )
					)
			)
		);?>
		</div>
		<div class="row">
			<table class="compact row-border hover stripe display" style="cursor: pointer;" cellspacing="0" width="100%" id="birthTable">
				<thead>
					<tr>
						<th></th>
						<th>Nome Completo</th>
						<th>Unidade</th>
						<th>Dia/Mes</th>
						<th>Idade</th>
					</tr>
				</thead>
			</table>
			<br/>
		</div>
	</div>
</div>
<script src="<?php echo PATTERNS::getVD();?>admin/view/screens/aniversario/index.js<?php echo "?".time();?>"></script>