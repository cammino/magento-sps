<?php
class Cammino_Sps_Block_Transfer_Receipt extends Mage_Payment_Block_Form {
	
	private $_orderId;
	protected $_orderModel;
	
	protected function _construct() {
		$this->_orderModel = Mage::getModel("sales/order");
		$this->_orderId    = $this->setOrderId();

		$this->setTemplate("sps/transfer/receipt.phtml");

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
	
	public function getOrderId() {
		return $this->_orderId;
	}
	
	public function getPayUrl() {
		return Mage::getUrl('sps/transfer/pay', array('id' => $this->_orderId));
	}
}