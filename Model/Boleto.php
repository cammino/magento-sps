<?php
class Cammino_Sps_Model_Boleto extends Cammino_Sps_Model_Sps {
	
	protected $_canAuthorize      = true;
	protected $_canCapture        = true;
	protected $_canCapturePartial = false;
	protected $_code              = 'sps_boleto';
	protected $_formBlockType     = 'sps/boleto_form';
	protected $_infoBlockType     = 'sps/boleto_info';
	protected $customer;

	/**
	  * Add addiotionalData to order.
	  *
	  * @param Varien_Object
	  * @return string
	  **/ 
    public function assignData($data) 
    {
		// $addata = new Varien_Object;	
		// $info = $this->getInfoInstance();
		// $info->setAdditionalData(serialize($addata));
		
        return $this;
    }

    /**
	  * Return object with data customer.
	  *
	  * @return object
	  **/
    protected function getCustomer()
    {
    	return $this->_modelCustomer->load($this->_modelOrder->getCustomerId());
    }
    
    /**
	  * Required Method for payment redirect in checkout.
	  *
	  * @return string
	  **/ 
	public function getOrderPlaceRedirectUrl() 
	{
		// return Mage::getUrl('sps/boleto/receipt', array('_secure' => false));
		return Mage::getUrl('sps/boleto/receipt');
	}
	
	/**
	 * Return parameters billet payment.
	 *
	 * @param $orderId int
	 * @return string
	 **/
	public function getBilletXml($orderId) 
	{
		$xml       = $this->getOrderXml($orderId);
		$orderData = $this->getOrderdata();
		$address   = $this->getCustomerAddress();
		$boleto    = array(
			'CEDENTE' 				  => $this->clearString($this->getConfigdata("receiver")),
			'BANCO' 				  => $this->getConfigdata("bank"),
			'NUMEROAGENCIA' 		  => $this->getConfigdata("agency"),
			'NUMEROCONTA'   		  => $this->getConfigdata("account"),
			'ASSINATURA' 			  => $this->getConfigdata("key"),
			'DATAEMISSAO' 			  => date("d/m/Y"),
			'DATAPROCESSAMENTO' 	  => date("d/m/Y"),
			'DATAVENCIMENTO' 		  => date("d/m/Y", $this->getExpireDays($orderData)),
			'NOMESACADO' 			  => $this->getCustomerName(),
			'ENDERECOSACADO' 		  => $address['street'],
			'CIDADESACADO' 			  => $address['city'],
			'UFSACADO' 				  => $address['state'],
			'CEPSACADO' 			  => $address['zipcode'],
			'CPFSACADO' 			  => preg_replace('/[^A-Za-z0-9]/', '', $this->getCustomer()->taxvat),
			'NUMEROPEDIDO' 			  => substr(strval($orderId), -9),
			'VALORDOCUMENTOFORMATADO' => $this->getOrderTotal('R$'),
			'SHOPPINGID' 			  => $this->getConfigdata("shopping_id"),
			'NUMDOC'   				  => substr(strval($orderId), -9),
			'CARTEIRA' 				  => strval($this->getConfigdata("wallet")) == "" ? "25" : strval($this->getConfigdata("wallet")),
			// 'ANONOSSONUMERO'          => 97,
			// 'CIP'                     => 865
		);

		for ($i=1; $i <= 10; $i++) {
			$boleto["INSTRUCAO{$i}"] = $this->clearString($this->getConfigdata("instructions{$i}"));
		}

		$xml .= $this->generateXml($boleto, 'BOLETO_DESCRIPTION');

		return $xml;
	}

	/**
	 * This method returns timestamp of expired day
	 *
	 * @param $orderData array
	 * @return string
	 **/
	protected function getExpireDays($orderData)
	{
		$expiresDays = !$this->getConfigdata("expires_days") ? 5 : $this->getConfigdata("expires_days");
		
		return strtotime("+$expiresDays day", strtotime($orderData["created_at"]));
	}

	/**
	 * Returns order data from current order.
	 *
	 * @return string
	 **/
	protected function getOrderdata()
	{
		return $this->_modelOrder->getData();
	}

	/**
	 * This method returns the customer first name concatenated with last name
	 *
	 * @return string
	 **/
	protected function getCustomerName()
	{
		return $this->clearString($this->getCustomer()->firstname . " " . $this->getCustomer()->lastname);
	}

	/**
	 * This method returns the address fields.
	 *
	 * @return array
	 **/
	public function getCustomerAddress()
	{
		$billingAddress = $this->_modelOrder->getBillingAddress();

		$address = array(
			'street'  => $this->clearString($billingAddress->street),
			'city'    => $this->clearString($billingAddress->city),
			'state'   => $this->getRegionCode($billingAddress->region_id),
			'zipcode' => preg_replace('/[^A-Za-z0-9]/', '', $billingAddress->postcode)
		);

		return $address;
	}
}