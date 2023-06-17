<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

$file = fopen( 'imports/Candidates.csv', 'r' );

$province = array(
  10 => 'Newfoundland and Labrador',
  11 => 'Prince Edward Island',
  12 => 'Nova Scotia',
  13 => 'New Brunswick',
  24 => 'Quebec',
  35 => 'Ontario',
  47 => 'Saskatchewan',
  46 => 'Manitoba',
  48 => 'Alberta',
  59 => 'British Columbia',
  60 => 'Yukon',
  61 => 'Northwest Territories',
  62 => 'Nunavut'
);


$parties = array();

/*
$query = 'SELECT id,nameEnglish
  FROM parties';
$result = mysqli_query( $connect, $query );

while( $record = mysqli_fetch_assoc( $result ) )
{
  $parties[$record['id']] = utf8_encode( $record['nameEnglish'] );
}
*/

$ridings = array();

/*
$query = 'SELECT id,nameEnglish
  FROM ridings';
$result = mysqli_query( $connect, $query );

while( $record = mysqli_fetch_assoc( $result ) )
{
  $ridings[$record['id']] = utf8_encode( $record['nameEnglish'] );
}
*/



$query = 'DELETE FROM parties';
mysqli_query( $connect, $query );

$query = 'DELETE FROM ridings';
mysqli_query( $connect, $query );

$query = 'DELETE FROM candidates';
mysqli_query( $connect, $query );


while( !feof( $file ) )
{
  
  $record = fgetcsv( $file );  
  
  echo '<pre>';
  print_r( $record );
  echo '</pre>';

  /**/
  // Parties
  $query = 'SELECT *
    FROM parties
    WHERE nameEnglish = "'.mysqli_real_escape_string( $connect, utf8_encode( $record[3] ) ).'"';;
  $result = mysqli_query( $connect, $query );
  
  echo 'Party Rows: '.mysqli_num_rows( $result ).'<br>';
  
  if( !mysqli_num_rows( $result ) )
  {
    
    $query = 'INSERT INTO parties (
        electionId,
        nameEnglish,
        nameFrench
      ) VALUES (
        1,
        "'.mysqli_real_escape_string( $connect, utf8_encode( $record[3] ) ).'",
        "'.mysqli_real_escape_string( $connect, utf8_encode( $record[4] ) ).'"
      )';
    mysqli_query( $connect, $query );
    
    echo mysqli_error( $connect );
    
    $parties[$record[3]] = mysqli_insert_id( $connect );
    
  }
  /**/
  
  /**/
  // Ridings  
  $query = 'SELECT *
    FROM ridings
    WHERE nameEnglish = "'.mysqli_real_escape_string( $connect, utf8_encode( $record[1] ) ).'"';;
  $result = mysqli_query( $connect, $query );
  
  echo 'Riding Rows: '.mysqli_num_rows( $result ).'<br>';
  
  if( !mysqli_num_rows( $result ) )
  {
    
    $query = 'INSERT INTO ridings (
        electionId,
        officialId,
        nameEnglish,
        nameFrench,
        provinceId
      ) VALUES (
        1,
        '.$record[0].',
        "'.mysqli_real_escape_string( $connect, utf8_encode( $record[1] ) ).'",
        "'.mysqli_real_escape_string( $connect, utf8_encode( $record[2] ) ).'",
        (
          SELECT provinces.id
          FROM provinces
          WHERE provinces.nameEnglish = "'.$province[substr( utf8_encode( $record[0] ), 0, 2 )].'"
        )
      )';
    mysqli_query( $connect, $query );
    
    echo 'Province: '.$record[0].'<br>';
    echo mysqli_error( $connect );
    
    $ridings[$record[1]] = mysqli_insert_id( $connect );
    
  }
  /**/
  
  /**
  $query = 'SELECT ridings.id
    FROM ridings
    WHERE ridings.nameEnglish = "'.mysqli_real_escape_string( $connect, utf8_encode( $record[1] ) ).'"
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  $riding = mysqli_fetch_assoc( $result );
  
  echo '<pre>';
  print_r( $riding );
  echo '</pre>';
  
  echo mysqli_error( $connect ).'<br>';
  echo $query.'<br>';
  
  $query = 'SELECT parties.id
    FROM parties
    WHERE parties.nameEnglish = "'.mysqli_real_escape_string( $connect, utf8_encode( $record[3] ) ).'"
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  $party = mysqli_fetch_assoc( $result );
  
  echo '<pre>';
  print_r( $party );
  echo '</pre>';
  
  echo mysqli_error( $connect ).'<br>';
  echo $query.'<br>';
  /**/

  echo $parties[$record[3]] ? $parties[$record[3]] : 'MISSING';
  echo '<br>';
  echo $ridings[$record[1]] ? $ridings[$record[1]] : 'MISSING';
  
  /**/
  $query = 'INSERT INTO candidates (
      electionId,
      first,
      last,
      ridingId,
      partyId,
      photo
    ) VALUES (
      1,
      "'.mysqli_real_escape_string( $connect, utf8_encode( $record[5] ) ).'",
      "'.mysqli_real_escape_string( $connect, utf8_encode( $record[6] ) ).'",
      '.$ridings[$record[1]].',
      '.$parties[$record[3]].',
      ""
    )';
  mysqli_query( $connect, $query );
  
  echo mysqli_error( $connect ).'<br>';
  
  echo $query;
  /**/
  
  echo '<hr>';
  
}

pre( $parties );
pre( $ridings );

die();

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
  
