<?php

// TODO: Document!
interface Prb_I_Logger
{
	public function debug  ( $progname = null, $callback = null );
	public function info   ( $progname = null, $callback = null );
	public function warn   ( $progname = null, $callback = null );
	public function error  ( $progname = null, $callback = null );
	public function fatal  ( $progname = null, $callback = null );
	public function unknown( $progname = null, $callback = null );
	
	public function add( $severity, $progname = null, $message = null, $callback = null );
	
	public function close();
}