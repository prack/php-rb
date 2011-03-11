<?php

// TODO: Document!
interface Prb_Interface_WritableStreamlike
{
	public function puts();
	public function write( $string );
	public function flush();
}