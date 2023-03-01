<?php 
/**
* 
*/
class Traslados
{
	protected $TasaOCuota;
	protected $base;
	protected $TipoFactor;
	protected $impuesto;
	protected $importe;


	function __construct(argument)
	{
		# code...
	}

	public function gettasaOCuota($tasaOCuota)
	{
		if ($tasaOCuota == '') {
			$this->tasaOCuota = $tasaOCuota;
		}
	}
	public function getbase($base)
	{
		if ($base == '') {
			$this->base = $base;
		}
	}
	public function getTipoFactor($TipoFactor)
	{
		if ($TipoFactor == '') {
			$this->TipoFactor = $TipoFactor;
		}
	}
	public function getimpuesto($impuesto)
	{
		if ($impuesto == '') {
			$this->impuesto = $impuesto;
		}
	}
	public function getimporte($importe)
	{
		if ($importe == '') {
			$this->importe = $importe;
		}
	}
}
?>