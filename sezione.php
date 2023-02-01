<?php
    if ($_COOKIE["login"] == "OK")
    {
?>
<?php
$valore_acquistor=0;
$valore_acquisto=0;
$valore_prova=3;
//1. After submitting the form, insert the stockid with watchlist id in watchlist_stocks table
session_start();
require_once('includes/connect.php');
if(isset($_POST) & !empty($_POST)){
    //print_r($_POST);
    // PHP Form Validations
    if(empty($_POST['stockid'])){ $errors[] = "Nessuna azione disponibile. Riprovare"; }
    if(empty($_POST['quantita'])){ $errors[] = "Nessuna azione disponibile. Riprovare"; }

    // CSRF Token Validation
    if(isset($_POST['csrf_token'])){
        if($_POST['csrf_token'] === $_SESSION['csrf_token']){
        }else{
            $errors[] = "Problema con la verifica del CSRF Token";
        }
    }else{
        $errors[] = "Problema con la verifica del CSRF Token";
    }

    // CSRF Token Time Validation
    $max_time = 60*60*24; // time in seconds
    if(isset($_SESSION['csrf_token_time'])){
        // compare the time with maxtime
        $token_time = $_SESSION['csrf_token_time'];
        if(($token_time + $max_time) >= time()){ // nothing here
        }else{
            // display error message and unset the CSRF Tokens
            $errors[] = "CSRF Token Expired";
            unset($_SESSION['csrf_token']);
            unset($_SESSION['csrf_token_time']);
        }
    }else{
        // unset the CSRF Tokens
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
    }

    if(empty($errors)){
        $sql = "INSERT INTO portafoglio_azioni (idazione, idportafoglio, quantita, tipologia ) VALUES (:stockid, :watchlistid, :quantita, :tipologia)";
        $result = $db->prepare($sql);
        $values = array(':stockid'      => $_POST['stockid'],
                        ':watchlistid'  => $_POST['watchlistid'],
                        ':quantita'  => $_POST['quantita'],
                        ':tipologia'  => $_POST['tipologia']
                        );
        $res = $result->execute($values) or die(print_r($result->errorInfo(), true));
        if($res){
            $messages[] = "Azione aggiunta nel tuo portafoglio";
        }else{
            $errors[] = "Errore nell'acquisto";
        }
    }
}

$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();

include('includes/header.php');
include('includes/navigation.php');

$sql = "SELECT * FROM portafoglio WHERE id=?";
$result = $db->prepare($sql);
$result->execute(array($_GET['id'])) or die(print_r($result->errorInfo(), true));
$watchlist = $result->fetch(PDO::FETCH_ASSOC);
?>
<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Gestione: <?php echo $watchlist['nome']; ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Acquista 
                </div>
                <div class="panel-body">
                    <?php
                        if(!empty($errors)){
                            echo "<div class='alert alert-danger'>";
                            foreach ($errors as $error) {
                                echo "<span class='glyphicon glyphicon-remove'></span>&nbsp;" . $error ."<br>";
                            }
                            echo "</div>";
                        }
                    ?>
                    <?php
                        if(!empty($messages)){
                            echo "<div class='alert alert-success'>";
                            foreach ($messages as $message) {
                                echo "<span class='glyphicon glyphicon-ok'></span>&nbsp;" . $message ."<br>";
                            }
                            echo "</div>";
                        }
                    ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form" method="post">
                                <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                                <input type="hidden" name="watchlistid" value="<?php echo $_GET['id']; ?>">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Azioni disponibili</label>
                                        <select name="stockid" class="form-control">
                                            <?php
                                                $sql = "SELECT * FROM azioni";
                                                $result = $db->prepare($sql);
                                                $result->execute() or die(print_r($result->errorInfo(), true));
                                                $stocks = $result->fetchAll(PDO::FETCH_ASSOC);
                                                foreach ($stocks as $stock) {
                                            ?>
                                            <option value="<?php echo $stock['id'] ?>"><?php echo $stock['simbolo'] ?> - <?php echo $stock['nome'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                <div class = "fortelement">
                                <label>Quantita</label>
                                <br>
                                <input type="number" min="1" name="quantita"  required="required">

                                <div class="checkbox-inline">
                                <input class="form-check-input" type="radio" name="tipologia" id="inlineRadio1" value="Compra">
                                <label class="form-check-label" for="inlineRadio1">Compra</label>
                                </div>
                                    <div class="checkbox-inline">
                                    <input class="form-check-input" type="radio" name="tipologia" id="inlineRadio2" value="Vendi">
                                    <label class="form-check-label" for="inlineRadio2">Vendi</label>
                                        </div>
              
                                        </div>
       
                                        <br>
                                
                                        
                                        <input type="submit" class="btn btn-primary" value="Conferma" />
                                    </div>
                                </div>
</div>
                            </form>
                        </div>
                        <!-- /.col-lg-6 (nested) -->   
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>

        
        <?php
            $sql = "SELECT s.id, s.nome, s.simbolo, s.mercato FROM azioni AS s JOIN portafoglio_azioni AS ws ON s.id=ws.idazione WHERE ws.idportafoglio=?  ORDER BY ws.creato DESC";
            //$sql = "SELECT * FROM stocks";
            $result = $db->prepare($sql);
            $result->execute(array($_GET['id'])) or die(print_r($result->errorInfo(), true));
            $stockscount = $result->rowCount();
            $stocks = $result->fetchAll(PDO::FETCH_ASSOC);
            if($stockscount >= 1){
        ?>
        <div class="panel panel-default">
                <div class="panel-heading">
                    Titoli già presenti:
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Tipologia</th>
                                    <th>Azione</th>
                                    <th>Quantità</th>
                                    <th>Prezzo acquisto</th>
                                    <th>Prezzo attuale</th>
                                    
                            
                                    <th>Mercato</th>
                                    <th>Operazioni</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            // fetch the stock details from watchlist_stocks table based on watchlist id
                                
                                foreach ($stocks as $stock) {
                                    // We can get the number of days by counting the number of rows in db
                                    $sql = "SELECT * FROM valori_cache_azione WHERE idazione=?  ";
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
    $query = "SELECT prezzo_apertura, giorno FROM valori_giornalieri_azione WHERE idazione ='".$i."' ORDER BY giorno DESC LIMIT 1";
    $result = $conn->query($query);
    while($row = $result->fetch_assoc()) {

            $prezzo=$row['prezzo_apertura'];
            $trade_date=$row['giorno'];

        }
    $conn->close();
        ?>
<?php
        $conn = new mysqli("localhost","root","", "DB_TradingForYouO");
        if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
        }
        $query = "SELECT quantita, creato, tipologia,id FROM portafoglio_azioni WHERE idazione ='".$i."'";
        $result = $conn->query($query);
        while($row = $result->fetch_assoc()) {
                
                $idpa=$row['id'];
                $creata=$row['creato'];
                $quantita=$row['quantita'];
                $tipologia=$row['tipologia'];

            }
        $conn->close();

?>
<?php
    $prezzo_acquisto=0;
    
        $conn = new mysqli("localhost","root","", "DB_TradingForYouO");
        if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
        }
        $query = "SELECT prezzo_apertura FROM valori_giornalieri_azione WHERE idazione ='".$i."' AND giorno ='".$creata."' ";
        $result = $conn->query($query);
        while($row = $result->fetch_assoc()) {
                
                $prezzo_acquisto=$row['prezzo_apertura'];
               
            }
            $valore_singolo=$quantita*$prezzo_acquisto;
            $valore_acquisto=$valore_acquisto+$valore_singolo;
            
            $valore_singolor=$prezzo*$quantita;
            $valore_acquistor=$valore_acquistor+$valore_singolor;
        $conn->close();

?>

                                <tr>
                                    <td><?php echo $idpa; ?> </td>
                                    <td><?php echo $tipologia; ?></td>
                                    <td><a href="azione.php?scrip=<?php echo $stock['simbolo']; ?>"><?php echo $stock['simbolo']; ?></a><br><small><?php echo $stock['nome']; ?></small>
                                    </td>

                                    <td><?php echo $quantita; ?></td>
                                    <td><?php echo round($prezzo_acquisto,2); ?>
                                        <br><small><?php echo $creata; ?></small>
                                    </td>
                                    <td><?php echo round($prezzo,2); ?>
                                        <br><small><?php echo $trade_date; ?></small>
                                    </td>
                                    
                                    
                                    <td><?php echo $stock['mercato']; ?></td>
                                    <td><a href="grafico.php?scrip=<?php echo $stock['simbolo']; ?>">Grafico <br> <a href="chiudi-posizione.php?stockid=<?php echo $stock['id']; ?>&watchlistid=<?php echo $_GET['id']; ?>">Chiudi posizione</a>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                <table class="table table-hover">
                    <thead>
                        <tr>
                <th>Saldo:</th>
                
                    
                </thead>
                </table>
                <?php
                $saldo=$valore_acquistor-$valore_acquisto;
                        if($saldo<0){
                            echo "<div class='alert alert-danger'>";
                                echo "<span class='glyphicon glyphicon-thumbs-down'></span>&nbsp;|   " . round($saldo,2) ."<br>";
                            echo "</div>";
                        }
                    ?>
                    <?php
                        if(!empty($saldo>=0)){
                            echo "<div class='alert alert-success'>";
                                echo  "<span class='glyphicon glyphicon-thumbs-up'></span>&nbsp;|   " .round($saldo,2) ."<br>";
                            echo "</div>";
                        }
                    ?>

                </div>

        <?php } ?>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
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
