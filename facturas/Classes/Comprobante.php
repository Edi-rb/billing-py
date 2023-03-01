<?php 
	namespace Classes;
	use Classes\Conexiones\ConexionSAT;
	/**
	*	Sello, Certificado, noCertificado http://www.facturando.mx/blog/index.php/tag/cfdi-3-3/
	* 	Reglas para el xml:  http://www.sat.gob.mx/informacion_fiscal/factura_electronica/Documents/cfdv33.pdf
	* verificar xml:https://solucionfactible.com/sfic/capitulos/timbrado/timbrado-test.jsp
	* 
	* 
	*/
	class Comprobante
	{
		
		protected $version = '3.3';		//Forsozamente 3.3
		protected $serie;				//Control interno (1-25 caracteres)
		protected $folio;				//Folio Contol interno (1-40 caracteres)
		protected $fecha;				//Dato lo integra el sistema para emisión del comprobante fiscal  AAAA-MM-DDThh:mm:ss
		protected $certificado;				//lo integra el sistema que utiliza el contribuyente
		protected $formaPago;	// 01 Efectivo ; la clave de forma de pago con la que se liquida la mayor cantidad del pago En caso de que no se reciba el pago seleccionar clave 99
		protected $noCertificado;// = '00001000000403185533';		//  lo incluye en el comprobante fiscal el sistema que utiliza el contribuyente para la emisión
		protected $sello;			//generado con el certificado de sello digital del emisor
		protected $condicionesDePago;	// condiciones comerciales aplicables para el pago del comprobante fiscal (1-1000 caracteres)
		protected $subTotal;			//Suma de importes de conceptos antes de descuentos e impuestos > 0 decimales permitidas por el campo moneda MXN 2
		protected $descuento;			// I(Ingreso), E(Egreso) o N(Nómina)
		protected $moneda = 'MXN';				//MXN
		protected $tipoCambio;			//Se puede registrar el tipo de cambio conforme a la moneda registrada en el comprobante.
		protected $total;				//Suma del subtotal, menos los descuentos aplicables, más las contribuciones recibidas menos los impuestos retenidos federales o locales
		protected $tipoDeComprobante;	//I=Ingreso, E=Egreso, T=Traslado, N=Nómina, P=Pago verificar los limites
		protected $metodoPago = 'PUE';			//PUE=Pago en una sola exhibición, PPD=Pago en parcialidades o diferido
		protected $lugarExpedicion;		//Código postal incluida en el catálogo (matriz o sucursal),se cumple con el requisito
		protected $confirmacion ; 		// (5 caracteres)

		function __construct($xml,$folio, $tipoFactura, $formaPago, $sucursal, $certificado, $metodoPago){
			$this->setMetodoPago($metodoPago);
			$this->setFolio($folio);
			$this->setTipoDeComprobante($tipoFactura);
			$this->setFormaPago($formaPago);
			$this->setLugarExpedicion($sucursal);
			$this->setCertificado($certificado['cer']);
			$this->setNoCertificado($certificado['noCer']);
			$this->fecha();


			$domElement = $xml->createElement('cfdi:Comprobante');

			$domAttribute = $xml->createAttribute('xmlns:cfdi');	//Crear atributo para el elemto comprobante
			$domAttribute->value = 'http://www.sat.gob.mx/cfd/3';	// Valor para el atributo
			$domElement->appendChild($domAttribute);	// No olvidar agregar el atributo al elemento

			$domAttribute = $xml->createAttribute('xmlns:xsi');
			$domAttribute->value = 'http://www.w3.org/2001/XMLSchema-instance';
			$domElement->appendChild($domAttribute);

			$domAttribute = $xml->createAttribute('xsi:schemaLocation');
			$domAttribute->value = 'http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv33.xsd';
			$domElement->appendChild($domAttribute);

			$domAttribute = $xml->createAttribute('Version');
			$domAttribute->value = $this->version;
			$domElement->appendChild($domAttribute);

			$domAttribute = $xml->createAttribute('Folio');
			$domAttribute->value = $this->folio;
			$domElement->appendChild($domAttribute);

			$domAttribute = $xml->createAttribute('Fecha');
			$domAttribute->value = $this->fecha;
			$domElement->appendChild($domAttribute);



			$domAttribute = $xml->createAttribute('NoCertificado');
			$domAttribute->value = $this->noCertificado;
			$domElement->appendChild($domAttribute);

			$domAttribute = $xml->createAttribute('Certificado');
			$domAttribute->value = $this->certificado;
			$domElement->appendChild($domAttribute);


			$domAttribute = $xml->createAttribute('FormaPago');
			$domAttribute->value = $this->formaPago;
			$domElement->appendChild($domAttribute);

			$domAttribute = $xml->createAttribute('Moneda');
			$domAttribute->value = $this->moneda;
			$domElement->appendChild($domAttribute);

			$domAttribute = $xml->createAttribute('MetodoPago');
			$domAttribute->value = $this->metodoPago;
			$domElement->appendChild($domAttribute);

			$domAttribute = $xml->createAttribute('Folio');
			$domAttribute->value = $this->folio;
			$domElement->appendChild($domAttribute);


			$domAttribute = $xml->createAttribute('TipoDeComprobante');
			$domAttribute->value = $this->tipoDeComprobante;
			$domElement->appendChild($domAttribute);

			$domAttribute = $xml->createAttribute('LugarExpedicion');
			$domAttribute->value = $this->lugarExpedicion;
			$domElement->appendChild($domAttribute);

			$xml->appendChild($domElement);
			//return $domElement;
	
		}

		public function getMetodoPago()
		{
			if ($this->metodoPago == 'PUE') {
				return $this->metodoPago . ' Pago en una sola exhibición';
			}else if ($this->metodoPago == 'PPD') {
				return $this->metodoPago . ' Pago en parcilidades o diferido';
			}
		}

		function setValues($version, $serie, $folio, $sello, $formaPago, $noCertificado, $certificado, $condicionesDePago, $subTotal, $descuento, $moneda, $tipoCambio, $total, $tipoDeComprobante, $metodoPago, $lugarExpedicion)
		{
			setVersion($version);
			setSerie($serie);
			setFolio($folio);
			$this->fecha();
			setSello($sello);
			setFormaPago($formaPago);
			setNoCertificado($noCertificado);
			setCertificado($certificado);
			setCondicionesDePago($condicionesDePago);
			setSubTotal($subTotal);
			setDescuento($descuento);
			setMoneda($moneda);
			setTipoCambio($tipoCambio);
			setTotal($total);
			setTipoDeComprobante($tipoDeComprobante);
			setMetodoPago($metodoPago);
			setLugarExpedicion($lugarExpedicion);
			setConfirmacion($confirmacion);
		}

		public function setVersion($version)
		{
			if ($version != '') {
				$this->version = $version;
			}
		}

		public function setSerie($serie)
		{
			if ($serie != '') {
				$this->serie = $serie;
			}
		}

		public function setFolio($folio)
		{
			if ($folio != '') {
				$this->folio = $folio;
			}
		}

		public function setFecha($fecha)
		{
			if ($fecha != '') {
				$this->fecha = $fecha;
			}
		}


		public function setSello($sello)
		{
			if ($sello != '') {
				$this->sello = $sello;
			}
		}


		public function setFormaPago($formaPago)
		{
			if ($formaPago != '') {
				$this->formaPago = $formaPago;
			}
		}



		public function setNoCertificado($noCertificado)
		{
			if ($noCertificado != '') {
				$this->noCertificado = $noCertificado;
			}
		}


		public function setCertificado($certificado)
		{
			if ($certificado != '') {
				$this->certificado = $certificado;
			}
		}


		public function setCondicionesDePago($condicionesDePago)
		{
			if ($condicionsDePago != '') {
				$this->condicionesDePago = $condicionesDePago;
			}
		}


		public function setSubTotal($subTotal)
		{
			if ($subTotal != '') {
				$this->subTotal = $subTotal;
			}
		}


		public function setDescuento($descuento)
		{
			if ($descuento != '') {
				$this->descuento = $descuento;
			}
		}

		public function setMoneda($moneda)
		{
			if ($this-$moneda != '') {
				$this->moneda = $moneda;
			}
		}

		public function setTipoCambio($tipoCambio)
		{
			if ($tipoCambio != '') {
				$this->tipoCambio = $tipoCambio;
			}
		}


		public function setTotal($total)
		{
			if ($total != '') {
				$this->total = $total;
			}
		}


		public function setTipoDeComprobante($tipoDeComprobante)
		{
			if ($tipoDeComprobante != '') {
				$this->tipoDeComprobante = $tipoDeComprobante;
			}
		}


		public function setMetodoPago($metodoPago)
		{
			if ($metodoPago != '') {
				$this->metodoPago = $metodoPago;
			}
		}


		public function setLugarExpedicion($lugarExpedicion)
		{
			if ($lugarExpedicion != '') {
				$this->lugarExpedicion = $lugarExpedicion;
			}
		}


		public function setConfirmacion($confirmacion)
		{
			if ($confirmacion != '') {
				$this->confirmacion = $confirmacion;
			}
		}

		public function fecha()
		{
			date_default_timezone_set('America/Mexico_City');
			$this->fecha = date("Y-m-d\TH:i:s");
		}

		public function getFecha()
		{
			return $this->fecha;
		}

		/*public function lugarExpedicion($sucursal)
		{
			//'mic014'
			$conAct = new ConexionSAT();
			//print_r($sucursal);
			$datos = array('Sucursal' => $sucursal);
			$sql = "SELECT s.cp FROM sat.sucursales as s WHERE s.codigsam = :Sucursal";
			$datos = json_decode($conAct->queryList($sql,$datos));
			//print_r($datos);
			$this->lugarExpedicion = $datos[0]->cp;
			//return c
		}*/

		public function insertaDatosFinal($xml,$total,$ImpImporte)
		{
			$comprobante = $xml->getElementsByTagName('cfdi:Comprobante')->item(0);

			$this->setSubTotal(number_format($total, 2, '.', ''));
			$this->setTotal( number_format($total + $ImpImporte, 2, '.', ''));

			$domAttribute = $xml->createAttribute('SubTotal');
			$domAttribute->value = $this->subTotal;
			$comprobante->appendChild($domAttribute);

			$domAttribute = $xml->createAttribute('Total');
			$domAttribute->value = $this->total;
			$comprobante->appendChild($domAttribute);

		}
		
		public function guardaSello($xml,$sello)
		{
			$this->setSello($sello);
			$comprobante = $xml->getElementsByTagName('cfdi:Comprobante')->item(0);

			$domAttribute = $xml->createAttribute('Sello');
			$domAttribute->value = $this->sello;
			$comprobante->appendChild($domAttribute);
		}

	}
?>
