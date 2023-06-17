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
  candidates.partyId
  FROM candidates
  ORDER BY last,first';
$result = mysqli_query( $connect, $query );


$ridings = array();
while( $record = mysqli_fetch_assoc( $result ) )
{
  
  if( !isset( $ridings[$record['ridingId']] ) )
  {
    $ridings[$record['ridingId']]['votes'] = $record['votes'];
    $ridings[$record['ridingId']]['partyId'] = $record['partyId'];
  }
  elseif( $ridings[$record['ridingId']]['votes'] < $record['votes'] )
  { 
    $ridings[$record['ridingId']]['votes'] = $record['votes'];
    $ridings[$record['ridingId']]['partyId'] = $record['partyId'];
  }
  elseif( $ridings[$record['ridingId']]['votes'] == $record['votes'] )
  {
    $ridings[$record['ridingId']]['votes'] = $record['votes'];
    $ridings[$record['ridingId']]['partyId'] = 0;
  }
  
}

$query = 'SELECT parties.*,
  candidates.first,
  candidates.last,
  candidates.photo
  FROM parties
  LEFT JOIN candidates
  ON parties.candidateId = candidates.id
  WHERE parties.electionid = '.$_GET['e'].'
  ORDER BY nameEnglish ASC';
$result = mysqli_query( $connect, $query );

echo mysqli_error( $connect );

$data = array();

while( $record = mysqli_fetch_assoc( $result ) )
{
  
  foreach( $record as $key => $value )
  {
    $record[$key] = htmlentities( $value );
  }
  
  $win = 0;
  
  foreach( $ridings as $key => $value )
  {
    if( $value['partyId'] == $record['id'] )
    {
      $win ++;
    }
  }
  
  $record['photo']  = 'http://humberelections.professoradamthomas.com/image.php?type=candidate&id='.$record['id'];
  
  $record['ridingsWinning'] = $win;
  $record['ridingspercent'] = round( $win / count( $ridings ) * 100, 5 );
  
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