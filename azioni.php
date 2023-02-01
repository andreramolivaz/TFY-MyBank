<?php
    if ($_COOKIE["login"] == "OK")
    {
?>

<?php
require_once('includes/connect.php');
include('includes/header.php');
include('includes/navigation.php');
?>
<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Visualizza titoli azionari</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Storico Dati
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Azione</th>
                                    
                                    <th>Giorni</th>
                                    <th>Prezzo apertura</th>
                                    <th>Prezzo attuale</th>
                                    <th>Minimo</th>
                                    <th>Massimo</th>
                                    <th>Mercato</th>
                                    <th>Analisi</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $sql = "SELECT * FROM azioni";
                                $result = $db->prepare($sql);
                                $result->execute() or die(print_r($result->errorInfo(), true));
                                $stocks = $result->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($stocks as $stock) {
                                    // We can get the number of days by counting the number of rows in db
                                    $sql = "SELECT * FROM valori_cache_azione WHERE idazione=?";
                                    $result = $db->prepare($sql);
                                    $res = $result->execute(array($stock['id'])) or die(print_r($result->errorInfo(), true));
                                    $stockvals = $result->fetch(PDO::FETCH_ASSOC);
                            ?>
                                    <?php
                                    $i=$stock['id'];
                                    $conn = new mysqli("localhost","root","", "DB_TradingForYouO");
                                    if ($conn->connect_error) {
                                    die("Connessione fallita: " . $conn->connect_error);
                                    }
                                    $query = "SELECT prezzo_chiusura, giorno FROM valori_giornalieri_azione WHERE idazione ='".$i."' ORDER BY giorno DESC LIMIT 1";
                                    $result = $conn->query($query);
                                    while($row = $result->fetch_assoc()) {

                                            $prezzo=$row['prezzo_chiusura'];
                                            $trade_date=$row['giorno'];

                                        }
                                    $conn->close();
                                        ?>
                                <tr>
                                    <td><?php echo $stock['id']; ?></td>
                                    <td><a href="azione.php?scrip=<?php echo $stock['simbolo']; ?>"><?php echo $stock['simbolo']; ?></a><br><small><?php echo $stock['nome']; ?></small>
                                    </td>
                                    
                                    <td><?php echo $stockvals['giorni']; ?></td>
                                    <td><?php echo round($stockvals['prezzo_iniziale'],2); ?>
                                        <br><small><?php echo $stockvals['data_iniziale']; ?></small>
                                    </td>
                                    <td><?php echo round($prezzo,2); ?>
                                        <br><small><?php echo $trade_date; ?></small>
                                    </td>
                                    <td><?php echo round($stockvals['prezzo_atl'],2); ?>
                                        <br><small><?php echo $stockvals['data_atl']; ?></small>
                                    </td>
                                    <td><?php echo round($stockvals['prezzo_ath'],2); ?>
                                        <br><small><?php echo $stockvals['data_ath']; ?></small>
                                    </td>
                                    <td><?php echo $stock['mercato']; ?></td>
                                    <td><a href="grafico.php?scrip=<?php echo $stock['simbolo']; ?>">Grafico</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>

<?php
include('includes/footer.php');
?>
<?php
    }
    else
    {
?>
<html>
<head>
<title>TFY | MyBank</title>
<center>
<img src="logo_size.jpg" alt="logo TFY|MyBank">
<h4>Acesso Negato!</h4>
<p>Il nome utente o la password interita non sono validi</p>

<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.loader {
border: 4px solid #f3f3f3;
    border-radius: 50%;
    border-top: 4px solid #416db4;
width: 30px;
height: 30px;
    -webkit-animation: spin 2s linear infinite; /* Safari */
animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
</head>
<body>

<p>Reindirizzamento</p>

<div class="loader"></div>
</center>
</body>
</html>
<?php
        header('Refresh: 3; URL=http://localhost/TFY-MyBank/login.html');
        exit;
    }
    
?>
