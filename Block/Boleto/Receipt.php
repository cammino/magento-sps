<?php
class Cammino_Sps_Block_Boleto_Receipt extends Mage_Payment_Block_Form {
	
	private $_orderId;
	
	protected function _construct() {
		$session = Mage::getSingleton('checkout/session');
		$order = Mage::getModel("sales/order");
		$order->loadByIncrementId($session->getLastRealOrderId());
		$this->_orderId = $order->getRealOrderId();
		$this->setTemplate("sps/boleto/receipt.phtml");

		$state   = 'pending_payment';
		$status  = 'pending_payment';
		$comment = 'Boleto gerado, aguardando pagamento.';

		$order->setState($state, $status, $comment, false);
		$order->save();

		$order->sendNewOrderEmail();

		parent::_construct();
	}
	
	public function getOrderId() {
		return $this->_orderId;
	}
	
	public function getPayUrl() {
		return Mage::getUrl('sps/boleto/pay', array('id' => $this->_orderId));
	}
	
}