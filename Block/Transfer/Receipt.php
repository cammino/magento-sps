<?php
class Cammino_Sps_Block_Transfer_Receipt extends Mage_Payment_Block_Form {
	
	private $_orderId;
	
	protected function _construct() {
		$session = Mage::getSingleton('checkout/session');
		$order   = Mage::getModel("sales/order");
		$order->loadByIncrementId($session->getLastRealOrderId());
		$this->_orderId = $order->getRealOrderId();
		$this->setTemplate("sps/transfer/receipt.phtml");

		parent::_construct();
	}
	
	public function getOrderId() {
		return $this->_orderId;
	}
	
	public function getPayUrl() {
		return Mage::getUrl('sps/transfer/pay', array('id' => $this->_orderId));
	}
	
}