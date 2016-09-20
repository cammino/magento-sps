<?php
class Cammino_Sps_Block_Transfer_Info extends Mage_Payment_Block_Info {
	
    protected $_paymentSpecificInformation = null;

	protected function _construct() {
		parent::_construct();
		$this->setTemplate('payment/info/sps.phtml');
	}

	public function getOrder() {
		return $this->getInfo()->getOrder();
	}
	
	public function getOrderId() {
		return $this->getOrder()->getRealOrderId();
	}

	public function getPayUrl() {
		return Mage::getUrl('sps/transfer/pay', array('id' => $this->getOrderId()));
	}

}