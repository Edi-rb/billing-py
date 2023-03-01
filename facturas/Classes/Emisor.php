<?php 
namespace Classes;
//require_once('autoload.php');
use Classes\Conexiones\ConexionActiva;
use Classes\Conexiones\ConexionSAT;
/**
* 
*/
class Emisor
{
	protected $rfc;
	protected $nombre;
	protected $CURP;
	protected $regimenFiscal;

	function __construct($rfc, $xml, $nombre, $regimen)
	{

		if ($rfc) {
			$this->setRfc($rfc);
			$this->setNombre($nombre);
			$this->setRegimenFiscal($regimen);
		}
		else {
			$this->setRfc('MMO120416IB6');
			$this->setNombre('MICROTECNOLOGIAS MOVILES SA DE CV');
			//$this->setCURP('');
			$this->setRegimenFiscal('601');
		}
		$this->crearNodoEmisor($xml);
	}
	public function setRfc($rfc)
	{
		if ($rfc != '') {
			$this->rfc = $rfc;
		}
	}
	public function setNombre($nombre)
	{
		if ($nombre != '') {
			$this->nombre = $nombre;
		}
	}
	public function setCURP($CURP)
	{
		if ($CURP != '') {
			$this->CURP = $CURP;
		}
	}
	public function setRegimenFiscal($regimenFiscal)
	{
		if ($regimenFiscal != '') {
			$this->regimenFiscal = $regimenFiscal;
		}
	}

	public function crearNodoEmisor($xml)
	{
		$comprobante = $xml->getElementsByTagName('cfdi:Comprobante')->item(0);

		$emisor = $xml->createElement('cfdi:Emisor');
		
		$domAttribute = $xml->createAttribute('Rfc');
		$domAttribute->value = $this->rfc;
		$emisor->appendChild($domAttribute);

		$domAttribute = $xml->createAttribute('Nombre');
		$domAttribute->value = $this->nombre;
		$emisor->appendChild($domAttribute);

		$domAttribute = $xml->createAttribute('RegimenFiscal');
		$domAttribute->value = $this->regimenFiscal;
		$emisor->appendChild($domAttribute);

		$comprobante->appendChild($emisor);
		return $comprobante;
		//return $emisor;
	}
}
 ?>