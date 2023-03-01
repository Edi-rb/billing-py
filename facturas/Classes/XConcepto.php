<?php 
namespace Classes\Concepto;

/**
*  83111600		000REC , 00REC  recargas de tiempo aire  códido 
*  43191600   	SAT.ventas.codigo < 15  Accesorios
*  43191500  	SAT.ventas.codigo >= 15  Equipos  
*
*/
class Concepto 
{
	protected $ClaveProdServ;
	protected $Cantidad;
	protected $ClaveUnidad = 'H87';
	protected $Unidad = 'PIEZA';
	protected $Descripcion;
	protected $ValorUnitario;
	protected $Importe;
	protected $ImpImpuesto;
	protected $imp = '002';
	protected $impuesto = array('001' => '0.160000', '002' => '0.160000', '003' => '0.160000');
	protected $TipoFactor = 'Tasa';

	function __construct($xml,$concepto)
	{
		$this->setClaveProdServ($concepto->codigo);
		$this->setCantidad($concepto->cantidad);
		$this->setDescripcion($concepto->descripcion);
		$concepto->precioun = $concepto->precioun / 1.16;
		$this->setValorUnitario(number_format($concepto->precioun, 2, '.', ''));
		$this->setImporte(number_format($concepto->precioun * $this->Cantidad, 2, '.', ''));
		$concepto->ClaveProdServ = $this->ClaveProdServ;
		$concepto->ClaveUnidad = $this->ClaveUnidad;
		$concepto->Unidad = $this->Unidad;
		$concepto->Descuento = 0;

		$this->crearNodoConcepto($xml);
	}

	public function getImpImpuesto()
	{
		return $this->ImpImpuesto;;
	}

	public function getTipoFactor()
	{
		return $this->TipoFactor;
	}

	public function setClaveProdServ($ClaveProdServ)
	{
		if ($ClaveProdServ != '') {
			if ($ClaveProdServ == '00REC' || $ClaveProdServ == '000REC' || $ClaveProdServ == '000M') {
				$this->ClaveProdServ = '83111603';
				$this->ClaveUnidad = 'E48';
				$this->Unidad = 'UNIDAD DE SERVICIO';
			} else if (strlen ($ClaveProdServ) < 15) {
				$this->ClaveProdServ = '43191500';
			} else if (strlen ($ClaveProdServ) == 15) {
				$this->ClaveProdServ = '43191501';
			} else
				$this->ClaveProdServ = '44111618';
		}
	}

	public function getImporte()
	{
		return $this->Importe;
	}

	public function setCantidad($Cantidad)
	{
		if ($Cantidad != '') {
			$this->Cantidad = $Cantidad;
		}
	}

	public function setClaveUnidad($ClaveUnidad)
	{
		if ($ClaveUnidad != '') {
			$this->ClaveUnidad = $ClaveUnidad;
		}
	}

	public function setUnidad($Unidad)
	{
		if ($Unidad != '') {
			$this->Unidad = $Unidad;
		}
	}

	public function setDescripcion($Descripcion)
	{
		if ($Descripcion != '') {
			$this->Descripcion = html_entity_decode( $Descripcion );
		}
	}

	public function setValorUnitario($ValorUnitario)
	{
		if ($ValorUnitario != '') {
			$this->ValorUnitario = $ValorUnitario;
		}
	}

	public function setImporte($Importe)
	{
		if ($Importe != '') {
			$this->Importe = $Importe;
		}
	}


	public function recolectarImpuesto()
	{
		return $this->ImpImpuesto > 0;

	}

	public function crearNodoConcepto($xml)
	{
		$conceptos = $xml->getElementsByTagName('cfdi:Conceptos')->item(0);

		$concepto = $xml->createElement('cfdi:Concepto');

		$domAttribute = $xml->createAttribute('ClaveProdServ');
		$domAttribute->value = $this->ClaveProdServ;
		$concepto->appendChild($domAttribute);

		$domAttribute = $xml->createAttribute('ClaveUnidad');
		$domAttribute->value = $this->ClaveUnidad;
		$concepto->appendChild($domAttribute);

		
		$domAttribute = $xml->createAttribute('Cantidad');		
		$domAttribute->value = $this->Cantidad;
		$concepto->appendChild($domAttribute);

		$domAttribute = $xml->createAttribute('ValorUnitario');
		$domAttribute->value = $this->ValorUnitario;
		$concepto->appendChild($domAttribute);

		$domAttribute = $xml->createAttribute('Descripcion');		
		$domAttribute->value = $this->Descripcion;
		$concepto->appendChild($domAttribute);

		$domAttribute = $xml->createAttribute('Importe');		
		$domAttribute->value = $this->Importe;
		$concepto->appendChild($domAttribute);
		
		$conceptos->appendChild($concepto);

		//if ($this->Importe > 0) {
			$impuestos = $xml->createElement('cfdi:Impuestos');
			$concepto->appendChild($impuestos);
			$traslados = $xml->createElement('cfdi:Traslados');
			$impuestos->appendChild($traslados);
			$traslado = $xml->createElement('cfdi:Traslado');
			
			$domAttribute = $xml->createAttribute('Base');
			$domAttribute->value = $this->Importe;
			$traslado->appendChild($domAttribute);

			$domAttribute = $xml->createAttribute('Impuesto');
			$domAttribute->value = $this->imp;
			$traslado->appendChild($domAttribute);

			$domAttribute = $xml->createAttribute('TipoFactor');
			$domAttribute->value = $this->TipoFactor;
			$traslado->appendChild($domAttribute);

			$domAttribute = $xml->createAttribute('TasaOCuota');
			$domAttribute->value = $this->impuesto[$this->imp];
			$traslado->appendChild($domAttribute);

			$this->ImpImpuesto = number_format($this->Importe * (float)$this->impuesto[$this->imp], 2, '.', '');
			$domAttribute = $xml->createAttribute('Importe');
			$domAttribute->value = $this->ImpImpuesto;
			$traslado->appendChild($domAttribute);

			$traslados->appendChild($traslado);
		//}

	}


}
?>