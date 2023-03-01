<?php
	namespace Classes;
	/**
	* 
	*/
	class Relacionados {

		protected $refUUID;
		protected $tipoRelacion;
		
		function __construct($xml,$uuid,$tipoRelacion) {
			$this->refUUID = $uuid;
			$this->tipoRelacion = $tipoRelacion;
			$this->crearNodoRelacionados($xml);
		}

		public function crearNodoRelacionados ($xml) {
			$comprobante = $xml->getElementsByTagName('cfdi:Comprobante')->item(0);
			$relacionados = $xml->createElement('cfdi:CfdiRelacionados');
			$domAttribute = $xml->createAttribute('TipoRelacion');
			$domAttribute->value = $this->tipoRelacion;
			$relacionados->appendChild($domAttribute);

			$comprobante->appendChild($relacionados);

			$relacionado = $xml->createElement('cfdi:CfdiRelacionado');
			$domAttribute = $xml->createAttribute('UUID');
			$domAttribute->value = $this->refUUID;
			$relacionado->appendChild($domAttribute);

			$relacionados->appendChild($relacionado);

			$comprobante->appendChild($relacionados);
		}
	}
?>