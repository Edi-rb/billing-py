<?php 
namespace Classes\Concepto;
/**
*  83111600		000REC , 00REC  recargas de tiempo aire  códido 
*  43191600   	SAT.ventas.codigo < 15  Accesorios
*  43191500  	SAT.ventas.codigo >= 15  Equipos  
*  84111506		GAR001 garantias (Nota de Credito)
*/
class Concepto {
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

	protected $codigo;

	function __construct($xml,$concepto) {

		if( is_null($concepto->clave_producto) ){//si la clave de prodcuto es null desde la base de datos la clase identifica esos valores
			$this->setClaveProdServ($concepto->codigo);
		}
		else{//si no es null, la clave la rescatamos desde la base de datos
			$this->ClaveProdServ = $concepto->clave_producto;//esto es una tonteria pero es para minimizar los cambios al codigo
			if( ! in_array($concepto->unidad, ['ACT', 'E48', 'H87']) ){//si la unidad es diferente de estos valores colocamos
				$this->ClaveUnidad = 'E48';//no supe que poner, le pongo por default E48
				$this->Unidad = 'UNIDAD DE SERVICIO';
			}
			else{
				$this->ClaveUnidad = $concepto->unidad;
				if( $concepto->unidad === 'ACT' )
					$this->Unidad = 'ACTIVIDAD';
				else if( $concepto->unidad === 'E48' )
					$this->Unidad = 'UNIDAD DE SERVICIO';
				else if ( $concepto->unidad === 'H87' )
					$this->Unidad = 'PIEZA';
			}
		}


		$this->setCantidad($concepto->cantidad);
		$this->setDescripcion($concepto->descripcion);
		$concepto->precioun = $concepto->precioun / 1.16;
		$this->setValorUnitario(number_format($concepto->precioun, 6, '.', ''));
		// if( $concepto->codigo !== 'TAE001' )
			$this->setImporte(number_format($concepto->precioun * $this->Cantidad, 6, '.', ''));
		// else
			// $this->setImporte(number_format($concepto->precioun, 6, '.', ''));
		
		$concepto->ClaveProdServ = $this->ClaveProdServ;
		$concepto->ClaveUnidad = $this->ClaveUnidad;
		$concepto->Unidad = $this->Unidad;
		$concepto->Descuento = 0;

		$this->crearNodoConcepto($xml);
	}

	public function getImpImpuesto() {
		return $this->ImpImpuesto;;
	}

	public function getTipoFactor() {
		return $this->TipoFactor;
	}

	public function setClaveProdServ($ClaveProdServ) {
		// $series = array('865424030720847','867224030107344','860697032141964','359560083851022','357501097629980','356268090459101','356453087502903','353072085268631','351913101728896','351913103050406','351913101725587','356268090464143','356454080561201','352605095393062','359591084572879','354454091842899','014898001868505','015226002524993','358337072099413','351913100406726','358606074039258','351913101778503','351913102792594','352156079703900','352156079714071','356268090877526','351913101601085','351913101702545','355315090435374','354454091539420','352156079692558','359591084114391','351551096715989','356453087987047','351913101822566','351913101465176','015226002869521','358404083132524','015226003139163','356454082185686','867264035900103','356453086773042','351812096725604','355535091298808','351551096610693','863249031840589','354454091222514','359591083434907','015226004698225','351913101703121','352156079715185','015226004671230','351913103027800','356453088047296','015226002853905','352156079698373','352156079602367','356453087590809','351527082645745','358977082834331','015088001800802','351913101833324','015226004702944','015226004667923','352897082152680');
		$series = array('','','','','','','','');
		$this->codigo = $ClaveProdServ;
		if ($ClaveProdServ != '') {
			if ($ClaveProdServ == '00REC' || $ClaveProdServ == '000REC' || $ClaveProdServ == 'TAE001' || $ClaveProdServ == 'TAE002' ) {
				$this->ClaveProdServ = '83111504';
				$this->ClaveUnidad = 'E48';
				$this->Unidad = 'UNIDAD DE SERVICIO';
			} else if ($ClaveProdServ == 'REN001') {
				$this->ClaveProdServ = '80131502';
				$this->ClaveUnidad = 'E48';
				$this->Unidad = 'UNIDAD DE SERVICIO';
			} else if ($ClaveProdServ == 'GAR001') {
				$this->ClaveProdServ = '84111506';
				$this->ClaveUnidad = 'ACT';
				$this->Unidad = 'Actividad';
			} else if (in_array($ClaveProdServ, $series)) {
				// $this->ClaveProdServ = '80141623';
				$this->ClaveProdServ = '43191501';
				$this->ClaveUnidad = 'E48';
				$this->Unidad = 'Pieza';
			} else if (strlen ($ClaveProdServ) < 15) {
				$this->ClaveProdServ = '43191500';
			} else if (strlen ($ClaveProdServ) == 15) {
				$this->ClaveProdServ = '43191501';
			} else
				$this->ClaveProdServ = '44111618';
		}

		// para facturas sin imei/serie
		if ($ClaveProdServ == '') {
			$this->ClaveProdServ = '43191501';
			$this->ClaveUnidad = 'E48';
			$this->Unidad = 'Pieza';
		}
	}

	public function getImporte() {
		return $this->Importe;
	}

	public function setCantidad($Cantidad) {
		if ($Cantidad != '') {
			$this->Cantidad = $Cantidad;
		}
	}

	public function setClaveUnidad($ClaveUnidad) {
		if ($ClaveUnidad != '') {
			$this->ClaveUnidad = $ClaveUnidad;
		}
	}

	public function setUnidad($Unidad) {
		if ($Unidad != '') {
			$this->Unidad = $Unidad;
		}
	}

	public function setDescripcion($Descripcion) {
		if ($Descripcion != '') {
			if( $this->codigo !== 'TAE002' )
				$this->Descripcion = htmlspecialchars( $Descripcion );
			else
				$this->Descripcion = 'TAE prepagado';
		}
	}

	public function setValorUnitario($ValorUnitario) {
		if ($ValorUnitario != '') {
			$this->ValorUnitario = $ValorUnitario;
		}
	}

	public function setImporte($Importe) {
		if ($Importe != '') {
			$this->Importe = $Importe;
		}
	}


	public function recolectarImpuesto() {
		return $this->ImpImpuesto > 0;
	}

	public function crearNodoConcepto($xml) {
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
		$domAttribute->value = htmlentities($this->Descripcion);
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

			$this->ImpImpuesto = number_format($this->Importe * (float)$this->impuesto[$this->imp], 6, '.', '');
			$domAttribute = $xml->createAttribute('Importe');
			$domAttribute->value = $this->ImpImpuesto;
			$traslado->appendChild($domAttribute);

			$traslados->appendChild($traslado);
		//}
	}
}
?>