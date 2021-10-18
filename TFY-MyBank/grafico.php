
<?php
require_once('includes/connect.php');
include('includes/header.php');

$table = "valori_giornalieri_azione";

$sql = "SELECT * FROM $table sdv JOIN azioni s ON sdv.idazione=s.id WHERE s.simbolo=? ORDER BY giorno ";
if(isset($_GET['days']) & !empty($_GET['days'])){ $sql .= " DESC LIMIT {$_GET['days']}"; }else{ $sql .= " ASC";}
$result = $db->prepare($sql);
$res = $result->execute(array($_GET['scrip'])) or die(print_r($result->errorInfo(), true));
$stockvals = $result->fetchAll(PDO::FETCH_ASSOC);

if(isset($_GET['days']) & !empty($_GET['days'])){
  $stockvals = array_reverse($stockvals);
}
?>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      <?php
        foreach ($stockvals as $stockval) {
      ?>

      ['<?php echo $stockval['giorno']; ?>', <?php echo $stockval['prezzo_minimo']; ?>, <?php echo $stockval['prezzo_apertura']; ?>, <?php echo $stockval['prezzo_chiusura']; ?>, <?php echo $stockval['prezzo_massimo']; ?>],
      <?php } ?>

    ], true);

    var options = {
      legend:'none',
      candlestick: {
            fallingColor: { strokeWidth: 0, fill: '#a52714', stroke: '#a52714' }, // rosso
            risingColor: { strokeWidth: 0, fill: '#0f9d58', stroke: '#0f9d58' }   // verde
          }
    };

    var chart = new google.visualization.CandlestickChart(document.getElementById('chart_div'));

    chart.draw(data, options);
  }
    </script>
  </head>

  <body>
    <h2></h2>
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">&nbsp<?php echo $_GET['scrip']; ?> - <?php if(isset($_GET['type']) & !empty($_GET['type'])){ echo $_GET['type']; }else{ echo "Storico";} ?><?php if(isset($_GET['days']) & !empty($_GET['days'])){ echo " - " .$_GET['days'] . " Giorni"; } ?> Grafico</h1>
  

<div id="chart_div" style="width: 100%; height: 550px;"></div>
<center>
    <a href="http://localhost/TFY-MyBank/grafico.php?scrip=<?php echo $_GET['scrip']; ?>&days=30">30 Giorni</a> | <a href="http://localhost/TFY-MyBank/grafico.php?scrip=<?php echo $_GET['scrip']; ?>&days=60">60 Giorni</a> | <a href="http://localhost/TFY-MyBank/grafico.php?scrip=<?php echo $_GET['scrip']; ?>&days=90">90 Giorni</a> | <a href="http://localhost/TFY-MyBank/grafico.php?scrip=<?php echo $_GET['scrip']; ?>&days=180">180 Giorni</a> | <a href="http://localhost/TFY-MyBank/grafico.php?scrip=<?php echo $_GET['scrip']; ?>&days=360">360 Giorni</a> <br><br>
</center>
</div>
  </body>
</html>
