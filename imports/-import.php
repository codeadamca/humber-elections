<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

$query = 'SELECT first,last,ridingId,nameEnglish,partyEnglish
  FROM import';
$result = mysqli_query( $connect, $query );

while( $record = mysqli_fetch_assoc( $result ) )
{
  
  /*
  $query = 'INSERT INTO riding (
      electionId,
      officialId,
      nameEnglish,
      nameFrench,
      coordinates
    ) VALUES (
      "1",
      "'.$record['ridingId'].'",
      "'.$record['nameEnglish'].'",
      "'.$record['nameFrench'].'",
      ""
    )';
  echo $query.'<hr>';
  mysqli_query( $connect, $query );
  */
  
  /*
  $query = 'SELECT id
    FROM party
    WHERE nameEnglish = "'.$record['partyEnglish'].'"
    LIMIT 1';
  $result2 = mysqli_query( $connect, $query );
  $record2 = mysqli_fetch_assoc( $result2 );
  
  $query = 'SELECT id
    FROM riding
    WHERE officialId = "'.$record['ridingId'].'"
    LIMIT 1';
  $result3 = mysqli_query( $connect, $query );
  $record3 = mysqli_fetch_assoc( $result3 );
  
  $query = 'INSERT INTO candidate (
      ridingId,
      partyId,
      first,
      last,
      photo
    ) VALUES (
      "'.$record3['id'].'",
      "'.$record2['id'].'",
      "'.$record['first'].'",
      "'.$record['last'].'",
      ""
    )';
  mysqli_query( $connect, $query );
  */
  
}
  
