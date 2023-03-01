<?php 
namespace Classes;
//require_once('autoload.php');
use Classes\Concepto\Concepto;
use Classes\Conexiones\ConexionActiva;
use Classes\Conexiones\ConexionSAT;
/**
* 
*/
class Conceptos
{
	protected $campo;
	protected $claveProdServ;
	protected $noIdentificacion;
	protected $cantidad;
	protected $claveUnidad;
	protected $unidad;
	protected $descripcion;
	protected $valorUnitario;
	protected $importe;
	protected $descuento;
	protected $Impuestos = [];  // Traslados  o Retenciones
	protected $Total;
	protected $conceptos;
	protected $dbapi;
	protected $meta;



	protected $tasa = array('Tasa' => array('Importe' => 0, 'Impuesto' => '002', 'TasaOCuota' => '0.160000', 'TipoFactor' => 'Tasa' ));

	function __construct($xml, $folio, $dbapi, $meta)
	{
		$this->dbapi = $dbapi;
		$this->meta = $meta;
		$this->crearNodoConceptos($xml);
		$this->conceptos = $this->buscarProductos($folio);
		foreach ($this->conceptos as $concepto) {
			if($concepto->precioun == 0) $concepto->precioun = 0.01;
			
			// if( trim($concepto->codigo) !== 'TAE001' )
				$this->Total += ($concepto->precioun / 1.16) * $concepto->cantidad;
			// else
				// $this->Total += ($concepto->precioun / 1.16); //* $concepto->cantidad;//no se multiplica por la cantidad

			$concepto = new Concepto($xml,$concepto);
			//$this->Total += $concepto->getImporte();
			if ($concepto->recolectarImpuesto()) {
				if($concepto->getTipoFactor() == 'Tasa'){
					$this->tasa['Tasa']['Importe'] += $concepto->getImpImpuesto();
					//echo $concepto->getImpImpuesto();
				}
			}
		}
		//echo number_format($this->Total, 2, '.', '');
		//print_r($this->tasa);
		//Es 
		//En esta parte debo cambiar cuando sea TAE001 el total a concepto por precioun
		
	}

	public function getTotal()
	{
		return $this->Total;
	}

	public function getConceptos()
	{
		return $this->conceptos;
	}

	public function buscarProductos($folio)
	{
		$sql = "SELECT v.cantidad, v.precioun ,v.codigo, v.descripcion, v.unidad, v.referencia, v.clave_producto FROM SAT.ventas as v WHERE v.folio = '".$folio."' ORDER BY v.descripcion";
		$datos = $this->dbapi->exec($sql,$this->meta);
		print_r($datos);
		return $datos;
	}

	public function getTasa()
	{
		return $this->tasa;
	}

	public function setcampo($campo)
	{
	 	if ($campo != '') {
	 		$this->campo = $campo;
		}
	} 

	public function setclaveProdServ($claveProdServ)
	{
	 	if ($claveProdServ != '') {
	 		$this->claveProdServ = $claveProdServ;
		}
	} 

	public function setnoIdentificacion($noIdentificacion)
	{
	 	if ($noIdentificacion != '') {
	 		$this->noIdentificacion = $noIdentificacion;
		}
	} 

	public function setcantidad($cantidad)
	{
	 	if ($cantidad != '') {
	 		$this->cantidad = $cantidad;
		}
	} 

	public function setclaveUnidad($claveUnidad)
	{
	 	if ($claveUnidad != '') {
	 		$this->claveUnidad = $claveUnidad;
		}
	} 

	public function setunidad($unidad)
	{
	 	if ($unidad != '') {
	 		$this->unidad = $unidad;
		}
	} 

	public function setdescripcion($descripcion)
	{
	 	if ($descripcion != '') {
	 		$this->descripcion = $descripcion;
		}
	} 

	public function setvalorUnitario($valorUnitario)
	{
	 	if ($valorUnitario != '') {
	 		$this->valorUnitario = $valorUnitario;
		}
	} 

	public function setimporte($importe)
	{
	 	if ($importe != '') {
	 		$this->importe = $importe;
		}
	} 

	public function setdescuento($descuento)
	{
	 	if ($descuento != '') {
	 		$this->descuento = $descuento;
		}
	} 

	public function setImpuestos($Impuestos)
	{
	 	if ($Impuestos != '') {
	 		$this->Impuestos = $Impuestos;
		}
	} 

	public function crearNodoConceptos($xml)
	{
		$comprobante = $xml->getElementsByTagName('cfdi:Comprobante')->item(0);
		$conceptos = $xml->createElement('cfdi:Conceptos');
		$comprobante->appendChild($conceptos);
	}

	public function getImpuestos()
	{
		return $this->tasa;
	}

}
?>