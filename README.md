# <div class><img src="http://vdapoi.altervista.org/image.png" width="50px" align="left"></div> TFY-MyBank 

A platform that allows users to simulate buying and selling stocks and create a watchlist of their favorite stocks. The portfolio data is stored in a relational database and real-time stock data is obtained through free API calls from [marketstack](http://api.marketstack.com) and [Alpha Vantage](https://www.alphavantage.co/). Portfolio status visualization and to access the trading predictive alghoritms optionalities (Auto, Standard, Assisted)

## Features
- Login to the webapp
- Buy and sell stocks to build your portfolio
- Create and manage a watchlist of your favorite stocks
- Real-time stock data from trusted sources with some statistics
- Secure platform with CSRF protection and user authentication

<div align="center">
 <table>
   <tr>
<td><img src="http://vdapoi.altervista.org/2.png"  /><br>
  <em>singleplayer</em></td> 
    <td><img src="http://vdapoi.altervista.org/3.png"  /><br>
  <em>multiplayer(player1 vs player2)</em></td> 
      <td><img src="http://vdapoi.altervista.org/4.png" /><br>
  <em>multiplayer(player1 vs CPU)</em></td> 
          <td><img src="http://vdapoi.altervista.org/5.png"  /><br>
  <em>multiplayer(player1 vs CPU)</em></td> 
          <td><img src="http://vdapoi.altervista.org/6.png" /><br>
  <em>multiplayer(player1 vs CPU)</em></td> 
          <td><img src="http://vdapoi.altervista.org/7.png"  /><br>
  <em>multiplayer(player1 vs CPU)</em></td> 
          <td><img src="http://vdapoi.altervista.org/8.png" /><br>
  <em>multiplayer(player1 vs CPU)</em></td> 
          <td><img src="http://vdapoi.altervista.org/9.png" /><br>
  <em>multiplayer(player1 vs CPU)</em></td> 
          <td><img src="http://vdapoi.altervista.org/10.png"  /><br>
  <em>multiplayer(player1 vs CPU)</em></td> 
   </tr>
  </table>
</div>


## Getting Started
1. Clone the repository and set up your local environment `$ git clone https://github.com/andreramolivaz/TFY-MyBank.git`
2. Install PHP and a relational database on your system (I used XAMPP) 
3. Config the connection to the DB [here](https://github.com/andreramolivaz/TFY-MyBank/blob/f034528e333319b39f30222281c74cd27af8eeab/includes/connect.php)
4. Request fot the API keys for marketstack and Alpha Vantage in order to retrieve real-time stock data and put them [here (config file)](https://github.com/andreramolivaz/TFY-MyBank/blob/f034528e333319b39f30222281c74cd27af8eeab/includes/config.php)

## DataBase Structure

[Here](https://github.com/andreramolivaz/TFY-MyBank/blob/3fffcc85c8970ce123219074ce406883b795d0a9/DB_TradingForYou_dump.sql) you can find a dump of the DB so that you can use TFY-MyBank without getting the API key by only importing the dump on XAMPP.
<div align="center">
 <table>
   <tr>
<td><img src="http://vdapoi.altervista.org/1.png" width="250" height="200" /><br>
  <em>ER Diagram</em></td> 
   </tr>
  </table>
</div>


## Project Structure

    Xtetris               
    ├── main.c                   
    ├── tetris.c                  
    └── tetris.h

- `main.c` contains the main function and initial menu
- `Tetris.c` implements the game logic
- `Tetris.h` contains the declarations of functions used in Tetris.c

