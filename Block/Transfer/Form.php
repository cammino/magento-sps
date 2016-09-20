<?php
class Cammino_Sps_Block_Transfer_Form extends Mage_Payment_Block_Form {
	
	protected function _construct() {
		$this->setTemplate('sps/transfer/form.phtml');
		parent::_construct();
	}
	
}