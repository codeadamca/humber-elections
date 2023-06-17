<?php

header('Access-Control-Allow-Origin: *'); 

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

$query = 'SELECT candidates.id,
  candidates.first,
  candidates.last,
  candidates.votes,
  candidates.ridingId,
  candidates.partyId,
  (
    SELECT nameEnglish
    FROM ridings
    WHERE candidates.ridingId = id
  ) AS ridingName,
  (
    SELECT nameEnglish
    FROM parties
    WHERE candidates.partyId = id
  ) AS partyName
  FROM candidates
  WHERE electionid = '.$_GET['e'].'
  ORDER BY last,first';
$result = mysqli_query( $connect, $query );

$data = array();

while( $record = mysqli_fetch_assoc( $result ) )
{
  
  foreach( $record as $key => $value )
  {
    $record[$key] = htmlentities( $value );
  }
  $record['photo']  = 'http://humberelections.professoradamthomas.com/image.php?type=candidate&id='.$record['id'];
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

};

?>