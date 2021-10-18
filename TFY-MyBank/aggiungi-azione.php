<?php
    if ($_COOKIE["login"] == "OK")
    {
?>
<?php
require_once('includes/config.php');
require_once('includes/connect.php');
session_start();
if(isset($_POST) & !empty($_POST)){

    if(empty($_POST['azione'])){ $errors[] = "Il campo 'Codice azione' è richiesto !"; }else{

        $sql = "SELECT * FROM azioni WHERE simbolo=?";
        $result = $db->prepare($sql);
        $res = $result->execute(array($_POST['azione'])) or die(print_r($result->errorInfo(), true));
        $count = $result->rowCount();
        if($count == 1){
            $errors[] = "Il titolo ricercato è gia presente nel database";
        }
    }


    if(isset($_POST['csrf_token'])){
        if($_POST['csrf_token'] === $_SESSION['csrf_token']){
        }else{
            $errors[] = "Problema con la verifica del CSRF Token";
        }
    }else{
        $errors[] = "Problema con la verifica del CSRF Token";
    }


    $max_time = 60*60*24;
    if(isset($_SESSION['csrf_token_time'])){

        $token_time = $_SESSION['csrf_token_time'];
        if(($token_time + $max_time) >= time()){
        }else{
            
            $errors[] = "CSRF Token Scaduto";
            unset($_SESSION['csrf_token']);
            unset($_SESSION['csrf_token_time']);
        }
    }else{
     
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
    }

    if(empty($errors)){
        $curl = curl_init();
        $symbol = $_POST['azione'].".".$_POST['mercato'];
        $prova = $_POST['azione'];
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://api.marketstack.com/v1/tickers?symbols=$prova&access_key=$token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 90,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if($err){
            echo "cURL Error :" . $err;
        }

        $name = json_decode($response, true);
        $companyname = $name['data'][0]['name'];

     
        $sql = "INSERT INTO azioni (simbolo, nome, mercato) VALUES (:simbolo, :nome, :mercato)";
        $result = $db->prepare($sql);
        $values = array(':simbolo'   => $_POST['azione'],
                        ':nome'     => $companyname,
                        ':mercato' => $_POST['mercato']
                        );
        $res = $result->execute($values) or die(print_r($result->errorInfo(), true));
        if($res){
            $stockid = $db->lastInsertID();
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=$prova&apikey=$key&outputsize=full",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 90,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET"
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if($err){
                echo "cURL Error :" . $err;
            }else{
                //echo $response;
                
                $data = json_decode($response, true);
                $dates = array_keys($data['Time Series (Daily)']);
                foreach ($dates as $date) {
                    if(isset($data['Time Series (Daily)'][$date]) & !empty($data['Time Series (Daily)'][$date])){
                        if($data['Time Series (Daily)'][$date]['1. open'] != '0.0000'){

                        
                            $dailysql = "INSERT INTO valori_giornalieri_azione (idazione, prezzo_apertura, prezzo_massimo, prezzo_minimo, prezzo_chiusura, volume, giorno) VALUES (:idazione, :prezzo_apertura, :prezzo_massimo, :prezzo_minimo, :prezzo_chiusura, :volume, :giorno)";
                            $dailyresult = $db->prepare($dailysql);
                            $values = array(':idazione'      => $stockid,
                                            ':prezzo_apertura'   => $data['Time Series (Daily)'][$date]['1. open'],
                                            ':prezzo_massimo'   => $data['Time Series (Daily)'][$date]['2. high'],
                                            ':prezzo_minimo'    => $data['Time Series (Daily)'][$date]['3. low'],
                                            ':prezzo_chiusura'  => $data['Time Series (Daily)'][$date]['4. close'],
                                            ':volume'       => $data['Time Series (Daily)'][$date]['5. volume'],
                                            ':giorno'   => $date
                                            );

                            $dailyres = $dailyresult->execute($values) or die(print_r($dailyresult->errorInfo(), true));
                            

                        }
                    }
                }
                $messages[] = "Titolo aggiunto";
            }
        }
    }
}
    //
    //
    //

$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();



include('includes/header.php');
include('includes/navigation.php');
?>
<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Aggiungi titoli azioniari</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Crea la tua watchlist...
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
                                <div class="col-lg-6">
                                    <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                                    <div class="form-group">
                                        <label>Codice azione</label>
                                        <input class="form-control" name="azione" placeholder="Inserisci il Codice Alfanumerico">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>Mercato</label>
                                        <select name="mercato" class="form-control">
                                            <option value="NYSE">NYSE</option>
                                        </select>
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-primary" value="Invia" />
                            </form>
                        </div>
                        <!-- /.col-lg-6 (nested) -->   
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<?php
include('includes/footer.php');
?>
<?php
require_once('includes/connect.php');
$sql = "SELECT * FROM azioni";
$result = $db->prepare($sql);
$result->execute() or die(print_r($result->errorInfo(), true));
$stocks = $result->fetchAll(PDO::FETCH_ASSOC);
foreach ($stocks as $stock) {

    $dayssql = "SELECT * FROM valori_giornalieri_azione WHERE idazione=?";
    $daysresult = $db->prepare($dayssql);
    $daysres = $daysresult->execute(array($stock['id'])) or die(print_r($daysresult->errorInfo(), true));
    $dayscount = $daysresult->rowCount();
    $stocklh = $daysresult->fetchAll(PDO::FETCH_ASSOC);

   
    $sql = "SELECT * FROM valori_giornalieri_azione WHERE idazione=? ORDER BY giorno ASC LIMIT 1";
    $result = $db->prepare($sql);
    $result->execute(array($stock['id'])) or die(print_r($result->errorInfo(), true));
    $stockstartvals = $result->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM valori_giornalieri_azione WHERE idazione=? ORDER BY giorno DESC LIMIT 1";
    $result = $db->prepare($sql);
    $result->execute(array($stock['id'])) or die(print_r($result->errorInfo(), true));
    $stockcurrentvals = $result->fetch(PDO::FETCH_ASSOC);


    $pricelh = array_column($stocklh, 'prezzo_apertura');
    $stocklow = $stocklh[array_search(min($pricelh), $pricelh)];
    $stockhigh = $stocklh[array_search(max($pricelh), $pricelh)];


    $sql = "INSERT INTO valori_cache_azione (idazione, giorni, prezzo_iniziale, data_iniziale, prezzo_attuale, data_attuale, prezzo_atl, data_atl, prezzo_ath, data_ath) VALUES (:idazione, :giorni, :prezzo_iniziale, :data_iniziale, :prezzo_attuale, :data_attuale, :prezzo_atl, :data_atl, :prezzo_ath, :data_ath)";
    $result = $db->prepare($sql);
    $values = array(':idazione'      => $stock['id'],
                    ':giorni'         => $dayscount,
                    ':prezzo_iniziale'   => $stockstartvals['prezzo_apertura'],
                    ':data_iniziale'    => $stockstartvals['giorno'],
                    ':prezzo_attuale' => $stockcurrentvals['prezzo_apertura'],
                    ':data_attuale'  => $stockcurrentvals['giorno'],
                    ':prezzo_atl'    => $stocklow['prezzo_apertura'],
                    ':data_atl'     => $stocklow['giorno'],
                    ':prezzo_ath'    => $stockhigh['prezzo_apertura'],
                    ':data_ath'     => $stockhigh['giorno']
                    );

    $res = $result->execute($values) or die(print_r($result->errorInfo(), true));


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
