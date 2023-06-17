<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

// https://electionsapi.cp.org/api/federal2019/Candidates_For_Riding?ridingnumber=167


if( count( $_POST ) )
{
  
  $query = 'INSERT INTO import (
      json
    ) VALUES (
      "'.addslashes( json_encode( $_POST['json'] ) ).'"
    )';
  mysqli_query( $connect, $query );
  
  echo '{"status":"complete"}';
  die(); 
  
}


?>
<!doctype html>
<html>
<head>
  
  <title>SCRAPE - RIDINGS</title>
  
</head>
<body>
  
  <?php 
  
  $ridings = array();
  
  $query = 'SELECT *
    FROM import
    ORDER BY id';
  $result1 = mysqli_query( $connect, $query );
  
  $query = 'SELECT *
    FROM ridings
    ORDER BY officialId';
  $result2 = mysqli_query( $connect, $query );
  
  for( $i = 0; $i < mysqli_num_rows( $result1 ); $i ++ )
  {
    
    $json = mysqli_fetch_assoc( $result1 );
    $json = json_decode( $json['json'], true )[0];
    
    $riding = mysqli_fetch_assoc( $result2 );
    
    echo $json['RidingName_En'].' - '.$riding['nameEnglish'].'<br>';
    
    $ridings[$json['RidingNumber']] = $riding['id'];
    
  }
  
  echo '<hr><hr><hr><hr>';
  
  mysqli_data_seek( $result1, 0 );
  
  for( $i = 0; $i < mysqli_num_rows( $result1 ); $i ++ )
  {
   
    $json = mysqli_fetch_assoc( $result1 ); 
    $json = json_decode( $json['json'], true );

    echo 'JSON Riding Number: '.$json[0]['RidingNumber'].'<br>';
    
    $query = 'SELECT id,first,last
      FROM candidates
      WHERE ridingId = '.$ridings[$json[0]['RidingNumber']].'
      ORDER BY id';
    $result2 = mysqli_query( $connect, $query );
    
    echo mysqli_error( $connect );
    echo $query.'<br>';
    
    echo 'Candidates: '.mysqli_num_rows( $result2 ).'<br>';
    
    $counter = 0;
    
    while( $candidate = mysqli_fetch_assoc( $result2 ) )
    {
      
      if( $candidate['first'] != $json[$counter]['First'] )
      {
        echo '<h1>FAILED</h1>';
      }
      
      echo '<hr>';
      echo $candidate['id'].' - '.$candidate['first'].' '.$candidate['last'].' - '.$json[$counter]['First'].' '.$json[$counter]['Last'].' - '.$json[$counter]['Votes'].'<br>';
      
      $query = 'UPDATE candidates SET
        votes = '.$json[$counter]['Votes'].'
        WHERE id = '.$candidate['id'].'
        LIMIT 1';
      mysqli_query( $connect, $query );
      
      /*
      pre( $query );
      mysqli_error( $connect );
      die();
      */
      
      $counter ++;
      
    }
    
    echo '<hr><hr>';
    
  }
  
  
  
  
  
  
  ?>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

  
  <script>
  
  /*
  var counter = 326;
    
  setInterval(function(){
    
    $.getJSON( "https://cors-anywhere.herokuapp.com/https://electionsapi.cp.org/api/federal2019/Candidates_For_Riding?ridingnumber="+counter, function( data ) {

      counter ++;
      
      $.ajax({
        type: "POST",
        url: '/-scrape-ridings.php',
        data: {"json":data},
        success: function(result){
        
          console.log( result );
          
        }
      });      
      
    });
    
  },2000);
  */
  
  </script>
  
  
</body>
</html>