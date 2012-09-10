<?php
class Cammino_Sps_Block_Form extends Mage_Payment_Block_Form {
	
	protected function _construct() {
		$this->setTemplate('sps/form.phtml');
		parent::_construct();
	}
	
}