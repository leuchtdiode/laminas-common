<?php
namespace Common\Db\Filter\Distance;

class FilterParams
{
	private string        $type;
	private ColumnOrValue $sourceLatitude;
	private ColumnOrValue $sourceLongitude;
	private ColumnOrValue $destinationLatitude;
	private ColumnOrValue $destinationLongitude;
	private float         $kilometers;

	public static function create(): FilterParams
	{
		return new static();
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function setType(string $type): FilterParams
	{
		$this->type = $type;
		return $this;
	}

	public function getSourceLatitude(): ColumnOrValue
	{
		return $this->sourceLatitude;
	}

	public function setSourceLatitude(ColumnOrValue $sourceLatitude): FilterParams
	{
		$this->sourceLatitude = $sourceLatitude;
		return $this;
	}

	public function getSourceLongitude(): ColumnOrValue
	{
		return $this->sourceLongitude;
	}

	public function setSourceLongitude(ColumnOrValue $sourceLongitude): FilterParams
	{
		$this->sourceLongitude = $sourceLongitude;
		return $this;
	}

	public function getDestinationLatitude(): ColumnOrValue
	{
		return $this->destinationLatitude;
	}

	public function setDestinationLatitude(ColumnOrValue $destinationLatitude): FilterParams
	{
		$this->destinationLatitude = $destinationLatitude;
		return $this;
	}

	public function getDestinationLongitude(): ColumnOrValue
	{
		return $this->destinationLongitude;
	}

	public function setDestinationLongitude(ColumnOrValue $destinationLongitude): FilterParams
	{
		$this->destinationLongitude = $destinationLongitude;
		return $this;
	}

	public function getKilometers(): float
	{
		return $this->kilometers;
	}

	public function setKilometers(float $kilometers): FilterParams
	{
		$this->kilometers = $kilometers;
		return $this;
	}
}