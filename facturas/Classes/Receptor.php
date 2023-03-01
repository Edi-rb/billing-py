<?php 
namespace Classes;
//require_once('autoload.php');
use Classes\Conexiones\ConexionActiva;
use Classes\Conexiones\ConexionSAT;
/**
* 
*/
class Receptor
{
	protected $rfc;
	protected $nombre = "PUBLICO EN GENERAL";
	protected $regimenFiscal;
	protected $residenciaFiscal;		//
	protected $numRegIdTrib;			//(1-40 caracteres)
	protected $usoCFDI ;				//G01 = Adquisición de mercancias
	protected $recibe;


	function __construct($rfc, $xml, $usoCFDI,$nombre){
		$this->recibe = $nombre;
	
		$this->setUsoCFDI($usoCFDI);
		$this->setRfc($rfc);
		if ($this->rfc != "XAXX010101000") {
			$this->setNombre($nombre);
		}
		$this->crearNodoReceptor($xml);
	}	

	public function setRfc($rfc)
	{
		$rfc = strtoupper($rfc);
		if ($rfc != '' && $rfc != 'undefined') {
			$this->rfc = trim($rfc);
		}else
			$this->rfc = "XAXX010101000";
	}

	public function setNombre($nombre)
	{
		if ($nombre != '') {
			$this->nombre = $nombre;
		}else
			$this->$nombre = "PUBLICO EN GENERAL";
	}

	public function setRegimenFiscal($regimenFiscal)
	{
		if ($regimenFiscal != '') {
			$this->regimenFical = $regimenFiscal;
		}
	}

	public function setResidenciaFiscal($residenciaFiscal)
	{
		if ($residenciaFiscal != '') {
			$this->residenciaFical = $residenciaFiscal;
		}
	}

	public function setNumRegIdTrib($numRegIdTrib)
	{
		if ($numRegIdTrib != '') {
			$this->numRegIdrib = $numRegIdTrib;
		}
	}

	public function setUsoCFDI($usoCFDI)
	{
		if ($usoCFDI != '') {
			$this->usoCFDI = $usoCFDI;
		}else
			$this->usoCFDI = "G03";
	}

	public function getNombre()
	{
		return $this->nombre;
	}	

	public function getRecibe()
	{
		if ($this->nombre == "PUBLICO EN GENERAL" ) {
			return $this->recibe;
		}
			return '';	
	}

	public function getRfc()
	{
		return $this->rfc;
	}

	public function crearNodoReceptor($xml)
	{
		$comprobante = $xml->getElementsByTagName('cfdi:Comprobante')->item(0);

		$emisor = $xml->createElement('cfdi:Receptor');
		
		$domAttribute = $xml->createAttribute('Rfc');
		$domAttribute->value = $this->rfc;
		$emisor->appendChild($domAttribute);

		$domAttribute = $xml->createAttribute('Nombre');
		$domAttribute->value = $this->nombre;
		$emisor->appendChild($domAttribute);

		$domAttribute = $xml->createAttribute('UsoCFDI');
		$domAttribute->value = $this->usoCFDI;
		$emisor->appendChild($domAttribute);

		$comprobante->appendChild($emisor);
	}

}

?>
