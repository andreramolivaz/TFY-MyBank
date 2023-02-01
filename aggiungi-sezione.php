<?php
    if ($_COOKIE["login"] == "OK")
    {
?>
<?php
session_start();
require_once('includes/connect.php');
if(isset($_POST) & !empty($_POST)){
    //print_r($_POST);
    // PHP Form Validations
    if(empty($_POST['name'])){ $errors[] = "Il nome è necessario!"; }

    // CSRF Token Validation
    if(isset($_POST['csrf_token'])){
        if($_POST['csrf_token'] === $_SESSION['csrf_token']){
        }else{
            $errors[] = "Problema con la verifica del CSRF Token";
        }
    }else{
        $errors[] = "Problema con la validazione del CSRF Token";
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
        $sql = "INSERT INTO portafoglio (nome) VALUES (:name)";
        $result = $db->prepare($sql);
        $values = array(':name'      => $_POST['name']
                        );
        $res = $result->execute($values) or die(print_r($result->errorInfo(), true));
        if($res){
            $messages[] = "Sezione aggiunta correttamente";
        }else{
            $errors[] = "Errore nell'inserimento";
        }
    }
}

$token = md5(uniqid(rand(), TRUE));
$_SESSION['csrf_token'] = $token;
$_SESSION['csrf_token_time'] = time();

include('includes/header.php');
include('includes/navigation.php');
?>
<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Aggiungi sezione</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                Nuova sezione qui...
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
                                        <label>Nome sezione</label>
                                        <input class="form-control" name="name" placeholder="Enter Watchlist Name">
                                    </div>
                                </div>
                                <input type="submit" class="btn btn-primary" value="Submit" />
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
