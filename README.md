![](http://xsolla.com/img/xsolla-logo2.png)

## Xsolla Payment API ##

===

## Please follow these easy steps: ##


1. [Register](https://account.xsolla.com/index.php?a=registrationForm "Account registration") your account
2. Read our API [Virtual Currency Wiki](https://github.com/xsolla/Xsolla-Payment-API/wiki/Virtual-Currency-API-Guide "Virtual Currency API Wiki") and [Cash protocol Wiki](https://github.com/xsolla/Xsolla-Payment-API/wiki/Cash-API-Guide "Cash Protocol API Wiki") or just print it:
   * [Virtual Currency](https://github.com/xsolla/Xsolla-Payment-API/blob/master/Xsolla_Virtual_Currency_API_Guide.pdf "Virtual Currency Protocol API Guide")
   * [Cash protocol](https://github.com/xsolla/Xsolla-Payment-API/blob/master/Xsolla_Cash_API_Guide.pdf "Cash Protocol API Guide")
3. [Add a new project](https://account.xsolla.com/index.php?a=projects&ext=drawfrmnewproject "Add project") to your account
4. Read [PayBar](https://github.com/xsolla/Xsolla-Payment-API/blob/master/Xsolla_PayBar_Integration_Guide_en.pdf "PayBar Integration Guide") / [Paystation](https://github.com/xsolla/Xsolla-Payment-API/blob/master/Xsolla_PayStation_Integration_Guide.pdf "PayStation Integration Guide") guides and implement one of these tools. 
In case you would like to customize it, here is the [template files](https://github.com/xsolla/Xsolla-Payment-API/blob/master/Paystation_template.zip "Paystation template files").
5. Test and go live.


## Virtual Currency Protocol ##

Xsolla's Virtual Currency Protocol allows the exchange of real currency into virtual currency with a preset exchange rate. The Virtual Currency Protocol is an easy and accessible solution for those projects which have in-game virtual currency with a predetermined value. Users get a preset amount of virtual currency when they replenish their accounts in-game. Players can make payments from e-wallets, cash kiosks, mobile, online-banking, etc.

#### Implementing Virtual Currency Protocol ####
Implementing Xsolla's Virtual Currency Protocol is as easy as editing the included [config.php](https://github.com/xsolla/Xsolla-Payment-API/blob/master/examples/virtual_currency_protocol/inc/config.php "config.php") to include your database information and secret key. Simply extend the included VirtualCurrency class found in [VirtualCurrency.php](https://github.com/xsolla/Xsolla-Payment-API/blob/master/examples/virtual_currency_protocol/inc/virtual_currency_protocol.php "VirtualCurrency.php") and implement the following methods for database handling:

* **setupDB()**
    * this method is responsible for configuring a connection to your database by instantiating a PDO object
* **userExists($user)**
    * this method checks the database for a user and returns a boolean true if found or false otherwise
* **invoiceExists($invoiceID)**
    * this method checks the database for the existence of an invoice and returns boolean true if found or false otherwise
* **newInvoice($invoiceID, $userID, $sum)**
    * this method inserts a new invoice into your database 
* **cancelInvoice($invoiceID)**
    * this method deletes an invoice from your database

if you have questions about how to implement these methods please see the included [example.php](https://github.com/xsolla/Xsolla-Payment-API/blob/master/examples/virtual_currency_protocol/example.php "example.php") which utilizes the database structure found in [example.sql](https://github.com/xsolla/Xsolla-Payment-API/blob/master/examples/virtual_currency_protocol/example.sql "example.sql").


## Cash Protocol ##
Xsolla's Cash Protocol enables game projects to sell packs of virtual goods and services. When using this protocol, an order is made on the side of the game project. 


*For additional information about protocols, please visit [http://xsolla.com/docs/section/protocols](http://xsolla.com/docs/section/protocols "More about protocols")*

## Additional resources ##
*If you need any help please [contact us](mailto: a.menshikov@xsolla.com "Integration manager").*
*If you found an issue or need to create new API, please add your request [here](https://github.com/xsolla/Xsolla-Payment-API/issues)*

**-Xsolla Team** 
[![githalytics.com alpha](https://cruel-carlota.pagodabox.com/83459fc49878adb201efdb4ec58a5f92 "githalytics.com")](http://githalytics.com/xsolla/Xsolla-Payment-API)