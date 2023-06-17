<?php

header('Access-Control-Allow-Origin: *'); 

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

$query = 'SELECT team.id,
  team.first,
  team.last,
  team.bio,
  team.programId,
  (
    SELECT name
    FROM teamPrograms
    WHERE team.programId = id
  ) AS programName
  FROM team
  WHERE electionid = '.$_GET['e'].'
  ORDER BY last,first';
$result = mysqli_query( $connect, $query );

$data = array();

while( $record = mysqli_fetch_assoc( $result ) )
{
  
  foreach( $record as $key => $value )
  {
    if( $key != 'bio' ) $record[$key] = htmlentities( $value );
  }
  
  $record['photo'] = 'http://humberelections.professoradamthomas.com/image.php?type=team&id='.$record['id'];
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