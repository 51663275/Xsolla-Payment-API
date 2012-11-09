![](http://xsolla.com/img/xsolla-logo2.png)
Xsolla Payment API in Chinese
===

## 請遵循這些簡單的步驟： ##


1. [註冊](https://account.xsolla.com/index.php?a=registrationForm "帳號註冊") 您的帳戶
2. 請閱讀我們的API
   * [Virtual Currency](https://github.com/xsolla/Xsolla-Payment-API/blob/master/Xsolla_Virtual_Currency_API_Guide.pdf "Virtual Currency Protocol API Guide")
   * [Cash protocol](https://github.com/xsolla/Xsolla-Payment-API/blob/master/Xsolla_Cash_API_Guide.pdf "Cash Protocol API Guide")
3. [添加一個新的項目](https://account.xsolla.com/index.php?a=projects&ext=drawfrmnewproject "添加項目") 您的帳戶
4. 閱讀 [PayBar](https://github.com/xsolla/Xsolla-Payment-API/blob/master/Xsolla_PayBar_Integration_Guide_en.pdf "PayBar Integration Guide") / [Paystation](https://github.com/xsolla/Xsolla-Payment-API/blob/master/Xsolla_PayStation_Integration_Guide.pdf "PayStation Integration Guide") 導遊和實施這些工具之. 如果你想自定義這裡是 [模板文件](https://github.com/xsolla/Xsolla-Payment-API/blob/master/Paystation_template.zip "Paystation template files").
5. 測試和去住.


## Virtual Currency Protocol ##

Xsolla's Virtual Currency Protocol 允許交換到虛擬貨幣的真實貨幣與預先設定的匯率。虛擬貨幣協議是一個容易和方便的解決方案，與一個預定值的那些項目，有在遊戲中的虛擬貨幣。用戶預設的金額補充自己的帳戶時，他們在遊戲中的虛擬貨幣。玩家可以從電子錢包，現金亭，手機，網上銀行等支付

#### 實施 Virtual Currency Protocol ####
實施 Xsolla's Virtual Currency Protocol 是那麼容易，因為編輯包括 [config.php](https://github.com/xsolla/Xsolla-Payment-API/blob/master/examples/virtual_currency_protocol/inc/config.php "config.php") 包括數據庫信息和秘密密鑰。簡單的擴展包括VirtualCurrency類 [VirtualCurrency.php](https://github.com/xsolla/Xsolla-Payment-API/blob/master/examples/virtual_currency_protocol/inc/virtual_currency_protocol.php "VirtualCurrency.php") 數據庫處理實現以下方法：

* **setupDB()**
    * 這種方法是負責配置連接到您的數據庫實例化一個PDO對象
* **userExists($user)**
    * 這個方法檢查一個用戶的數據庫，並返回一個布爾值，如果找到，否則返回
* **invoiceExists($invoiceID)**
    * 這個方法檢查數據庫中存在的發票，並返回TRUE，如果找到，否則返回false
* **newInvoice($invoiceID, $userID, $sum)**
    * 這種方法插入一個新的發票到你的數據庫
* **cancelInvoice($invoiceID)**
    * 這種方法從數據庫中刪除發票

如果您有任何疑問，有關如何實現這些方法，請參閱隨附的 [example.php](https://github.com/xsolla/Xsolla-Payment-API/blob/master/examples/virtual_currency_protocol/example.php "example.php") 它利用了數據庫結構中找到 [example.sql](https://github.com/xsolla/Xsolla-Payment-API/blob/master/examples/virtual_currency_protocol/example.sql "example.sql").


## Cash Protocol ##
Xsolla's Cash Protocol 使遊戲項目，以出售虛擬商品和服務包。當使用此協議，作出的命令上側的遊戲項目。 


*有關協議的更多信息，請訪問 [http://xsolla.com/docs/section/protocols](http://xsolla.com/docs/section/protocols "More about protocols")*

## 其他資源 ##
*如果你需要任何幫助，請 [聯繫我們](mailto: a.menshikov@xsolla.com "Integration manager").*

**-Xsolla Team** 