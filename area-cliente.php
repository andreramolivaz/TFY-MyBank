
<?php
    if ($_COOKIE["login"] == "OK")
    {
?>
<?php
//1. After submitting the form, insert the stockid with watchlist id in watchlist_stocks table
session_start();
if(isset($_POST) & !empty($_POST)){
    //print_r($_POST);
    // PHP Form Validations
   

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
            $errors[] = "CSRF Token scaduto";
            unset($_SESSION['csrf_token']);
            unset($_SESSION['csrf_token_time']);
        }
    }else{
        // unset the CSRF Tokens
        unset($_SESSION['csrf_token']);
        unset($_SESSION['csrf_token_time']);
    }

}

$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();

include('includes/header.php');
include('includes/navigation.php');
    ?>
<?php

    $conn = new mysqli("localhost","root","", "DB_TradingForYou");
    if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
    }
    $query = "SELECT * FROM cliente WHERE idcliente =1";
    $result = $conn->query($query);
    while($row = $result->fetch_assoc()) {

            $nome=$row['nome'];
            $cognome=$row['cognome'];
            $data_nascita=$row['data_nascita'];
            $cod_fiscale=$row['cod_fiscale'];
            $email=$row['email'];
        }
    $conn->close();
    

?>
<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Benvenuto <?php echo $nome; ?></h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    I tuoi dati
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
                    <td>Nome: <?php echo $nome; ?></td><br>
                    <td>Cognome: <?php echo $cognome; ?></td><br>
                    <td>Data di nascita: <?php echo $data_nascita; ?></td><br>
                    <td>Codice fiscale: <?php echo $cod_fiscale; ?></td><br>
                    <td>Email: <?php echo $email; ?></td>
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form" method="post">
                                <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                                <input type="hidden" name="clienteid" value="<?php echo $_GET['idcliente']; ?>">
                                    </div>
                                </div>
                                
                          
                        </div>
                        <!-- /.col-lg-6 (nested) -->   
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>


<?php

    $conn = new mysqli("localhost","root","", "DB_TradingForYou");
    if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
    }
    $query = "SELECT * FROM consulente WHERE idconsulente =1";
    $result = $conn->query($query);
    while($row = $result->fetch_assoc()) {

            $nome=$row['nome'];
            $cognome=$row['cognome'];
            $email=$row['email'];
            $n_telefono=$row['n_telefono'];
        }
    $conn->close();
    

?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    Il tuo consulente
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
                    <td>Nome: <?php echo $nome; ?></td><br>
                    <td>Cognome: <?php echo $cognome; ?></td><br>
                    <td>Email: <?php echo $email; ?></td><br>
                    <td>Telefono: <?php echo $n_telefono; ?></td>
                    <div class="row">
                        <div class="col-lg-12">
                            <form role="form" method="post">
                                <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
                                <input type="hidden" name="clienteid" value="<?php echo $_GET['idcliente']; ?>">

                                </div>
                                
                            </form>
                        
                        <!-- /.col-lg-6 (nested) -->
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>

<img src="logo_size.jpg" alt="logo TFY|MyBank">
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

