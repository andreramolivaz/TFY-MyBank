<?php
require_once('includes/connect.php');
$sql = "SELECT * FROM stocks";
$result = $db->prepare($sql);
$result->execute() or die(print_r($result->errorInfo(), true));
$stocks = $result->fetchAll(PDO::FETCH_ASSOC);
foreach ($stocks as $stock) {
    // We can get the number of days by counting the number of rows in db
    $dayssql = "SELECT * FROM stock_daily_values WHERE stockid=?";
    $daysresult = $db->prepare($dayssql);
    $daysres = $daysresult->execute(array($stock['id'])) or die(print_r($daysresult->errorInfo(), true));
    $dayscount = $daysresult->rowCount();
    $stocklh = $daysresult->fetchAll(PDO::FETCH_ASSOC);

    // we should get the first & last records for Start & Current Price
    $sql = "SELECT * FROM stock_daily_values WHERE stockid=? ORDER BY trade_date ASC LIMIT 1";
    $result = $db->prepare($sql);
    $result->execute(array($stock['id'])) or die(print_r($result->errorInfo(), true));
    $stockstartvals = $result->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM stock_daily_values WHERE stockid=? ORDER BY trade_date DESC LIMIT 1";
    $result = $db->prepare($sql);
    $result->execute(array($stock['id'])) or die(print_r($result->errorInfo(), true));
    $stockcurrentvals = $result->fetch(PDO::FETCH_ASSOC);

    // calculating All time low & Highs from full record set
    $pricelh = array_column($stocklh, 'price_open');
    $stocklow = $stocklh[array_search(min($pricelh), $pricelh)];
    $stockhigh = $stocklh[array_search(max($pricelh), $pricelh)];

    // Insert into cache_stock_values table
    $sql = "INSERT INTO stock_cache_values (stockid, days, startprice, startdate, currentprice, currentdate, atl_price, atl_date, ath_price, ath_date) VALUES (:stockid, :days, :startprice, :startdate, :currentprice, :currentdate, :atl_price, :atl_date, :ath_price, :ath_date)";
    $result = $db->prepare($sql);
    $values = array(':stockid'      => $stock['id'],
                    ':days'         => $dayscount,
                    ':startprice'   => $stockstartvals['price_open'],
                    ':startdate'    => $stockstartvals['trade_date'],
                    ':currentprice' => $stockcurrentvals['price_open'],
                    ':currentdate'  => $stockcurrentvals['trade_date'],
                    ':atl_price'    => $stocklow['price_open'],
                    ':atl_date'     => $stocklow['trade_date'],
                    ':ath_price'    => $stockhigh['price_open'],
                    ':ath_date'     => $stockhigh['trade_date']
                    );

    $res = $result->execute($values) or die(print_r($result->errorInfo(), true));

    if($res){
        echo $stock['id'] . " Inserito";
    }else{
        echo $stock['id'] . " Errore";
    }
}
?>
