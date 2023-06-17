<?php

header('Access-Control-Allow-Origin: *'); 

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

$query = 'SELECT *
  FROM provinces
  ORDER BY nameEnglish';
$result = mysqli_query( $connect, $query );

$provinces = array();

while( $record = mysqli_fetch_assoc( $result ) )
{
  foreach( $record as $key => $value )
  {
    $record[$key] = htmlentities( $value );
  }
  $provinces[$record['id']] = $record;
}

$query = 'SELECT *
  FROM parties
  WHERE electionid = '.$_GET['e'].'
  ORDER BY nameEnglish';
$result = mysqli_query( $connect, $query );

$parties = array();

while( $record = mysqli_fetch_assoc( $result ) )
{
  foreach( $record as $key => $value )
  {
    $record[$key] = htmlentities( $value );
  }
  $parties[$record['id']] = $record;
}

$query = 'SELECT candidates.id,
  candidates.first,
  candidates.last,
  candidates.votes,
  candidates.ridingId,
  candidates.partyId
  FROM candidates
  WHERE electionid = '.$_GET['e'].'
  ORDER BY last,first';
$result = mysqli_query( $connect, $query );

$candidates = array();

while( $record = mysqli_fetch_assoc( $result ) )
{
  foreach( $record as $key => $value )
  {
    $record[$key] = htmlentities( $value );
  }
  $record['party'] = $parties[$record['partyId']];
  $record['photo']  = 'http://humberelections.professoradamthomas.com/image.php?type=candidate&id='.$record['id'];
  $candidates[$record['ridingId']][$record['id']] = $record;
}

$query = 'SELECT *
  FROM ridings
  WHERE electionid = '.$_GET['e'].'
  ORDER BY officialId ASC';
$result = mysqli_query( $connect, $query );

$data = array();

while( $record = mysqli_fetch_assoc( $result ) )
{
  
  foreach( $record as $key => $value )
  {
    $record[$key] = htmlentities( $value );
  }
  $record['province'] = $provinces[$record['provinceId']];
  $record['candidates'] = $candidates[$record['id']];
  
  foreach( $record['candidates'] as $key => $value )
  {
    
    if( !isset( $record['winningVotes'] ) )
    {
      $record['winningVotes'] = $value['votes'];
      $record['winningCandidateId'] = $value['id'];
      $record['winningColour'] = $parties[$value['partyId']]['colour'];
    }
    elseif( $record['winningVotes'] < $value['votes'] )
    { 
      $record['winningVotes'] = $value['votes'];
      $record['winningCandidateId'] = $value['id'];
      $record['winningColour'] = $parties[$value['partyId']]['colour'];
    }
    elseif( $record['winningVotes'] == $value['votes'] )
    {
      $record['winningVotes'] = $value['votes'];
      $record['winningCandidateId'] = 0;
      $record['winningColour'] = "";
    }
    
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