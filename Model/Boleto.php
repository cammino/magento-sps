<?php
class Cammino_Sps_Model_Boleto extends Mage_Payment_Model_Method_Abstract {
	
	protected $_canAuthorize = true;
	protected $_canCapture = true;
	protected $_code = 'sps_boleto';
	protected $_formBlockType = 'sps/form';
	protected $_infoBlockType = 'sps/info';

    public function assignData($data) {
		//$addata = new Varien_Object;	
		//$info = $this->getInfoInstance();
		//$info->setAdditionalData(serialize($addata));
		
        return $this;
    }
    
	public function getOrderPlaceRedirectUrl() {
		# return Mage::getUrl('sps/boleto/receipt', array('_secure' => false));
		return Mage::getUrl('sps/boleto/receipt');
	}
	
	public function generateXml($orderId) {
		
		$order = Mage::getModel("sales/order");
		$order->loadByIncrementId($orderId);
		$orderData = $order->getData();
		$orderTotal = $order->getTotalDue();
		$payment = $order->getPayment();
		$addata = unserialize($payment->getData("additional_data"));
		
		$customer = Mage::getModel("customer/customer");
		$customer->load($orderData['customer_id']);
		$billingAddress = $order->getBillingAddress();
		
		$expiresDays = 5;
		$expiresAt = strtotime("+$expiresDays day", strtotime($orderData["created_at"]));
		
		$orderCode = "$orderId";
			
		$merchid = $this->getConfigdata("merchid");
		$instructions1 = $this->getConfigdata("instructions1");
		$instructions2 = $this->getConfigdata("instructions2");
		$instructions3 = $this->getConfigdata("instructions3");
		$instructions4 = $this->getConfigdata("instructions4");
		$instructions5 = $this->getConfigdata("instructions5");
		$instructions6 = $this->getConfigdata("instructions6");
		$instructions7 = $this->getConfigdata("instructions7");
		$instructions8 = $this->getConfigdata("instructions8");
		$instructions9 = $this->getConfigdata("instructions9");
		$instructions10 = $this->getConfigdata("instructions10");
		$receiver = utf8_decode($this->getConfigdata("receiver"));
		$bank = $this->getConfigdata("bank");
		$agency = $this->getConfigdata("agency");
		$account = $this->getConfigdata("account");
		$key = $this->getConfigdata("key");
		$shoppingId = $this->getConfigdata("shopping_id");
		
		$xml  = "<BEGIN_ORDER_DESCRIPTION>\n";
		$xml .= "<orderid>=($orderCode)\n";
		$xml .= "<descritivo>=(Teste)\n";
		// $xml .= "<descritivo>=(Pedido $orderId)\n";
		$xml .= "<quantidade>=(1)\n";
		$xml .= "<unidade>=(UN)\n";
		$xml .= "<valor>=(".number_format($orderTotal, 2, "", "").")\n";
		$xml .= "<END_ORDER_DESCRIPTION>\n";
		$xml .= "<BEGIN_BOLETO_DESCRIPTION>\n";
		$xml .= "<CEDENTE>=($receiver)\n";
		$xml .= "<BANCO>=($bank)\n";
		$xml .= "<NUMEROAGENCIA>=($agency)\n";
		$xml .= "<NUMEROCONTA>=($account)\n";
		$xml .= "<ASSINATURA>=($key)\n";
		$xml .= "<DATAEMISSAO>=(".date("d/m/Y").")\n";
		$xml .= "<DATAPROCESSAMENTO>=(".date("d/m/Y").")\n";
		$xml .= "<DATAVENCIMENTO>=(".date("d/m/Y", $expiresAt).")\n";
		$xml .= "<NOMESACADO>=(".utf8_decode($customer->firstname)." ".utf8_decode($customer->lastname).")\n";
		$xml .= "<ENDERECOSACADO>=(". utf8_decode(str_replace("\n", ", ", $billingAddress->street)) .")\n";
		$xml .= "<CIDADESACADO>=(". utf8_decode($billingAddress->city) .")\n";
		$xml .= "<UFSACADO>=(".$this->getRegionCode($billingAddress->region_id).")\n";
		$xml .= "<CEPSACADO>=(".preg_replace('/[^A-Za-z0-9]/', '', $billingAddress->postcode).")\n";
		$xml .= "<CPFSACADO>=(".preg_replace('/[^A-Za-z0-9]/', '', $customer->taxvat).")\n";
		$xml .= "<NUMEROPEDIDO>=($orderCode)\n";
		$xml .= "<VALORDOCUMENTOFORMATADO>=(R$".number_format($orderTotal, 2, ",", ".").")\n";
		$xml .= "<SHOPPINGID>=($shoppingId)\n";
		$xml .= "<NUMDOC>=($orderCode)\n";
		$xml .= "<CARTEIRA>=(25)\n";
	//	$xml .= "<ANONOSSONUMERO>=(97)\n";
	//	$xml .= "<CIP>=(865)\n";
		$xml .= "<INSTRUCAO1>=($instructions1)\n";
		$xml .= "<INSTRUCAO2>=($instructions2)\n";
		$xml .= "<INSTRUCAO3>=($instructions3)\n";
		$xml .= "<INSTRUCAO4>=($instructions4)\n";
		$xml .= "<INSTRUCAO5>=($instructions5)\n";
		$xml .= "<INSTRUCAO6>=($instructions6)\n";
		$xml .= "<INSTRUCAO7>=($instructions7)\n";
		$xml .= "<INSTRUCAO8>=($instructions8)\n";
		$xml .= "<INSTRUCAO9>=($instructions9)\n";
		$xml .= "<INSTRUCAO10>=($instructions10)\n";
		$xml .= "<END_BOLETO_DESCRIPTION>";
		
		return $xml;
	}
	
	public function getRegionCode($regionId) {
		$regions = Mage::getModel('directory/region_api')->items("BR");
		$regionCode = "";
			
		for($i = 0; $i < count($regions); $i++) {
			if (strval($regions[$i]["region_id"]) == strval($regionId)) {
				$regionCode = $regions[$i]["code"];
				break;
			}
		}
		
		return $regionCode;
	}
}