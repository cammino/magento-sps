<?php
class Cammino_Sps_Model_Source_Environment {
	
	public function toOptionArray() {
		return array(
			array(
				"value" => "production",
				"label" => " Produção"
			),
			array(
				"value" => "test",
				"label" => " Testes"
			),
		);
	}
}