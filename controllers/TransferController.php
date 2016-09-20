<?php
class Cammino_Sps_TransferController extends Mage_Core_Controller_Front_Action {
	
	public function receiptAction() {
		$block = $this->getLayout()->createBlock('sps/transfer_receipt');
		$this->loadLayout();
		$this->analyticsTrack();
		$this->getLayout()->getBlock('root')->setTemplate('page/1column.phtml');
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
	}
	
	public function payAction() {
		$block = $this->getLayout()->createBlock('sps/transfer_pay');
		$this->getResponse()->setRedirect($block->getUrl());
	}

	function xmlAction() {
		$sps = Mage::getModel('sps/transfer');
		$orderId = $_REQUEST["numOrder"];
		echo $sps->getTransferXml($orderId);
	}

	private function analyticsTrack() {
		$session = Mage::getSingleton('checkout/session');
		$orderId = $session->getLastOrderId();
		Mage::dispatchEvent('checkout_onepage_controller_success_action', array('order_ids' => array($orderId)));
	}

}
