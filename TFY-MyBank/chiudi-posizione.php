
<?php
    if ($_COOKIE["login"] == "OK")
    {


     
                $conn = new mysqli("localhost","root","", "DB_TradingForYouO");
            $a=$_GET['stockid'];
            $b=$_GET['watchlistid'];
                $query = "INSERT INTO storico (storico.creato,storico.quantita,storico.idportafoglio, storico.idazione, storico.tipologia) SELECT portafoglio_azioni.creato,portafoglio_azioni.quantita,portafoglio_azioni.idportafoglio,portafoglio_azioni.idazione,portafoglio_azioni.tipologia FROM portafoglio_azioni WHERE idazione='".$a."'AND idportafoglio='".$b."'";
                $result = $conn->query($query);


                $conn->close();

        ?>
        
        <?php
require_once('includes/connect.php');

$sql = "DELETE FROM portafoglio_azioni WHERE idazione=:stockid AND idportafoglio=:watchlistid";
$result = $db->prepare($sql);
$values = array(':stockid'      => $_GET['stockid'],
                ':watchlistid'  => $_GET['watchlistid']
                );
$res = $result->execute($values) or die(print_r($result->errorInfo(), true));
if($res){
    header("location: sezione.php?id={$_GET['watchlistid']}");
}else{
    header("location: sezione.php?id={$_GET['watchlistid']}");
}
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
