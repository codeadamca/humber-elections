<?php

header('Access-Control-Allow-Origin: *'); 

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

$query = 'SELECT *
  FROM provinces
  ORDER BY nameEnglish';
$result = mysqli_query( $connect, $query );

$data = array();

while( $record = mysqli_fetch_assoc( $result ) )
{
  
  foreach( $record as $key => $value )
  {
    $record[$key] = htmlentities( $value );
  }
  $data[$record['id']] = $record;
  
}

if( isset( $_GET['sample'] ) )
{
  pre( $data );  
}
else
{

  if( isset( $_GET['callback'] ) ) echo $_GET['callback'].'(';

  echo json_encode( $data );

  if( isset( $_GET['callback'] ) ) echo ')';

}

?>