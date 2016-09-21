<?php
class Cammino_Sps_Block_Message_Sps extends Mage_Payment_Block_Form {
	
	protected $_orderModel;
	protected $orderId;
	protected $paymentMethod;

	protected function _construct() {	
		$this->_orderModel   = Mage::getModel("sales/order");
		$this->orderId       = $this->setOrderId();
		$this->paymentMethod = $this->getOrderPayment()->getMethod();

		parent::_construct();
	}


	protected function setOrderId()
	{
		$this->_orderModel->loadByIncrementId($_REQUEST["OrderId"]);

		return $this->_orderModel->getRealOrderId();
	}

	public function getOrderId()
	{
		return $this->orderId;
	}

	protected function getOrderPayment()
	{
		return $this->_orderModel->getPayment();
	}

	public function redirectToUrl($url, $error = false)
	{	
		if ($error)
			Mage::getSingleton('core/session')->addError($_REQUEST['ErrorDesc']);

		return Mage::getUrl($url, array('id' => $this->orderId));
	}

	public function getSpsMethod()
	{
		$methods = array(
			'sps_boleto'   => 'boleto',
			'sps_transfer' => 'transfer'
		);
		return $methods[$this->paymentMethod];
	}
}