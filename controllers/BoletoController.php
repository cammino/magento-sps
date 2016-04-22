<?php
class Cammino_Sps_BoletoController extends Mage_Core_Controller_Front_Action {
	
	public function receiptAction() {
		$block = $this->getLayout()->createBlock('sps/receipt');
		$this->loadLayout();
		$this->analyticsTrack();
		$this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
	}
	
	public function payAction() {
		$block = $this->getLayout()->createBlock('sps/pay');
		$this->getResponse()->setRedirect($block->getExternalUrl());
	}

	function xmlAction() {
		$sps = Mage::getModel('sps/boleto');
		$orderId = $_REQUEST["numOrder"];
		echo $sps->generateXml($orderId);
	}

	private function analyticsTrack() {
		$session = Mage::getSingleton('checkout/session');
		$orderId = $session->getLastOrderId();
		Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($orderId)));
	}

}
