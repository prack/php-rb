<?php

class Prb_Numeric
  implements Prb_I_Comparable
{
	protected $numeric;
	
	// TODO: Document!
	static function with( $numeric )
	{
		return new Prb_Numeric( $numeric );
	}
	
	// TODO: Document!
	public function __construct( $numeric )
	{
		if ( !is_numeric( $numeric ) )
			throw new Prb_Exception_Type( 'FAILSAFE: __construct $numeric is not numeric' );
		
		$this->numeric = $numeric;
	}
	
	// TODO: Document!
	public function raw()
	{
		return $this->numeric;
	}
	
	// TODO: Document!
	public function toN()
	{
		return clone $this;
	}
	
	// TODO: Document!
	public function toS()
	{
		return Prb::Str( (string)$this->numeric );
	}
	
	// TODO: Document!
	public function truth( $value = '' )
	{
		return (bool)$this->numeric;
	}
	
	// TODO: Document!
	public function compare( $other_num )
	{
		return ( $this->numeric - $other_num->raw() );
	}
}