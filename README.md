# <div class><img src="http://vdapoi.altervista.org/image.png" width="50px" align="left"></div> TFY-MyBank 

A platform that allows users to simulate buying and selling stocks and create a watchlist of their favorite stocks. The portfolio data is stored in a relational database and real-time stock data is obtained through free API calls (in JSON format then stored) from [marketstack](http://api.marketstack.com) and [Alpha Vantage](https://www.alphavantage.co/). Portfolio status visualization and access to the trading predictive alghoritms optionalities (Auto, Standard, Assisted).

## Features
- Login to the webapp
- Buy and sell stocks to build your portfolio
- Create and manage a watchlist of your favorite stocks
- Real-time stock data from trusted sources with real time graphs
- Multiple possibilities to manage your trading portfolio (Auto, Assisted, Standard)
- Secure platform with CSRF protection and user authentication

<div align="center">
 <table>
   <tr>
<td><img src="http://vdapoi.altervista.org/2.png"  /><br>
  <em>Login</em></td> 
    <td><img src="http://vdapoi.altervista.org/3.png"  /><br>
  <em>Personal area</em></td> 
      <td><img src="http://vdapoi.altervista.org/4.png" /><br>
  <em>Add stocks</em></td> 
    </tr>   <tr>
          <td><img src="http://vdapoi.altervista.org/5.png"  /><br>
  <em>Watching list</em></td> 
          <td><img src="http://vdapoi.altervista.org/6.png" /><br>
  <em>Graphs based on DataBase</em></td> 
          <td><img src="http://vdapoi.altervista.org/7.png"  /><br>
  <em>Real time data stock</em></td> 
      </tr>   <tr>
          <td><img src="http://vdapoi.altervista.org/8.png" /><br>
  <em>Standard portfolio</em></td> 
          <td><img src="http://vdapoi.altervista.org/9.png" /><br>
  <em>Manage your trading portfolio (Auto, Assisted, Standard)</em></td> 
          <td><img src="http://vdapoi.altervista.org/10.png"  /><br>
  <em>Transaction history</em></td> 
   </tr>
  </table>
</div>


## Getting Started
1. Clone the repository and set up your local environment `$ git clone https://github.com/andreramolivaz/TFY-MyBank.git`
2. Install PHP and a relational database software on your system (preferably XAMPP) 
3. Config the connection to the DB [here](https://github.com/andreramolivaz/TFY-MyBank/blob/f034528e333319b39f30222281c74cd27af8eeab/includes/connect.php)
4. Request fot the API keys for marketstack and Alpha Vantage in order to retrieve real-time stock data and then put the key [here (config file)](https://github.com/andreramolivaz/TFY-MyBank/blob/f034528e333319b39f30222281c74cd27af8eeab/includes/config.php)

## DataBase Structure

[Here](https://github.com/andreramolivaz/TFY-MyBank/blob/3fffcc85c8970ce123219074ce406883b795d0a9/DB_TradingForYou_dump.sql) you can find a dump of the DB so that you can use TFY-MyBank without getting the API key by only importing the dump on XAMPP.
<div align="center">
 <table>
   <tr>
<td><img src="http://vdapoi.altervista.org/1.png" width="500" height="350" /><br>
  <em>ER Diagram</em></td> 
   </tr>
  </table>
</div>


## Project Structure
````bash
TFY_MyBank
├── Resources
│   └── stock-market-html.zip
├── Resources.zip
├── aggiungi-azione.php
├── aggiungi-sezione.php
├── area-cliente.php
├── azione.php
├── azioni.php
├── chiudi-posizione.php
├── debugging
│   ├── curl.php
│   ├── get-name.php
│   └── new-price.php
├── dist
│   ├── css
│   │   ├── sb-admin-2.css
│   │   └── sb-admin-2.min.css
│   └── js
│       ├── sb-admin-2.js
│       └── sb-admin-2.min.js
├── elimina-sezione.php
├── grafico.php
├── includes
│   ├── config.php
│   ├── connect.php
│   ├── database-planning.txt
│   ├── footer.php
│   ├── header.php
│   ├── info.txt
│   └── navigation.php
├── js
│   └── sb-admin-2.js
├── login.html
├── login.php
├── logo_size.jpg
├── logo_size_invert.jpg
├── logout.php
├── modifica-sezione.php
├── nuovi-dati.php
├── sezione.php
├── sezioni.php
├── storico.php
├── valori-cache-azione.php
└── vendor
    ├── bootstrap
    │   ├── css
    │   │   ├── bootstrap.css
    │   │   └── bootstrap.min.css
    │   ├── fonts
    │   │   ├── glyphicons-halflings-regular.eot
    │   │   ├── glyphicons-halflings-regular.svg
    │   │   ├── glyphicons-halflings-regular.ttf
    │   │   ├── glyphicons-halflings-regular.woff
    │   │   └── glyphicons-halflings-regular.woff2
    │   └── js
    │       ├── bootstrap.js
    │       └── bootstrap.min.js
    ├── bootstrap-social
    │   ├── bootstrap-social.css
    │   ├── bootstrap-social.less
    │   └── bootstrap-social.scss
    ├── font-awesome
    │   ├── css
    │   │   ├── font-awesome.css
    │   │   ├── font-awesome.css.map
    │   │   └── font-awesome.min.css
    │   ├── fonts
    │   │   ├── FontAwesome.otf
    │   │   ├── fontawesome-webfont.eot
    │   │   ├── fontawesome-webfont.svg
    │   │   ├── fontawesome-webfont.ttf
    │   │   ├── fontawesome-webfont.woff
    │   │   └── fontawesome-webfont.woff2
    │   ├── less
    │   │   ├── animated.less
    │   │   ├── bordered-pulled.less
    │   │   ├── core.less
    │   │   ├── extras.less
    │   │   ├── fixed-width.less
    │   │   ├── font-awesome.less
    │   │   ├── icons.less
    │   │   ├── larger.less
    │   │   ├── list.less
    │   │   ├── mixins.less
    │   │   ├── path.less
    │   │   ├── rotated-flipped.less
    │   │   ├── screen-reader.less
    │   │   ├── spinning.less
    │   │   ├── stacked.less
    │   │   └── variables.less
    │   └── scss
    │       ├── _animated.scss
    │       ├── _bordered-pulled.scss
    │       ├── _core.scss
    │       ├── _extras.scss
    │       ├── _fixed-width.scss
    │       ├── _icons.scss
    │       ├── _larger.scss
    │       ├── _list.scss
    │       ├── _mixins.scss
    │       ├── _path.scss
    │       ├── _rotated-flipped.scss
    │       ├── _screen-reader.scss
    │       ├── _spinning.scss
    │       ├── _stacked.scss
    │       ├── _variables.scss
    │       └── font-awesome.scss
    └── jquery
        ├── jquery.js
        └── jquery.min.js
````
## Documentation

You can find a detailed documentation of the project in Italian [here](https://github.com/andreramolivaz/TFY-MyBank/blob/54e2e6024bed0641cc970437b04c9ee5c0493735/report_elaborato-Andre%CC%81_Ramolivaz.pdf).

An italian ppt that resume evrething is also avaiable [here](https://prezi.com/p/eendei5qgeo0/trading-for-you/).

