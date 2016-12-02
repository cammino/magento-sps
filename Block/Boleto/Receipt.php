<?php
class Cammino_Sps_Block_Boleto_Receipt extends Mage_Payment_Block_Form {
	
	private $_orderId;
	protected $_orderModel;
	
	protected function _construct() {
		$this->_orderModel = Mage::getModel("sales/order");
		$this->_orderId    = $this->setOrderId();
		
		$this->setTemplate("sps/boleto/receipt.phtml");

		$state   = 'pending_payment';
		$status  = 'pending_payment';
		$comment = 'Boleto gerado, aguardando pagamento.';

		if ($this->_orderModel->getState() == "pending") {
			$this->_orderModel->setState($state, $status, $comment, false);
			$this->_orderModel->save();
			$this->_orderModel->sendNewOrderEmail();
		}

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
		return Mage::getUrl('sps/boleto/pay', array('id' => $this->_orderId));
	}

	
}