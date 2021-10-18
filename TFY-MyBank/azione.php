
<?php
    if ($_COOKIE["login"] == "OK")
    {
        //http://localhost/TFY-MyBank/azione.php?scrip=GOOG
?>
<?php
require_once('includes/connect.php');
include('includes/header.php');
include('includes/navigation.php');


  $table = "valori_giornalieri_azione";

?>
    <div id="page-wrapper" style="min-height: 345px;">
        
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Titolo: <?php echo $_GET['scrip']; ?></h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Storico dati 
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                           
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Azione</th>
                                        <th>Giorno</th>
                                        <th>Apertura</th>
                                        <th>Massimo</th>
                                        <th>Minimo</th>
                                        <th>Chiusura</th>
                                        <th>Volume</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    // we should join 2 tabels
                                        $sql = "SELECT DISTINCT * FROM $table sdv JOIN azioni s ON sdv.idazione=s.id WHERE s.simbolo=? ORDER BY giorno DESC ";
                                        $result = $db->prepare($sql);
                                        $res = $result->execute(array($_GET['scrip'])) or die(print_r($result->errorInfo(), true));
                                        $stockvals = $result->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($stockvals as $stockval) {
                                    ?>
                                    <tr>
                                        <td><?php echo $stockval['id']; ?></td>
                                        <td><?php echo $stockval['simbolo']; ?></td>
                                        <td><?php echo $stockval['giorno']; ?></td>
                                        <td><?php echo $stockval['prezzo_apertura']; ?></td>
                                        <td><?php echo $stockval['prezzo_massimo']; ?></td>
                                        <td><?php echo $stockval['prezzo_minimo']; ?></td>
                                        <td><?php echo $stockval['prezzo_chiusura']; ?></td>
                                        <td><?php echo $stockval['volume']; ?></td>
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

</div>
<!-- /#wrapper -->

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
