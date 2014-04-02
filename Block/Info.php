<?php
class Cammino_Sps_Block_Info extends Mage_Payment_Block_Info {
	
    protected function _construct()
    {
		parent::_construct();
    }

	protected function _beforeToHtml()
	{
		$this->_prepareInfo();
		return parent::_beforeToHtml();
	}

	public function getOrder() {
		return $this->getInfo()->getOrder();
	}
	
	public function getOrderId() {
		return $this->getOrder()->getRealOrderId();
	}

	public function getPayUrl() {
		return Mage::getUrl('sps/boleto/pay', array('id' => $this->getOrderId()));
	}

	protected function _prepareInfo()
	{
		$order = $this->getOrder();

		$paymentMethod = ' (<a href="' . $this->getPayUrl() . '" onclick="this.target=\'_blank\'">Emitir Boleto</a>)';

		$this->addData(array(
			'show_paylink' => (($order->getState() == Mage_Sales_Model_Order::STATE_NEW) || ($order->getState() == Mage_Sales_Model_Order::STATE_PENDING_PAYMENT) || ($order->getState() == Mage_Sales_Model_Order::STATE_HOLDED)),
			'pay_url' => $this->getPayUrl(),
			'payment_method' => $paymentMethod,
		));
	}
}