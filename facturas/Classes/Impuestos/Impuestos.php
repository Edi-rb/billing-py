<?php 
namespace Classes\Impuestos;
/**
* 
*/




class Impuestos 
{
	protected $Impuesto;
	protected $TipoFactor;
	protected $TasaOCuota;
	protected $Importe;
	protected $nodo;
	protected $traslados;

	function __construct($xml, $impuestos)
	{
		if ($impuestos['Tasa']['Importe'] > 0) {
			$this->Importe = $impuestos['Tasa']['Importe'];
			$this->Impuesto = $impuestos['Tasa']['Impuesto'];
			$this->TipoFactor = $impuestos['Tasa']['TipoFactor'];
			$this->TasaOCuota = $impuestos['Tasa']['TasaOCuota'];
			$this->crearNodoImpuestos($xml);
		}

	}

	public function crearNodoImpuestos($xml)
	{
		if (!$this->nodo) {
			$comprobante = $xml->getElementsByTagName('cfdi:Comprobante')->item(0);
			$this->nodo = $xml->createElement('cfdi:Impuestos');
			
			$domAttribute = $xml->createAttribute('TotalImpuestosTrasladados');
			$domAttribute->value = number_format($this->Importe, 2, '.', '');
			$this->nodo->appendChild($domAttribute);

			$comprobante->appendChild($this->nodo);
		}
		$this->traslados = $xml->createElement('cfdi:Traslados');
		$traslado = $xml->createElement('cfdi:Traslado');

		$domAttribute = $xml->createAttribute('Impuesto');
		$domAttribute->value = $this->Impuesto;
		$traslado->appendChild($domAttribute);

		$domAttribute = $xml->createAttribute('TipoFactor');
		$domAttribute->value = $this->TipoFactor;
		$traslado->appendChild($domAttribute);

		$domAttribute = $xml->createAttribute('TasaOCuota');
		$domAttribute->value = $this->TasaOCuota;
		$traslado->appendChild($domAttribute);

		$domAttribute = $xml->createAttribute('Importe');
		$domAttribute->value = number_format($this->Importe, 2, '.', '');
		$traslado->appendChild($domAttribute);

		$this->traslados->appendChild($traslado);
		$this->nodo->appendChild($this->traslados);
		$comprobante->appendChild($this->nodo);

	}


}
 ?>