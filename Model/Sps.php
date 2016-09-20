<?php

class Cammino_Sps_Model_Sps extends Mage_Payment_Model_Method_Abstract {

    /**
     * load Mage_Sales_Model_Order
     * @var $_modelOrder 
     **/
    protected $_modelOrder;
    protected $_modelCustomer;
    

    /**
      * @return void
      **/
    function __construct() {
        parent::__construct();

        $this->_modelOrder    = Mage::getModel("sales/order");
        $this->_modelCustomer = Mage::getModel("customer/customer");
    }    


    /**
     * Generate default order for xml.
     *
     * @param $orderId Int - Current Order id.
     * @return string
     **/ 
    protected function getOrderXml($orderId)
    {
        
        if (!$orderId) return '';

        $this->_modelOrder->loadByIncrementId($orderId);
        $order = array(
            'orderid'    => $orderId,
            'descritivo' => "Pedido {$orderId}",
            'quantidade' => 1,
            'unidade'    => "UN",
            'valor'      => $this->getOrderTotal(),
        );

        return $this->generateXml($order, 'ORDER_DESCRIPTION');
    }

    /**
     * Generate generic xml to payments methods.
     *
     * @param $data Array - Fields of xml.
     * @param $contaner String - Name of container fields.
     * @return string
     **/
    protected function generateXml($data, $containerName)
    {
        $xml = "<BEGIN_{$containerName}>\n";

        foreach ($data as $key => $value) {
            $xml .= "<{$key}>=({$value})\n";
        }

        $xml .= "<END_{$containerName}>\n";

        return $xml;

    }

    /**
     * Generate money string to xml.
     *
     * @param $prefix String - Money Prefix.
     * @return string
     **/
    protected function getOrderTotal($prefix = '')
    {
        if ($prefix)
            return $prefix . number_format($this->_modelOrder->getTotalDue(), 2, ",", ".");
        
        return number_format($this->_modelOrder->getTotalDue(), 2, "", "");
    }

    /**
     * Get State name.
     *
     * @param $regionId Int - Id of region. 
     * @return string
     **/
    protected function getRegionCode($regionId) 
    {
        $regions = Mage::getModel('directory/region_api')->items("BR");
        $regionCode = "";
            
        for($i = 0; $i < count($regions); $i++) {
            if (strval($regions[$i]["region_id"]) == strval($regionId)) {
                $regionCode = $regions[$i]["code"];
                break;
            }
        }
        
        return $regionCode;
    }

    /**
     * Remove string special characteres.
     *
     * @param $str String - String to clear special characteres
     * @return string
     **/
    protected function clearString($str) {
        $str = str_replace("\n", " ", $str);
        $str = str_replace("=", "", $str);
        $str = str_replace("(", "", $str);
        $str = str_replace(")", "", $str);
        return utf8_decode($str);
    }
}