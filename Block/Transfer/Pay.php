<?php
class Cammino_Sps_Block_Transfer_Pay extends Mage_Payment_Block_Form {
	
	private $_orderId;
	
	protected function _construct() {
		$id = $this->getRequest()->getParam("id");
		$order = Mage::getModel("sales/order");
		$order->loadByIncrementId($id);
		$payment = $order->getPayment();
		$addata = unserialize($payment->getData("additional_data"));
		$this->_orderId = $order->getRealOrderId();
		$this->setTemplate("sps/transfer/pay.phtml");

		parent::_construct();

	}
	
	public function getUrl() {
		$sps = Mage::getModel('sps/transfer');
		$environment = $sps->getConfigdata("environment");
		$merchid = $sps->getConfigdata("merchid");
		
		if ($environment == "test") {
			return "http://mupteste.comercioeletronico.com.br/sepsTransfer/$merchid/prepara_pagto.asp?merchantid=$merchid&orderid=".$this->_orderId;
		} else {
			return "https://mup.comercioeletronico.com.br/sepsTransfer/$merchid/prepara_pagto.asp?merchantid=$merchid&orderid=".$this->_orderId;
		}
	}
	
}