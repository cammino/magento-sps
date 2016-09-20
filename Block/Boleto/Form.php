<?php
class Cammino_Sps_Block_Boleto_Form extends Mage_Payment_Block_Form {
	
	protected function _construct() {
		$this->setTemplate('sps/boleto/form.phtml');
		parent::_construct();
	}
	
}