 <head>
 <meta charset="UTF-8">
 <title>Histórico de Royalties do Município</title>
 <meta name="viewport" content="width-device-width, initial-scale=1">
 <meta name="author" content="Jorge Clésio"> <!-- Não vale hackear e mudar o nome :D -->
  </head>
   	<?php 
  	$conexao = mysqli_connect ("host","user","senha",'bd') or die ("Err SERVER");
	date_default_timezone_set('America/Belem');
	?>
	<!-- Formulário -->
	<center>
	<form method="GET" action="">
	<select id="ano" name="cidade">
	<?php
		if(!$_GET){
		echo "<option value=''>Selecione um Município</option>";	
			$sql="SELECT * FROM cfem group by G"; 
			$sql=mysqli_query($conexao,$sql);
			while($dado=mysqli_fetch_array($sql)){	
			echo "<option value='$dado[G]'>$dado[G]</option>";	
	 
			}
		}
		else{
			echo"<option value='$_GET[cidade]'>$_GET[cidade]</option>";	
				$sql="SELECT * FROM cfem group by G"; 
				$sql=mysqli_query($conexao,$sql);
				while($dado=mysqli_fetch_array($sql)){	
						echo "<option value='$dado[G]'>$dado[G]</option>";	
				}
		}
	?>
	</select>	
		<select id="ano" name="ano"">
		<?php echo !$_GET ? "<option value=19>ANO</option>" : ""; ?>
		<?php echo $_GET[ano] ? "<option value=$_GET[ano]>20$_GET[ano]</option>" : "" ; ?>
		<option value="15">2015
		<option value="16">2016
		<option value="17">2017
		<option value="18">2018
		<option value="19">2019
		</select>
	
	<button>Processar</button>

	</form>	 	<!-- FIM Formulário -->
	</center>

 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
<?php 
		$cor = array('#FF6633', '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6', 
				  '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D',
				  '#80B300', '#809900', '#E6B3B3', '#6680B3', '#66991A', 
				  '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A', '#33FFCC',
				  '#66994D', '#B366CC', '#4D8000', '#B33300', '#CC80CC', 
				  '#66664D', '#991AFF', '#E666FF', '#4DB3FF', '#1AB399',
				  '#E666B3', '#33991A', '#CC9999', '#B3B31A', '#00E680', 
				  '#4D8066', '#809980', '#E6FF80', '#1AFF33', '#999933',
				  '#FF3380', '#CCCC00', '#66E64D', '#4D80CC', '#9900B3', 
				  '#E64D66', '#4DB380', '#FF4D4D', '#99E6E6', '#6666FF');
		?>		  
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Mes", "Valor", { role: "style" }],
		<?php 

		$ano=$_GET['ano'];
		$cidade=$_GET['cidade'];
		
		$sql="SELECT * FROM cfem where G='$cidade' and A like '%$ano'"; 
		$sql=mysqli_query($conexao,$sql);
		while($dado=mysqli_fetch_array($sql)){	
		?>
        ["<?php echo $dado['A'];?>", <?php echo $dado['L'];?>, "<?php echo $cor[$i];  $i=$i+1;?>"],
		
		<?php } ?>
		
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);
      var options = {

        title: "Royalties de <?php echo $cidade .' - 20'. $ano;?>",
		subtitle: 'Compensação Financeira pela Exploração de Recursos Minerais',

        //width: 1200,
        height: 500,
        bar: {groupWidth: "75%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
      chart.draw(view, options);
  }
  </script>
												   
  	<div style="text-align:center;"> <!-- Valor Total -->
	<?php 
		$ano=$_GET['ano'];
		$cidade=$_GET['cidade'];
		$sql="SELECT sum(L) as valor FROM cfem where G='$cidade' and A like '%$ano'"; 
		$sql=mysqli_query($conexao,$sql);
		while($dado=mysqli_fetch_array($sql)){	
		?>
		<?php echo "Total:<b> R$". number_format($dado['valor']) ."</b>";?>
		<?php } ?>
		
	</div> <!-- FIM Valor Total -->
				       
<?php if($_GET){ //Mostra apenas quando existe uma requisição GET ?>
<div id="columnchart_values" style="width: 110%; height: 300px;"></div>
<?php } ?>
