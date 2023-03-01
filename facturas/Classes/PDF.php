<?php
	namespace Classes;
	use fpdf\FPDF;
	require_once 'NumberToLetter.php';
	use phpqrcode\QRcode;

	error_reporting(E_ALL);
	ini_set('display_errors', 0);
	ini_set('log_errors', 1);
	const PATH = __DIR__.'/logs';
	if( ! file_exists(PATH) )
	  mkdir(PATH);
	ini_set('error_log', PATH . '\/'. date('Y-m-d') . '.log');

	class PDF extends FPDF {
		protected $nomEmpresa;
		protected $dirEmpresa;
		protected $rfcEmpresa;
		protected $nomSucursal;
		protected $dirSucursal;
		protected $nombre;
		protected $rfc;
		protected $usoCFDI;
		protected $moneda;
		protected $formaPago;
		protected $metodoPago;
		protected $folioFiscal;
		protected $numSerieSAT;
		protected $numSerieCSD;
		protected $fechaCer;
		protected $fechaCFDI;
		protected $efectoComprobante;
		protected $regimenFiscal;
		protected $folio;
		protected $serie;
		protected $selloCFDI;
		protected $selloSAT;
		protected $cadOriginalSAT;
		protected $conceptos;
		protected $recibe;
		protected $Rcp;
		protected $dirReceptor;
		protected $meta;
		protected $dbapi;
		protected $flagVale;

		public function constructor($nomEmpresa,$dirEmpresa,$rfcEmpresa,$nomSucursal,$dirSucursal,$nombre,$rfc,$cp,$direccion,$recibe,$usoCFDI,$formaPago,$metodoPago,$folioFiscal,$numSerieSAT,$numSerieCSD,$fechaCer,$fechaCFDI,$efectoComprobante,$regimenFiscal,$folio,$serie,$selloCFDI,$selloSAT,$cadOriginalSAT,$conceptos, $dbapi, $meta, $uuidRel) {
			$this->nomEmpresa = utf8_decode($nomEmpresa);
			$this->dirEmpresa = utf8_decode($dirEmpresa);
			$this->recibe = utf8_decode($recibe);
			$this->rfcEmpresa = $rfcEmpresa;
			$this->nomSucursal = utf8_decode($nomSucursal);
			$this->dirSucursal = utf8_decode($dirSucursal);
			$this->nombre = utf8_decode($nombre);
			$this->rfc = $rfc;
			$this->usoCFDI = utf8_decode($usoCFDI);
			$this->moneda = 'MXN Peso Mexicano';
			$this->formaPago = utf8_decode($formaPago);
			$this->metodoPago = utf8_decode($metodoPago);
			$this->folioFiscal = $folioFiscal;
			$this->numSerieSAT = $numSerieSAT;
			$this->numSerieCSD = $numSerieCSD;
			$this->fechaCer = $fechaCer;
			$this->fechaCFDI = $fechaCFDI;
			$this->efectoComprobante = utf8_decode($efectoComprobante);
			$this->regimenFiscal = utf8_decode($regimenFiscal);
			$this->folio = $folio;
			$this->serie = $serie;
			$this->selloCFDI = $selloCFDI;
			$this->selloSAT = $selloSAT;
			$this->cadOriginalSAT = $cadOriginalSAT;
			$this->conceptos = $conceptos;
			$this->Rcp = $cp;
			$this->dirReceptor = utf8_decode($direccion);
			$this->dbapi = $dbapi;
			$this->meta = $meta;
			$this->flagVale = false;
			$this->uuidRel = $uuidRel or '';
			$this->creaPDF();
		}

	    public function cab() {
	        $this->Image('img/factura_v33.jpg',8,5,200);
			$this->SetFont('arial-narrow-bold','',10);
			$this->SetXY(52,12);
	        $this->MultiCell(70,4,$this->nomEmpresa,0,'L');
			$this->SetXY(52,23);
	        $this->MultiCell(70,0,$this->rfcEmpresa,0,'L');
	        $this->SetXY(52,26);
		   	$this->MultiCell(70,4,$this->dirEmpresa,0,'L');
			$this->SetFont('arial-narrow','',8);
			$this->SetXY(70,38.6);
		   	$this->MultiCell(52,3,$this->nomSucursal,0,'C');
		   	$this->SetXY(70,45.2);
		   	$this->MultiCell(52,3,$this->dirSucursal,0,'C');	        
	        $this->SetXY(140,12.2);
			$this->MultiCell(65,0,$this->folioFiscal,0,'R');
	        $this->SetXY(162,18);
			$this->MultiCell(43,0,$this->numSerieSAT,0,'R');
			$this->SetXY(162,23.8);
	        $this->MultiCell(43,0,$this->numSerieCSD,0,'R');
			$this->SetXY(158,29.4);
	        $this->MultiCell(47,0,$this->fechaCer,0,'R');
			$this->SetXY(161,35.2);
	        $this->MultiCell(44,0,$this->fechaCFDI,0,'R');
			$this->SetXY(153,41);
	        $this->MultiCell(52,0,$this->efectoComprobante,0,'R');
			$this->SetXY(143,46.4);
	        $this->MultiCell(62,0,$this->regimenFiscal,0,'R');
	        $this->Text(135,52.8,$this->folio);
	        $this->Text(172,52.8,$this->serie);
	        $this->Text(24,59.8,$this->nombre);
	        $this->Text(24,64.1,$this->rfc);
	        $this->Text(24,68.5,$this->usoCFDI);
	       	$this->Text(24,72.5,$this->Rcp);
	        $this->Text(164,62,$this->moneda);
	        $this->Text(164,66.6,$this->formaPago);
	        $this->Text(164,71.5,$this->metodoPago);
	        if ($this->efectoComprobante == 'I Ingreso') {
		        $this->SetFont('arial-narrow-bold','',9);
	        	$this->SetXY(127,8);
	        	$this->MultiCell(77,0,'FACTURA',0,'C');
	        	$this->SetFont('arial-narrow-bold-italic','',7);
	        	$this->Text(62,64.5,utf8_decode('Observación'));
	        	$this->SetFont('arial-narrow','',7);
		        $this->SetXY(62,65.5);
		        if($this->rfc =='XAXX010101000') {
		        	$this->MultiCell(74,2.8,'Recibe:     '.$this->recibe."\n".utf8_decode('Dirección: ').$this->dirReceptor,0,'L');
		        } else {
		        	$this->MultiCell(74,2.8,utf8_decode('Dirección: ').$this->dirReceptor,0,'L');
		        }
	        } else {
	        	$this->SetFont('arial-narrow-bold','',9);
	        	$this->SetXY(127,8);
	        	$this->MultiCell(77,0,utf8_decode('NOTA DE CRÉDITO'),0,'C');
	        }

	        if( $this->uuidRel ){
	        	$this->SetFont('arial-narrow','',7);
	        	$this->SetXY(62,58.5);
		        $this->MultiCell(74,2.8,utf8_decode('Tipo De Relación: Sustitución de los CFDI previos'),0,'L');
	        	$this->SetXY(62,60.6);
		        $this->MultiCell(74,2.8,utf8_decode('UUID Relacionado: ').$this->uuidRel,0,'L');
	        }

	    }

	    public function pie() {
			$this->SetFont('arial-narrow','',7);
	        $this->SetXY(8.4,221.2);
			$this->MultiCell(140.6,2.6,$this->selloSAT,0);
			$this->SetXY(8.4,237);
	        $this->MultiCell(140.6,2.6,$this->selloCFDI,0);
			$this->SetXY(8.4,253);
			$this->MultiCell(140.6,2.6,$this->cadOriginalSAT,0);
	        $this->Text(175,271,'Pag. '.$this->PageNo().'/{nb}');
	        $this->Image('img/logo_RedesSociales.png',162,258,35);
	    }

	    public function creaHoja () {
	    	if($this->GetY() > 190) {
				$this->AddPage();
				$this->cab();
				$this->pie();
				$this->SetXY(64,89);
				$this->SetFont('arial-narrow','',9);
			}
	    }

	    public function impTabla ($datos,$pos,$tmp) {
	    	if ($pos > 190) {
	    		$this->AddPage();
				$this->cab();
				$this->pie();
				$this->SetXY(64,89);
				$this->SetFont('arial-narrow','',9);
	    		$pos = 89;
	    	}
	    	$valorU = number_format($datos['valorU'],2,'.',',');
	    // if( trim($datos['codigo']) !== 'TAE001' )
				$importe = number_format(round($datos['cantidad']*$datos['valorU'],2),2,'.',',');
			// else
				// $importe = number_format(round($datos['valorU'],2),2,'.',',');
			
			$descuento = number_format($datos['descuento'],2,'.',',');
			$ivaImporte = number_format(round($datos['cantidad']*$datos['valorU']*0.16,2),2,'.',',');
			//Clave Producto/Servicio
			$this->SetXY(8,$pos);
			$this->MultiCell(16,0,$datos['clavePS'],0,'C');
			//No. Ident
			$this->SetXY(24,$pos);
			$this->MultiCell(10,0,'',0,'C');
			//Cantidad
			$this->SetXY(32.8,$pos);
			$this->MultiCell(10,0,$datos['cantidad'],0,'C');
			//Clave Unidad
			$this->SetXY(42.2,$pos);
			$this->MultiCell(11,0,$datos['claveU'],0,'C');
			//Unidad
			$this->SetFont('arial-narrow','',7);
			$this->SetXY(52.8,$pos-1);
			$this->MultiCell(12,2.5, trim($datos['codigo']) !== 'TAE002' ? $datos['unidad'] : 'SRV' ,0,'C');
			// $this->MultiCell(12,2.5, $datos['unidad'] ,0,'C');
			$this->SetFont('arial-narrow','',9);				
			//Valor Unitario
			$this->SetXY(155,$pos);
			$this->MultiCell(18,0,$valorU,0,'R');
			//Importe
			$this->SetXY(172.4,$pos);
			$this->MultiCell(18,0,$importe,0,'R');
			//Descuento
			$this->SetXY(189.6,$pos);
			if ($datos['descuento'] == 0) {
				$this->MultiCell(18,0,'',0,'R');
			}
			else {
				$this->MultiCell(18,0,$descuento,0,'R');
			}

			$this->SetXY(64,$pos);
			if( trim($datos['codigo']) !== 'TAE002' )
				$this->MultiCell(92,0, $datos['descripcion'] ,0,'L');
			else
				$this->MultiCell(92,4, "TAE Prepago \n Clave Prod o Serv: {$datos['clavePS']} Servicios de tarjetas telefonicas prepagadas CLAVE U.M. {$datos['claveU']} \n Pzas Recarga: {$datos['cantidad']}, Total de Pzas Factura: {$datos['cantidad']}" ,0,'L');
			$this->Ln(2);
			$this->SetX(64);
			$cad = explode(' ', $datos['codigo'], -1);
			$i = 0;
	    	foreach ($cad as $key) {
				if ($this->GetX() >= 140) {
					$this->Ln();
					$this->SetX(64);
				}
				$this->creaHoja();
				$this->Write(3.5,$key.'  ');
				$i++;
				if ($i == count($cad)) {
					$this->Ln();
					$this->creaHoja();
					$y = $this->GetY();
					$this->SetFont('arial-narrow-bold','',8);
					$this->SetXY(64,$y);
					$this->MultiCell(18.4,3,'Base'."\n".$importe,0,'C');
					$this->SetXY(82.4,$y);
					$this->MultiCell(18.4,3,'Impuesto'."\n".'IVA',0,'C');
					$this->SetXY(100.8,$y);
					$this->MultiCell(18.4,3,'Tipofactor'."\n".'Tasa',0,'C');
					$this->SetXY(119.2,$y);
					$this->MultiCell(18.4,3,'Tasa o Cuota'."\n".'0.160000',0,'C');
					$this->SetXY(137.6,$y);
					$this->MultiCell(18.4,3,'Importe'."\n".$ivaImporte,0,'C');
					$this->SetX(64);
					$this->SetFont('arial-narrow','',9);
				}
			}
			if ($tmp != count($this->conceptos)-1) {
				$this->Ln();
				$this->creaHoja();
			}
	    }

		public function creaPDF() {
			$this->AliasNbPages();
			$this->AddPage();
			$this->AddFont('arial-narrow','','arial-narrow.php');
			$this->AddFont('arial-narrow-bold','','arial-narrow-bold.php');
			$this->AddFont('arial-narrow-bold-italic','','arial-narrow-bold-italic.php');
			$this->cab();
			$this->pie();
			$this->SetFont('arial-narrow','',9);

			$posY = 89;
			$subtotal = 0;
			$descTotal = 0;
			$tmp = 0;
			$canTotal = 0;
			$cadCodigo = '';

			foreach ($this->conceptos as $value) {
				// if( trim($value->codigo) !== 'TAE002' )
					$subtotal += $value->cantidad*$value->precioun;
				// else
					// $subtotal += $value->precioun;

				$descTotal += $value->Descuento;

				//comprobar si es kit y vale mas de 999
				/*
				$vale_serie = $value->codigo;
				echo "Serie No " . $vale_serie;
				
				if( strlen( $vale_serie ) == 15 ){
					$sql = "SELECT m.tipo, m.precio from activa.kits k left join activa.marcas m on k.marca = m.marca and k.modelo = m.modelo where k.serie = '$vale_serie'";
					$r = $this->dbapi->exec($sql, $this->meta);
					if( $r ){
						$tipo = $r[0]->tipo;
						$precio = $r[0]->precio;
						if( strtolower($tipo) == 'k' ){
							if( $precio >= '999' ){
								//si cumple enteonce cambiamos la bandera
								$this->flagVale = true;
							}
						}
					}
				}
				*/
				//aqui acaba mi proceso
				
				if ($tmp == 0) {
					$base = $value->descripcion;
					$pre = $value->precioun;
				}
				if ($base == $value->descripcion && $pre == $value->precioun) {
					$cadCodigo .= $value->codigo.' ';
					$canTotal += $value->cantidad;
					$datos = array("clavePS" => $value->ClaveProdServ, "claveU" => $value->ClaveUnidad, "unidad" => $value->Unidad, "valorU" => $value->precioun, "descuento" => $value->Descuento, "cantidad" => $canTotal, "descripcion" => $value->descripcion, "codigo" => $cadCodigo);
					if ($tmp == count($this->conceptos)-1) {
						$datos = array("clavePS" => $value->ClaveProdServ, "claveU" => $value->ClaveUnidad, "unidad" => $value->Unidad, "valorU" => $value->precioun, "descuento" => $value->Descuento, "cantidad" => $canTotal, "descripcion" => $value->descripcion, "codigo" => $cadCodigo);
						$this->impTabla($datos,$posY,$tmp);
					}
				}
				else {
					$this->impTabla($datos,$posY,$tmp);
					if ($this->GetY() != 89) {
						$posY = $this->GetY()+1;
					}
					else {
						$posY = 89;
					}
					$base = $value->descripcion;
					$pre = $value->precioun;
					$canTotal = $value->cantidad;
					$cadCodigo = $value->codigo.' ';
					$datos = array("clavePS" => $value->ClaveProdServ, "claveU" => $value->ClaveUnidad, "unidad" => $value->Unidad, "valorU" => $value->precioun, "descuento" => $value->Descuento, "cantidad" => $canTotal, "descripcion" => $value->descripcion, "codigo" => $cadCodigo);
					if ($tmp == count($this->conceptos)-1) {
						$posY += 2.5;
						$datos = array("clavePS" => $value->ClaveProdServ, "claveU" => $value->ClaveUnidad, "unidad" => $value->Unidad, "valorU" => $value->precioun, "descuento" => $value->Descuento, "cantidad" => $canTotal, "descripcion" => $value->descripcion, "codigo" => $cadCodigo);
						$this->impTabla($datos,$posY,$tmp);
					}
				}
				$tmp++;
			}

			$this->SetFont('arial-narrow','',7);
			//Subtotal
			$this->SetXY(175,203.4);
			$this->MultiCell(32,0,'$ '.number_format($subtotal,2,'.',','),0,'R');
			//IVA
			$iva = round($subtotal*0.16,2);
			$this->SetXY(175,210.4);
			$this->MultiCell(32,0,'$ '.number_format($iva,2,'.',','),0,'R');
			//Total
			$this->SetXY(175,214.4);
			$total = round($subtotal+$iva-$descTotal,2);
			$this->MultiCell(32,0,'$ '.number_format($total,2,'.',','),0,'R');
			$this->SetXY(10,207);
			$this->MultiCell(137,3,numtoletras($total),0);
			$fe = substr($this->selloCFDI,-8);
			QRcode::png('https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?'.'re='.$this->rfcEmpresa.'&rr='.$this->rfc.'&tt='.$total.'&id='.$this->folioFiscal.'&fe='.$fe,'img/'.$this->folio.'.png');
			$this->Image('img/'.$this->folio.'.png',162,226,35);
			/*	
			if( $this->flagVale ):
				//si tiene todo para sacar el vale generamos un consecutivo
				$r = $this->dbapi->exec("SELECT folio FROM tramites.folios WHERE tipo = 'vales'", $this->meta);
				if( $r[0]->folio < '800' ):
					$r = $this->dbapi->exec("CALL tramites.folio('vales')", $this->meta);
					if( $r[0]->folio <= '800' ):
						//si el consecutivo esta dentro del rango permitido que es 800 generamos el vale
						//agregar una nueva hoja
						$consecutivo = str_pad($r[0]->folio, 3, "0", STR_PAD_LEFT);
						$this->AddPage();
						$this->Image('img/vales.png',8,5,200);
						$this->SetFont('arial-narrow','',8);
						$this->SetXY(73, 19.5);
						$this->MultiCell(30, 4,'MCR161118-200T-0'.$consecutivo ,0);
						$this->SetXY(175, 19.5);
						$this->MultiCell(30, 4, 'MCR161118-200T-0'.$consecutivo ,0);
					endif;
				endif;
			endif;
			//fin del vale
			*/

			$this->Output('Files/PDF/'.$this->folio.'.pdf','F');
		}
	}
?>