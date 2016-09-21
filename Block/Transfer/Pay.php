<?php
class Cammino_Sps_Block_Transfer_Pay extends Mage_Payment_Block_Form {
	
	private $_orderId;
	protected $_orderModel;
	
	protected function _construct() {
		$this->_orderModel = Mage::getModel("sales/order");
		$this->_orderId    = $this->setOrderId();

		$this->setTemplate("sps/transfer/pay.phtml");

		parent::_construct();
	}

	protected function setOrderId()
	{

		if ($this->getRequest()->getParam("id")) {
			$id = $this->getRequest()->getParam("id");
		} else {
			$session = Mage::getSingleton('checkout/session');
			$id      = $session->getLastRealOrderId();
		}

		$this->_orderModel->loadByIncrementId($id);
		
		return $this->_orderModel->getRealOrderId();
	}

	public function getUrl() {
		$sps         = Mage::getModel('sps/transfer');
		$environment = $sps->getConfigdata("environment");
		$merchid     = $sps->getConfigdata("merchid");
		
		if ($environment == "test") {
			return "http://mupteste.comercioeletronico.com.br/sepsTransfer/$merchid/prepara_pagto.asp?merchantid=$merchid&orderid=".$this->_orderId;
		} else {
			return "https://mup.comercioeletronico.com.br/sepsTransfer/$merchid/prepara_pagto.asp?merchantid=$merchid&orderid=".$this->_orderId;
		}
	}
}