
<?php

    $conn = new mysqli("localhost","root","", "DB_TradingForYou");
    if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
    }
    $query = "SELECT price_close, trade_date FROM stock_daily_values WHERE stockid =1 ORDER BY trade_date DESC LIMIT 1";
    $result = $conn->query($query);
    while($row = $result->fetch_assoc()) {

            $prezzo=$row['price_close'];
            $trade_date=$row['trade_date'];

        }
    $conn->close();
    

?>

                    <td>Prezzo: <?php echo $prezzo; ?></td><br>
                    <td>Data: <?php echo $trade_date; ?></td><br>

                   
 

