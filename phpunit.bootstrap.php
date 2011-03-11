<?php

error_reporting( E_ALL | E_STRICT );
date_default_timezone_set( 'UTC' );

$pwd = dirname( __FILE__ );

require_once join( DIRECTORY_SEPARATOR, array( $pwd, 'autoload.php'       ) );
require_once join( DIRECTORY_SEPARATOR, array( $pwd, 'test', 'helper.php' ) );