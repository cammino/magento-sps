<?php
class Cammino_Sps_MessageController extends Mage_Core_Controller_Front_Action {
	
	protected $block;

	protected function _construct() {
		$this->block = $this->getLayout()->createBlock('sps/message_sps');

		parent::_construct();
	}

	public function errorAction()
	{
		$this->redirectToUrl(true);
	}

	public function successAction()
	{
		$this->redirectToUrl();
	}


	protected function redirectToUrl($error = false)
	{
		$method = $this->block->getSpsMethod();

		$url = $this->block->redirectToUrl("sps/{$method}/receipt", $error);
		$this->getResponse()->setRedirect($url);
	}
}