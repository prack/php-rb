<?php

// TODO: Document!
class Prb_IO_Tempfile extends Prb_IO_File
  implements Prb_I_ReadableStreamlike, Prb_I_WritableStreamlike
{
	// TODO: Document!
	static function generatePath( $prefix = 'prb_tmp' )
	{
		return tempnam( sys_get_temp_dir(), $prefix );
	}
	
	// TODO: Document!
	function __construct( $prefix = null )
	{
		parent::__construct( self::generatePath( $prefix ), parent::TRUNCATE_AND_READWRITE );
	}
	
	// TODO: Document!
	public function close( $unlink = false )
	{
		parent::close();
		if ( $unlink )
			unlink( $this->getPath() );
	}
}
