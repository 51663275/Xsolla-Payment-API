<?php
/**
 *
 * Virtual Currency Protocol Implementation. Sample.
 *
 * @version 1.0
 * @author Xsolla
 */

/**
 * Config class
 */
Class XsollaConfig {

    /**
     * Parameters for database connection
     *
     * @var String
     */
    public $dbHost = '';
    public $dbUser = '';
    public $dbPassword = '';
    public $db = ''; // database name

    /**
     *
     * Table that contains the information about the payments
     * 
     * @var String
     */
    public $dbPaymentsTable = 'xsolla_billing';
    
    /**
     * 
     * Table where you store character's names or accounts
     * 
     * @var String 
     */
    public $dbCharactersTable = 'characters';

    /**
     * 
     * Secret key for your project, you can ask for it your account manager
     *
     * @var String
     */
    public $secretKey = '';

    /**
     *
     * List of allowed IP
     * 
     * @var Array
     */
    public $allowedIPs = Array ("94.103.26.178", "94.103.26.181");

}

/**
 *
 * Class that implements "Virtual Currency Protocol"
 *
 * In this sample class you can find implementation of main methods of "Virtual Currency Protocol".
 * To start script, replace parameters by your own parameters (based on your system).
 *
 * @version 1.0
 * @author Xsolla
 */
Class VirtualCurrencyProtocolImplementation
{
    /**
     *
     * Result codes
     */
    const _codeSuccess = 0;
    const _codeTemporaryError = 1;
    const _codeCancelNotFound = 2;
    const _codeIncorrectSignature = 3;
    const _codeIncorrectRequestFormat = 4;
    const _codeOtherError = 5;
    const _codePaymentCannotBeProcessed = 7;

    private $_connect;
    private $_xsollaConfig;

    public function __construct(XsollaConfig $config)
    {
        $this->_xsollaConfig = $config;
        $this->_connect = mysql_connect($this->_xsollaConfig->dbHost, $this->_xsollaConfig->dbUser, $this->_xsollaConfig->dbPassword);
        mysql_select_db($this->_xsollaConfig->db, $this->_connect);
        // calling main method
        $this->processRequest();
    }

    /**
     *
     * Checks signature when using check method
     *
     * @return Boolean
     */
    private function _checkStatusSignature()
    {
        return md5($_GET["command"].urldecode($_GET["v1"]).$this->_xsollaConfig->secretKey) === $_GET["md5"];
    }

    /**
     *
     * Checks signature when using pay method
     *
     * @return Boolean
     */
    private function _checkPaySignature()
    {
        return md5($_GET["command"].urldecode($_GET["v1"]).$_GET["id"].$this->_xsollaConfig->secretKey) === $_GET["md5"];
    }

    /**
     *
     * Checks signature when using check method
     *
     * @return Boolean
     */
    private function _checkCancelSignature()
    {
        return md5($_GET["command"].urldecode($_GET["id"]).$this->_xsollaConfig->secretKey) === $_GET["md5"];
    }

    /**
     *
     * Method for nickname check
     *
     * @throws Exception
     */
    public function processCheckRequest()
    {
        try
        {
            /**
             *
             * Checking existance of v1 - nickname, account and so on
             */
            if (!isset($_GET["v1"]))
                throw new Exception("User ID is undefined");
            /**
             *
             * Checking existance of v2, v3. If you don't support these parametres, please comment out these lines
             */

            if (!isset($_GET["v2"]))
                throw new Exception("User ID is undefined");

            if (!isset($_GET["v3"]))
                throw new Exception("User ID is undefined");

            /*
             * 
             * Checking existance of md5 param
             */
            if (!isset($_GET["md5"]))
                throw new Exception("Signature is undefined");

            /**
             *
             * Checking signature
             */
            if (!$this->_checkStatusSignature())
                throw new Exception("Incorrect signature");

            $result = $this->_check();
            
            /* Generating response */
            $this->_generateCheckResponse($result[0], $result[1]);
        }
        catch (Exception $e)
        {
            $this->_errorCheckResponse($e);
        }
    }

    /**
     *
     * Pay method
     *
     * @throws Exception
     */
    public function processPayRequest()
    {
        try
        {
            /* Neccessary checking */
            if (!isset($_GET["id"]))
                throw new Exception("Invoice is undefined");

            if (!isset($_GET["v1"]))
                throw new Exception("User ID is undefined");

            /*If you don't support these parametres, please comment out these lines*/

            if (!isset($_GET["v2"]))
                throw new Exception("User ID is undefined");

            if (!isset($_GET["v3"]))
                throw new Exception("User ID is undefined");

            if (!isset($_GET["sum"]))
                throw new Exception("Amount is undefined");

            if (!isset($_GET["md5"]))
                throw new Exception("Signature is undefined");

            if (!$this->_checkPaySignature())
                throw new Exception("Incorrect signature");

            $result = $this->_pay();
            $this->_generatePayResponse($result[0], $result[1], $result[2], $result[3], $result[4]);
        }
        catch (Exception $e)
        {
            $this->_errorPayResponse($e);
        }
    }

    /**
     *
     * Payment cancel method
     *
     * @throws Exception
     */
    public function processCancelRequest()
    {
        try
        {
            if (!isset($_GET["id"]))
                throw new Exception("Invoice is undefined");

            if (!isset($_GET["md5"]))
                throw new Exception("Signature is undefined");

            if (!$this->_checkCancelSignature())
                throw new Exception("Incorrect signature");

            $result = $this->_cancel();
            
            /* Generating response */
            $this->_generateCancelResponse($result[0], $result[1]);
        }
        catch (Exception $e)
        {
            $this->_errorCancelResponse($e);
        }
    }

    /**
     *
     * Main method
     *
     * @throws Exception
     */
    public function processRequest()
    {
        try
        {
            /* Checking the IP-address */

            if (!in_array($_SERVER["REMOTE_ADDR"], $this->_xsollaConfig->allowedIPs))
                throw new Exception ("IP address is not allowed");

            if (!isset($_GET["command"]))
                throw new Exception("Command is undefined");

            $command = $_GET["command"];

            if ($command == "check")
            {
                $this->processCheckRequest();
            }
            elseif ($command == "pay")
            {
                $this->processPayRequest();
            }
            elseif ($command == "cancel")
            {
                $this->processCancelRequest();
            }
            else
            {
                throw new Exception("Incorrect command");
            }
        }
        catch (Exception $e)
        {
            $this->_errorCheckResponse($e);
        }
    }

    /**
     * Generating response when using nickname check method
     *
     * @param Int $code
     * @param String $description
     */
    private function _generateCheckResponse($code, $description)
    {
        $xml = new SimpleXMLElement("<response></response>");

        $xml->addChild("result", $code);
        $xml->addChild("comment", $description);

        header("Content-Type: text/xml; charset=cp1251");
        echo html_entity_decode($xml->asXML(), ENT_COMPAT, 'windows-1251');
    }

    /**
     * Generating response when using pay method
     *
     * @param Int $code
     * @param String $description
     * @param Int $invoice
     * @param Int $order
     * @param Float $sum
     */
    private function _generatePayResponse($code, $description, $invoice = 0, $order = 0, $sum = 0)
    {
        $xml = new SimpleXMLElement("<response></response>");

        $xml->addChild("id", $invoice);
        $xml->addChild("id_shop", $order);
        $xml->addChild("sum", $sum);
        $xml->addChild("result", $code);
        $xml->addChild("comment", $description);

        header("Content-Type: text/xml; charset=cp1251");
        echo html_entity_decode($xml->asXML(), ENT_COMPAT, 'windows-1251');
    }

    /**
     * Generating response when using payment cancel method
     *
     * @param Int $code
     * @param String $description
     */
    private function _generateCancelResponse($code, $description)
    {
        $xml = new SimpleXMLElement("<response></response>");

        $xml->addChild("result", $code);
        $xml->addChild("comment", $description);

        header("Content-Type: text/xml; charset=cp1251");
        echo html_entity_decode($xml->asXML(), ENT_COMPAT, 'windows-1251');
    }


    private function _errorCheckResponse($e)
    {
        $this->_generateCheckResponse(self::_codePaymentCannotBeProcessed, $e->getMessage());
    }

    private function _errorPayResponse($e)
    {
        $this->_generatePayResponse(self::_codePaymentCannotBeProcessed, $e->getMessage());
    }

    private function _errorCancelResponse($e)
    {
        $this->_generateCancelResponse(self::_codePaymentCannotBeProcessed, $e->getMessage());
    }
    
    //////////////////////////////////////////////////////////////////////////
    ////// THIS SECTION IS THE ONLY PLACE WHERE YOU SHOULD WRITE CODE ////////
    //////////////////////////////////////////////////////////////////////////
    
    /* YOUR CHECK CODE HERE */
    private function _check()
    {
        // Code example

        // If you don't use v2, v3, use the string below
        //$sql = 'SELECT count(1) as cnt FROM '.$this->_xsollaConfig->dbCharactersTable.' WHERE v1 = ' . mysql_real_escape_string($_GET['v1']);
        $sql = 'SELECT count(1) as cnt FROM '.$this->_xsollaConfig->dbCharactersTable.' WHERE v1 = ' . mysql_real_escape_string($_GET['v1']) . ' AND v2 = ' . mysql_real_escape_string($_GET['v2']) . ' AND v3 = ' . mysql_real_escape_string($_GET['v3']);
        // performing query
        $result = mysql_query($sql, $this->_connect);
        // getting result
        $checked = mysql_fetch_assoc ($result);
        // if nickname exists
        if ($checked['cnt'] != 0)
            return Array (self::_codeSuccess, 'OK');
        else
            return Array (self::_codePaymentCannotBeProcessed, 'Character doesn\'t exist.');
    }
    
    /* YOUR PAY CODE HERE */
    private function _pay()
    {
        // Code example
        
        // Looking for payment with such id
        $sql = 'SELECT `id` FROM '.$this->_xsollaConfig->dbPaymentsTable.' WHERE `invoice` = ' . mysql_real_escape_string($_GET['id']);
        // performing query
        $result = mysql_query ($sql, $this->_connect);
        // getting result
        $exist = mysql_fetch_assoc ($result);
        // If there is no payment with such id, inserting it
        if (!$exist['id'])
        {
            // If you don't use v2, v3, use the string below
            //$sql = 'INSERT INTO `'.$this->_xsollaConfig->dbPaymentsTable.'` (`v1`, `amount`, `invoice`, `date_add`, `canceled`) VALUES ('. mysql_real_escape_string($_GET['v1']) . ', ' . mysql_real_escape_string($_GET['sum']) . ', ' . mysql_real_escape_string($_GET['id']) . ', NOW(), "0")';
            $sql = 'INSERT INTO `'.$this->_xsollaConfig->dbPaymentsTable.'` (`v1`, `v2`, `v3`, `amount`, `invoice`, `date_add`, `canceled`) VALUES ('. mysql_real_escape_string($_GET['v1']) . ', ' . mysql_real_escape_string($_GET['v2']) . ', ' . $_GET['v3'] . ', ' . mysql_real_escape_string($_GET['sum']) . ', ' . mysql_real_escape_string($_GET['id']) . ', NOW(), "0")';
            // performing query
            $result = mysql_query ($sql, $this->_connect);
            // if insert successful
            if ($result)
                $id_shop = mysql_insert_id ();
            else
                return Array (self::_codeOtherError, 'Could\'t insert data. See mysql errors', $_GET['id'], NULL, $_GET['sum']);
            
            return Array (self::_codeSuccess, 'OK', $_GET['id'], $id_shop, $_GET['sum']);
        }
        else
        {
            // If payment with such xsolla id exists, successful
            return Array (self::_codeSuccess, 'OK', $_GET['id'], $exist['id'], $_GET['sum']);
        }
    }
    
    /* YOUR CANCEL CODE HERE */
    private function _cancel()
    {
        // Code example
        
        // Canceling payment
        $sql = 'UPDATE `'.$this->_xsollaConfig->dbPaymentsTable.'` SET `canceled` = "1", `date_cancel` = NOW() WHERE `invoice` = '. mysql_real_escape_string($_GET['id']);
        // performing query
        $result = mysql_query($sql, $this->_connect);
        // if query successful
        if ($result)
            return Array (self::_codeSuccess, 'OK');
        else
            return Array (self::_codeCancelNotFound, 'Payment with given ID does not exist');
    }
    //////////////////////////////////////////////////////////////////////////
    //////////////////////////////// END /////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////
}

$handler = new VirtualCurrencyProtocolImplementation(new XsollaConfig());