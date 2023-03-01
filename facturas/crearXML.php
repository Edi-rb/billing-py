<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
const PATH = __DIR__.'/logs';
if( ! file_exists(PATH) )
  mkdir(PATH);
ini_set('error_log', PATH . '\/'. date('Y-m-d') . '.log');

include("/var/www/api/antisql.php");
require_once('autoload.php');
include_once("/var/www/api/bd.php");
require_once('phpqrcode/qrlib.php');
include("/var/www/api/mtools.php");

use fpdf\FPDF;
use Classes\PDF;
use Classes\DataPDF;
use Classes\Emisor;
use Classes\Receptor;
use Classes\Comprobante;
use Classes\Conceptos;
use Classes\Impuestos\Impuestos;
use phpqrcode\QRcode;
print_r($_POST);
try {
	if (isset($_POST)) {
		$function = $_POST['function'];		//Función que se va a ejecutar
		$factura = $_POST['factura'];		//Folio de factura a realizar
		$usoFactura = 'G03';				//$_POST['usoFactura'];
		$crear = new CrearXML();
		$data = array('data' => $crear->$function($factura,$usoFactura));
	}
} catch (Exception $e) {
	$data = array('ERROR' => $e);
	echo json_encode($e);
}
/**
* 
*/
class CrearXML {
	protected $rfc; 
	protected $empresa;
	protected $pass;
	protected $formaPago;
	protected $metodoPago;
	protected $email = '';
	protected $ENombre;
	protected $ERegimen; 
	protected $EDireccion;
	protected $ERfc;
	protected $SDireccion;
	protected $RNombre;
	protected $RRfc;
	protected $Rcp;
	protected $RDireccion;
	protected $usoCFDI = 'G03';
	protected $tipoRelacion;
	protected $folioFiscal;
	protected $numSerieSAT;
	protected $numSerieCSD;
	protected $fechaCer;
	protected $fechaCFDI;
	protected $efectoComprobante;
	//protected $regimenFiscal;
	protected $folio;
	protected $serie;
	protected $selloCFDI;
	protected $selloSAT;
	protected $cadOriginalSAT;
	protected $NSucursal;

	//Variable de conexión a la base de Datos
	protected $dbapi;
	protected $meta;

	public function crear($folio,$usoFactura) {
		try {
			$this->dbapi = new DataDb;
			$this->meta = $_SERVER['REMOTE_ADDR']."|FacturaMicroTec|Web";
			$datos = $this->buscarDatos($folio);
			if (strlen ($datos[0]->SSatfac) < 20) {
				$cer = $this->generaCertificado($this->ERfc);	//Obtener certificado y número de certificado
				$xml = new DOMDocument('1.0', "UTF-8");
				$com = new Comprobante($xml, $folio, $this->tipoFactura, $this->formaPago, $datos[0]->SCp,$cer,$this->metodoPago);

				if( $datos[0]->VUUIDRel ){//si el campo referencia es una cadena con caracteres. puede ser null o hasta vacio, eso es un valor booleno false
					//entonces posee un uuid para relacionar
					$comprobante = $xml->getElementsByTagName('cfdi:Comprobante')->item(0);

					$Relacionados = $xml->createElement('cfdi:CfdiRelacionados');
					
					$domAttribute = $xml->createAttribute('TipoRelacion');
					$domAttribute->value = '04';
					$Relacionados->appendChild($domAttribute);

					$comprobante->appendChild($Relacionados);

					$Relacionados = $xml->getElementsByTagName('cfdi:CfdiRelacionados')->item(0);

					$Relacionado = $xml->createElement('cfdi:CfdiRelacionado');
					
					$domAttribute = $xml->createAttribute('UUID');
					$domAttribute->value = trim($datos[0]->VUUIDRel);
					$Relacionado->appendChild($domAttribute);

					$Relacionados->appendChild($Relacionado);

				}

				$emisor = new Emisor($this->ERfc,$xml,$this->ENombre ,$this->ERegimen );
				$receptor = new Receptor($datos[0]->CRfc,$xml,$this->usoCFDI,$datos[0]->CNombre);
				$this->RNombre = $receptor->getNombre();
				$this->RRfc = $receptor->getRfc();
				$this->RRecibe = $receptor->getRecibe();

				$conceptos = new Conceptos($xml,$folio,$this->dbapi,$this->meta);
				$impuestos = new Impuestos($xml,$conceptos->getTasa());
				$imp = $conceptos->getImpuestos();
				$com->insertaDatosFinal($xml,$conceptos->getTotal(),$imp['Tasa']['Importe']);

				$xml->formatOutput = true; 
	            $strings_xml = $xml->saveXML();
	            echo "<hr>";
	            echo htmlentities( $strings_xml );
	            echo "<hr>";
	            $xml->save('Files/XML/'.$folio.'.xml');
	            $sello = $this->generaSello('Files/XML/'.$folio.'.xml',$this->ERfc);

	            $com->guardaSello($xml,$sello);
	            $xml->formatOutput = true; 
	            $strings_xml = $xml->saveXML(); 
	            $xml->save('Files/XML/'.$folio.'.xml');

	            $this->timbrarCFD('Files/XML/'.$folio.'.xml',$folio);
	            /************  CREAR PDF  *******************/
	           	$this->fechaCer = $com->getFecha();
	            $this->numSerieCSD = $cer['noCer'];
	            $pdf = new PDF();
            	$pdf->constructor($this->ENombre,$this->EDireccion,$this->ERfc,$this->NSucursal,$this->SDireccion,$this->RNombre,$this->RRfc,$this->Rcp,$this->RDireccion, $this->RRecibe, $this->getUsoCFDI(),$this->getFormaPago(),$com->getMetodoPago(),$this->folioFiscal,$this->numSerieSAT,$this->numSerieCSD,$this->fechaCer, $this->fechaCFDI, $this->getTipoFactura(), $this->getERegimen(), $folio, $this->serie, $this->selloCFDI, $this->selloSAT, $this->cadOriginalSAT, $conceptos->getConceptos(), $this->dbapi, $this->meta, $datos[0]->VUUIDRel);

	            $this->leerArchivo($folio);
	            return $datos;
	        } else
				return  "Folio facturado";
		} catch (SDO_Exception $e) {
			return ("Problema creando un documento XML: " . $e->getMessage());
		}
	}

	public function buscarDatos($folio) {
		try {
			
			//$sql = 'SELECT DISTINCT c.nombre CNombre, c.rfc CRfc, s.calle SCalle, s.colonia SColonia, s.localidad SLocalidad, s.estado SEstado, s.cp SCp, v.distribuidor VDistribuidor, v.satmodp VSatmodp, v.empresa VEmpresa, v.satcli VSatcli, v.tipo VTipo FROM SAT.ventas as v, SAT.sucursales as s, SAT.clientes as c WHERE v.folio = "' . $folio . '" and s.codigsam = v.sucursal and v.satcli = c.idcliente';
			//$sql = 'SELECT DISTINCT l.dis NSucursal, l.representa N2Sucursal , c.nombre CNombre, c.rfc CRfc, s.calle SCalle, s.colonia SColonia, s.localidad SLocalidad, s.estado SEstado, s.cp SCp, v.distribuidor VDistribuidor, v.satmodp VSatmodp, v.empresa VEmpresa, v.satcli VSatcli, v.tipo VTipo FROM SAT.ventas as v, SAT.sucursales as s, SAT.clientes as c, canal.lista as l WHERE v.folio = "' . $folio . '" and s.codigsam = v.sucursal and v.satcli = c.idcliente and l.user = s.codigsam';
			$sql = 'SELECT DISTINCT l.dis NSucursal, l.representa N2Sucursal , c.nombre CNombre, c.rfc CRfc, c.cp CCp, c.direccion CDireccion, s.calle SCalle, s.colonia SColonia, s.localidad SLocalidad, s.estado SEstado, s.cp SCp, v.distribuidor VDistribuidor, v.satmodp VSatmodp, v.empresa VEmpresa, v.satcli VSatcli, v.tipo VTipo, "1320164" VReferencia, v.satfac SSatfac, v.uso_cfdi usoCFDI, v.descripcion VDescripcion, v.referencia AS VUUIDRel FROM SAT.ventas as v, SAT.sucursales as s, SAT.clientes as c, canal.lista as l WHERE v.folio = "' . $folio . '" and s.codigsam = v.sucursal and v.satcli = c.idcliente and l.user = s.codigsam';
			echo $sql;

			//$venta = json_decode($conAct->queryList($sql,$datos));
			
			$venta = $this->dbapi->exec($sql,$this->meta);
			print_r($venta);

			$this->RDireccion = $venta[0]->CDireccion;
			$this->Rcp = $venta[0]->CCp;
			$this->setNSucursal($venta[0]->NSucursal);
			$this->setempresa($venta[0]->VEmpresa);
			$this->setFormaPago($venta[0]->VSatmodp);
			$this->SDireccion = $venta[0]->SCalle.', '.$venta[0]->SColonia.', '.$venta[0]->SLocalidad.', '.$venta[0]->SEstado;
			$this->setUsoCFDI($venta[0]->usoCFDI);
			$this->setTipoFactura($venta[0]->VTipo);

			return $venta;
		} catch (Exception $e) {
			echo $e->__toString();
			return $e;
		}
	}

	public function getUsoCFDI() {
		if ($this->usoCFDI == 'G01') {
			return 'G01 Adquisición de Mercancias';
		} else if ($this->usoCFDI == 'G02') {
			return 'G02 Devoluciones, descuentos o bonificaciones';
		} else if ($this->usoCFDI == 'G03') {
			return 'G03 Gastos en general';
		} else if ($this->usoCFDI == 'P01') {
			return 'P01 Por definir';
		}
	}

	public function setUsoCFDI($usoCFDI) {
		if ($usoCFDI != "") {
			$this->usoCFDI = $usoCFDI; 
		}
	}

	public function setNSucursal($NSucursal) {
		if ($NSucursal != '') {
			$this->NSucursal =	$NSucursal;
		}
	}

	public function buscarProductos($folio) {
		$datos = array('Folio' => $folio );
		$sql = 'SELECT v.codigo, v.precioun FROM SAT.ventas as v WHERE v.folio = :Folio';
		$datos = json_decode($conAct->queryList($sql,$datos));
		return $datos;
	}

	function generaCadOriginal($xmlRuta) {
    	// Ruta al archivo XSLT
    	$xslRuta = "lib/cadenaoriginal_3_3.xslt"; 
    	// Crear un objeto DOMDocument para cargar el CFDI
    	$xml = new DOMDocument("1.0","UTF-8"); 
    	// Cargar el CFDI
    	$xml->load($xmlRuta);
    	// Crear un objeto DOMDocument para cargar el archivo de transformación XSLT
    	$xsl = new DOMDocument();
    	$xsl->load($xslRuta);
    	// Crear el procesador XSLT que nos generará la cadena original con base en las reglas descritas en el XSLT
    	$proc = new XSLTProcessor;
    	// Cargar las reglas de transformación desde el archivo XSLT.
    	$proc->importStyleSheet($xsl);
    	// Generar la cadena original y asignarla a una variable
    	$cadenaOriginal = $proc->transformToXML($xml);

		return $cadenaOriginal;
	}

	function generaSello($xmlRuta,$emisor) {
		// Genera la cadena original
		$cadenaOriginal = $this->generaCadOriginal($xmlRuta);
		if ($emisor == 'MMO120416IB6') {
			// MICROTECNOLOGIAS MOVILES
			// Ruta a la llave privada del Certificado de Sello Digital (CSD) (.key.pem)
			$rutaCSD = "CSD/CSD_moviles_MMO120416IB6_20160722_232536.key.pem";
		}
		else if( $emisor == 'MTC180717K3A' ){
			//MICROTECNOLOGIAS y COMUNICACIONES
			$rutaCSD = "CSD/CSD_MICROTECNOLOGIA_Y_COMUNICACIONES_MTC180717K3A_20180730_115554.key.pem";
		}
		else {
			// MICROTECNOLOGIAS DEL GOLFO
			// Ruta a la llave privada del Certificado de Sello Digital (CSD) (.key.pem)
			$rutaCSD = "CSD/CSD_microtec1_GMG040722E79_20160905_151458.key.pem";
		}
		// Carga el Certificado de Sello Digital
		$llave = openssl_get_privatekey(file_get_contents($rutaCSD));
		// Realiza la encriptacion 
		openssl_sign($cadenaOriginal, $crypttext, $llave, OPENSSL_ALGO_SHA256);
		// Libera el Certificado de Sello Digital
		openssl_free_key($llave);
		// Codifica a base64
		$sello = base64_encode($crypttext);

		return $sello;
	}

	function generaCertificado($emisor) {
		if ($emisor == "MMO120416IB6") {
			// MICROTECNOLOGIAS MOVILES
			// Carga vertificado
			$cer = file("CSD/CSD_moviles_MMO120416IB6_20160722_232536s.cer.pem");
			$noCer = "00001000000403185533";
		}
		else if( $emisor == 'MTC180717K3A' ){
			$cer = file("CSD/CERTIFICADO_MTC_00001000000411668796.cer.pem");
			$noCer = "00001000000411668796";
		}
		else {
			// MICROTECNOLOGIAS DEL GOLFO
			// Carga vertificado
			$cer = file("CSD/CSD_microtec1_GMG040722E79_20160905_151458s.cer.pem");
			$noCer = "00001000000403587715";
		}
		$certificado = ""; 
		$carga = false;
		for ($i=0; $i<sizeof($cer); $i++) {
    		if (strstr($cer[$i],"END CERTIFICATE"))
     			$carga = false;
    		if ($carga)
     			$certificado .= trim($cer[$i]);
    		if (strstr($cer[$i],"BEGIN CERTIFICATE"))
     			$carga = true;
		}
		$datos = array('cer' => $certificado, 'noCer' => $noCer );
		return $datos;
	}

	function codificaXML($xmlRuta) {
		// Carga archivo
		$xml = file_get_contents($xmlRuta);
		// Codifica contenido xml a base64
		$xmlCodif = base64_encode($xml);
		return $xmlCodif;
	}

	public function timbrarCFD($xmlRuta,$folio) {
		try {
			//$ws = "https://cfdi33-pruebas.buzoncfdi.mx:1443/Timbrado.asmx?wsdl"
			$ws = "https://timbracfdi33.mx:1443/Timbrado.asmx?wsdl";
			$cod64 = $this->codificaXML($xmlRuta);
			$params = array('usuarioIntegrador' => 'lKWarjJTn88yq5whBB50HA==|micro01cfdi',
							'xmlComprobanteBase64' => $cod64,
							'idComprobante' => $folio);
			// set some SSL/TLS specific options
			$config = array('ssl' => array('verify_peer' => false,
											'verify_peer_name' => false,
											'allow_self_signed' => false),	// True para pruebas
							'http' => array('user_agent' => 'PHPSoapClient'));
			$context = stream_context_create($config);
			$options = array('stream_context' => $context,
								'cache_wsdl' => WSDL_CACHE_MEMORY,
								'trace' => true);
			libxml_disable_entity_loader(false);
			$client = new SoapClient($ws,$options);
			$response = $client->__soapCall('TimbraCFDI', array('parameters' => $params));
			print_r($response);
			$xmlTimbrado = $response->TimbraCFDIResult->anyType[3];
			$codQR = $response->TimbraCFDIResult->anyType[4];
			if ($xmlTimbrado != '') {
				/******************** ACTUALIZA COMISIONES ********************/
				$sql = "UPDATE samtec.comisiones set facnota='F' where factura='".$folio."'";
				$comision = $this->dbapi->exec($sql,$this->meta);

				$this->cadOriginalSAT = $response->TimbraCFDIResult->anyType[5];
				$this->folio = $folio;
				$this->serie = '';

				$doc = new DOMDocument();
				$doc->loadXML($xmlTimbrado);
				$doc->saveXML();
				$doc->formatOutput = true; 
		        $doc->save('Files/XSLT/'.$folio.'.xml');

		        $xml = simplexml_load_file('Files/XSLT/'.$folio.'.xml'); 
				$ns = $xml->getNamespaces(true);
				$xml->registerXPathNamespace('t', $ns['tfd']);

				foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
					$this->folioFiscal = strtoupper($tfd['UUID']);
					$this->numSerieSAT = $tfd['NoCertificadoSAT'];;	
					$this->fechaCFDI = $tfd['FechaTimbrado'];
					$this->selloCFDI = $tfd['SelloCFD'];
					$this->selloSAT = $tfd['SelloSAT'];
				}

				/******************** INSERTAR Values en la base ********************/
		    	$sql = "UPDATE SAT.ventas set satfac='".$this->folioFiscal."' where folio='".$folio."'";
		    	$satfac = $this->dbapi->exec($sql,$this->meta);
		    	/******************** ACTIVA NOTA DE CREDITO ********************/
		    	if ($this->tipoFactura == 'E') {
		    		$this->activaNC($folio);
		    	}
			} else {
				$tipoEx = $response->TimbraCFDIResult->anyType[0];
				$numEx = $response->TimbraCFDIResult->anyType[1];
				$descEx = $response->TimbraCFDIResult->anyType[2];
				$sql = "UPDATE SAT.ventas set acuse='".$tipoEx.' '.$numEx.' '.$descEx."' where folio='".$folio."'";
				$satfac = $this->dbapi->exec($sql,$this->meta);
			}
		} catch (SoapFault $fault) {
			echo "SOAPFault: ".$fault->faultcode."-".$fault->faultstring."\n";
		}
	}

	public function setempresa($empresa) {
		if ($empresa == 'MM') {
			//$this->empresa = 'MICROTECNOLOGIAS MOVILES SA DE CV';
			$this->ERfc = 'MMO120416IB6';
			$this->pass = 'portal16';
		}
		else if( $empresa == 'MC' ){
			$this->ERfc = 'MTC180717K3A';
			$this->pass = 'mtc18071';
		}
		else {
			//$this->ENombre = 'GRUPO MICRO-TECNOLOGIA DEL GOLFO SA DE CV';
			$this->ERfc = 'GMG040722E79';
			$this->pass = 'a85dw4';
		}
		$sql = 'SELECT e.nombre, e.regimen_fiscal, e.direccion FROM SAT.empresas as e WHERE e.rfc = "' . $this->ERfc . '"';
		echo $sql;

		$empresa = $this->dbapi->exec($sql,$this->meta);
		print_r($empresa);

		$this->ENombre = $empresa[0]->nombre;
		$this->EDireccion = $empresa[0]->direccion;
		$this->ERegimen = $empresa[0]->regimen_fiscal;// . ' General de Ley Personas Morales';
		$this->ENombre = $empresa[0]->nombre;
	}

	public function getERegimen() {
		return $this->ERegimen . ' General de Ley Personas Morales';
	}

	public function setTipoFactura($tipo) {
		/*
		*	A=Ingreso credito
		*	B=ingreso contado
		*	N= nota de crédito
		*/
		if ($tipo == 'A') {
			$this->tipoFactura = 'I';
			$this->metodoPago = 'PPD';
			$this->setFormaPago('Por definir');
		} else if ($tipo == 'B') {
			$this->tipoFactura = 'I';
			$this->metodoPago = 'PUE';
		} else if ($tipo == 'Y') {
			$this->tipoFactura = 'I';
			$this->metodoPago = 'PPD';
		} else if ($tipo == 'N') {
			$this->tipoFactura = 'E';
			$this->metodoPago = 'PUE';
			$this->usoCFDI = 'G02';
		} else {
			$this->tipoFactura = 'E';
		}
	}

	public function setFormaPago($formaPago) {
		if ($formaPago != '') {
			switch ($formaPago) {
				case 'Efectivo':
					$this->formaPago = '01';
					break;
				case 'Cheque':
					$this->formaPago = '02';
					break;
				case 'Cheque nominativo':
					$this->formaPago = '02';
					break;
				case 'Transf. Electronica':
					$this->formaPago = '03';
					break;
				case 'Transf. Electrónica':
					$this->formaPago = '03';
					break;
				case 'Transferencia electrónica de fondos':
					$this->formaPago = '03';
					break;				
				case 'Tarjeta de Credito':
					$this->formaPago = '04';
					break;
				case 'Tarj. de Credito':
					$this->formaPago = '04';
					break;
				case 'Tarjeta de crédito':
					$this->formaPago = '04';
					break;
				case 'Por definir':
					$this->formaPago = '99';
					break;
				case 'Tarj. de Debito':
					$this->formaPago = '28';
					break;
				case 'Tarjeta de Debito':
					$this->formaPago = '28';
					break;
				case 'Tarjeta de débito':
					$this->formaPago = '28';
					break;
				case 'Compensacion':
					$this->formaPago = '17';
					break;
				case 'No Identificado':
					$this->formaPago = '99';
					break;
				default:
					$this->formaPago = '99';
					break;
			}
		}
	}

	public function getFormaPago() {
		switch ($this->formaPago) {
			case '01':
				return '01 Efectivo';
				break;
			case '02':
				return '02 Cheque';
				break;
			case '03':
				return '03 Transferencia electrónica de fondos';
				break;
			case '04':
				return '04 Tarjeta de crédito';
				break;
			case '28':
				return '28 Tarjeta de débito';	
				break;
			case '17':
				return '17 Compensacion';	
				break;
			case '99':
				return '99 Por definir';
				break;
			default:
				break;
		}
	}

	public function getTipoFactura() {
		if ($this->tipoFactura == 'I') {
			return 'I Ingreso';
		} else if ($this->tipoFactura == 'E') {
			return 'E Egreso';
		}
	}

	public function leerArchivo($folio) {
		$namepdf = $_SERVER['DOCUMENT_ROOT']."/pagina/factura33/facturas/Files/PDF/".$folio.".pdf";  // 'Files/PDF/'..'.pdf';
		$namexml = $_SERVER['DOCUMENT_ROOT']."/pagina/factura33/facturas/Files/XML/".$folio.".xml";  //'Files/XML/'.$folio.'.xml';
		$namexslt = $_SERVER['DOCUMENT_ROOT']."/pagina/factura33/facturas/Files/XSLT/".$folio.".xml"; //'Files/XSLT/'.$folio.'.xml';
		//   Verificar si existe el archivo xml timbado
		if (file_exists($namexslt)) {
			if ($this->ERfc == 'MMO120416IB6') {
				$ruta = 'facturas/mtm/xml/';
				echo $this->enviaArchivo($namexml,$ruta);

				$ruta = 'facturas/mtm/pdf/';
				echo $this->enviaArchivo($namepdf,$ruta);

				$ruta = 'facturas/mtm/xslt/';
				echo $this->enviaArchivo($namexslt,$ruta);
			} else if ($this->ERfc == 'GMG040722E79') {//$this->ENombre = 'GRUPO MICRO-TECNOLOGIA DEL GOLFO SA DE CV';
				$ruta = 'facturas/mtc/xml/';
				echo $this->enviaArchivo($namexml,$ruta);

				$ruta = 'facturas/mtc/pdf/';
				echo $this->enviaArchivo($namepdf,$ruta);

				$ruta = 'facturas/mtc/xslt/';
				echo $this->enviaArchivo($namexslt,$ruta);
			}
			else if( $this->ERfc == 'MTC180717K3A' ){//empresa Microtecnologias y COmunicaciones (mtcom)
				$ruta = 'facturas/mtcom/xml/';
				echo $this->enviaArchivo($namexml,$ruta);

				$ruta = 'facturas/mtcom/pdf/';
				echo $this->enviaArchivo($namepdf,$ruta);

				$ruta = 'facturas/mtcom/xslt/';
				echo $this->enviaArchivo($namexslt,$ruta);
			}
		}
		else {
			echo "No existe el archivo, que se desea subir";
		}
	}

	public function enviaArchivo($fname, $ruta) {
		$tools = new MtcTools('file');
		$res = $tools->move($fname,$ruta);
		return  $res;
	}

	public function activaNC($folio) {
		$sql = "UPDATE SAT.ventas SET nc_activa='1' where folio='".$folio."' LIMIT 1";
		$res = $this->dbapi->exec($sql,$this->meta);
	}
}
/*
*	usuario y pass para timbrado móviles
*	"MMO120416IB6","webservice17"
*
*	usuario y pass para timbrado microtec
*	"GMG040722E79","a85dw4"
* 	
*/
?>