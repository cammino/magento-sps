<?php

class Cammino_Sps_Model_Transfer extends Cammino_Sps_Model_Sps {
	
	protected $_canAuthorize = true;
	protected $_canCapture = true;
	protected $_canCapturePartial = false;
	protected $_code = 'sps_transfer';
	protected $_formBlockType = 'sps/transfer_form';
	protected $_infoBlockType = 'sps/transfer_info';

	/**
	  * Add addiotionalData to order.
	  *
	  * @param Varien_Object
	  * @return string
	  **/
	public function assignData($data) {		
        return $this;
    }

    /**
	  * Required Method for payment redirect in checkout.
	  *
	  * @return string
	  **/
	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl('sps/transfer/receipt');
	}

	/**
	 * Return parameters transfer payment.
	 *
	 * @param $orderId int
	 * @return string
	 **/
	public function getTransferXml($orderId)
	{

		$xml = $this->getOrderXml($orderId);
		
		$transfer = array(
			'NUMEROAGENCIA' => $this->getConfigdata("agency"),
			'NUMEROCONTA'   => $this->getConfigdata("account"),
			'ASSINATURA'    => $this->getConfigdata("key")
		);

		$xml .= $this->generateXml( $transfer, 'TRANSFER_DESCRIPTION');

		return $xml;
	}
}