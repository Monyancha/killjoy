<?php
ob_start();
if (!isset($_SESSION)) {
session_start();
}




function hex2str( $hex ) {
  return pack('H*', $hex);
}

function str2hex( $str ) {
  return array_shift( unpack('H*', $str) );
}

$txt = 'friends@killjoy.co.za';
$hex = str2hex( $txt );
$str = hex2str( $hex );

echo "{$txt} => {$hex} => {$str}\n";

?>


