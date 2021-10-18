<?php
        $username = $_POST["username"];
        $password = $_POST["password"];
        $pass ="";
        $user ="";
    
    
        $conn = new mysqli("localhost","root","", "DB_TradingForYou");
        if ($conn->connect_error) {
        die("Connessione fallita: " . $conn->connect_error);
        }
        $query = "SELECT * FROM cliente WHERE email ='".$username."'";
        $result = $conn->query($query);
        while($row = $result->fetch_assoc()) {

                $pass=$row['password'];
                $user=$row['email'];
            }
        $conn->close();



    if ($username == $user && $password == $pass)
    {
        setcookie("login", "OK", time() + 3600);
        header('Location: http://localhost/TFY-MyBank/area-cliente.php');
        exit;
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
        header('Refresh: 4; URL=http://localhost/TFY-MyBank/login.html');
        exit;
    }
?>
