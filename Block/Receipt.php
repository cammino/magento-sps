<?php
class Cammino_Sps_Block_Receipt extends Mage_Payment_Block_Form {
	
	private $_orderId;
	
	protected function _construct() {
		$session = Mage::getSingleton('checkout/session');
		$order = Mage::getModel("sales/order");
		$order->loadByIncrementId($session->getLastRealOrderId());
		$payment = $order->getPayment();
		$addata = unserialize($payment->getData("additional_data"));
		$this->_orderId = $order->getRealOrderId();
		$this->setTemplate("sps/receipt.phtml");
		
		parent::_construct();
	}
	
	public function getOrderId() {
		return $this->_orderId;
	}

	public function getUrl() {
		$sps = Mage::getModel('sps/boleto');
		$environment = $sps->getConfigdata("environment");
		$merchid = $sps->getConfigdata("merchid");
		
		if ($environment == "test") {
			return "http://mupteste.comercioeletronico.com.br/sepsBoleto/$merchid/prepara_pagto.asp?merchantid=$merchid&orderid=".$this->_orderId;
		} else {
			return "https://mup.comercioeletronico.com.br/sepsBoleto/$merchid/prepara_pagto.asp?merchantid=$merchid&orderid=".$this->_orderId;
		}
	}
	
}