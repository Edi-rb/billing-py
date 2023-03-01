<?php 
/**
* 
*/
class Retenciones
{
	protected $tasaOCuota;
	protected $base;
	protected $TipoFactor;
	protected $impuesto;
	protected $importe;


	function __construct(argument)
	{
		# code...
	}

	public function get$tasaOCuota($tasaOCuota)
	{
		if ($tasaOCuota == '') {
			$this->tasaOCuota = $tasaOCuota;
		}
	}
	public function get$base($base)
	{
		if ($base == '') {
			$this->base = $base;
		}
	}
	public function get$TipoFactor($TipoFactor)
	{
		if ($TipoFactor == '') {
			$this->TipoFactor = $TipoFactor;
		}
	}
	public function get$impuesto($impuesto)
	{
		if ($impuesto == '') {
			$this->impuesto = $impuesto;
		}
	}
	public function get$importe($importe)
	{
		if ($importe == '') {
			$this->importe = $importe;
		}
	}




}
 ?>