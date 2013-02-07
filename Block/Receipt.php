<?php
class Cammino_Sps_Block_Receipt extends Mage_Payment_Block_Form {
	
	private $_orderId;
	
	protected function _construct() {
		$session = Mage::getSingleton('checkout/session');
		$order = Mage::getModel("sales/order");
		$order->loadByIncrementId($session->getLastRealOrderId());
		$this->_orderId = $order->getRealOrderId();
		$this->setTemplate("sps/receipt.phtml");
		parent::_construct();
	}
	
	public function getOrderId() {
		return $this->_orderId;
	}
	
	public function getPayUrl() {
		return Mage::getUrl('sps/boleto/pay', array('id' => $this->_orderId));
	}
	
}