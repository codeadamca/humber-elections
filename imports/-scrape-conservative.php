<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

?>

<!doctype html>
<html>
<head>
  <title>SCRAPE CONSERVATIVES</title>
</head>
<body>
  
<script>

var images = new Array();
  
</script>

<?php
  
// $query = 'UPDATE candidates SET photo = "" WHERE partyId = 2258';
// mysqli_query( $connect, $query );
  
$query = 'UPDATE candidates SET photo = "" WHERE photo LIKE "%text/xml%"';
mysqli_query( $connect, $query );

$url = '-scrape-conservative.html';
$html = file_get_contents( $url );
$html = preg_replace('/\s+/', ' ', $html );

$start = 0;

$counter = 0;

$images = array();

while( $next = strpos( $html, 'cabinet-avatar', $start ) and $counter < 500 )
{

  $firstQuote = $next + 27;
  $secondQuote = strpos( $html, '"', $next + 28 );

  $image =  trim( substr( $html, $firstQuote, $secondQuote - $firstQuote ) );
  
  if( strpos( $image, 'data-img-src' ) )
  {
    $firstQuote = $next + 66;
    $secondQuote = strpos( $html, '"', $next + 67 );

    $image =  trim( substr( $html, $firstQuote, $secondQuote - $firstQuote ) );
  }

  $start = $next +22; 
  
  $firstH2 = strpos( $html, '<h3>', $start ) + 4;
  $secondH2 = strpos( $html, '<', $firstH2 + 1 );
  
  $name = explode( ' ', trim( substr( $html, $firstH2, $secondH2 - $firstH2 ) ) );
  
  $query = 'SELECT id,photo
    FROM candidates
    WHERE first = "'.$name[0].'"
    AND last = "'.$name[1].'"
    LIMIT 1';
  $result = mysqli_query( $connect, $query );

  if( mysqli_num_rows( $result ) == 0 )
  {
    echo $query.'<br>';
    
    $query = 'SELECT id,photo
      FROM candidates
      WHERE first LIKE "%'.$name[0].'%"
      AND last LIKE "%'.$name[1].'%"
      LIMIT 1';
    $result = mysqli_query( $connect, $query );
    
    echo 'USING LIKE<br>';
  }
  
  if( mysqli_num_rows( $result ) == 0 )
  {
    echo $query.'<br>';
    
    $query = 'SELECT id,photo
      FROM candidates
      WHERE first LIKE "%'.$name[0].'%"
      AND partyId = 2258
      AND photo = ""
      LIMIT 1';
    $result = mysqli_query( $connect, $query );
    
    echo 'USING FIRST AND PARTY<br>';
  }
  
  echo $query.'<br>';
  echo mysqli_error( $connect ).'<br>';
  
  echo 'NAME: '.$name[0].' '.$name[1].'<br>';
  echo 'IMAGE: '.$image.'<br>';
  
  /*
  if( !strpos( $image, '://www.ndp.ca/' ) )
  {
    $image = 'https://www.ndp.ca'.$image;
    echo 'ADJUSTED: '.$image.'<br>';
  }
  */

  if( mysqli_num_rows( $result ) )
  {
    
    $record = mysqli_fetch_assoc( $result );
    
    if( $record['photo'] )
    {
      
      echo 'ALREADY SCRAPED<br>';
      
    }
    else
    {

      echo 'FOUND: '.$record['id'].'<br>';
      echo '<img src="'.$image.'" id="'.$record['id'].'" width="100" class="test"><br>';


      $images[] = array(
        'image' => $image,
        'id' => $record['id']
      );

      echo '<script>

        images.push( ["'.$record['id'].'","'.$image.'"] );

        </script>';
      
    }
    
  }
  else
  {
    
    echo 'NOT FOUND<br>';
    
  }
  
  echo '<hr>';
  
  $counter ++;
    
}

  

/*
phpQuery::newDocumentFile( $url );

foreach( pq( '.candidate-card' ) as $key => $value )
{
  
  $value = pq( $value );
  pre( $value );
  echo '<hr>';
  
  
  
  die();
  
  
}
*/

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script>
  
  
console.log( images );
var counter = 0;  
  

var int = setInterval(function(){

  
      console.log( images[counter][0] );
    console.log( images[counter][1] );
    console.log( counter );
  
  
  getDataUri( images[counter][1], function (base64) {
    
    // base64 availlable here
    console.log( base64 );
    console.log( images[counter][0] );
    console.log( images[counter][1] );
    console.log( counter );
    
    $.ajax({
      type: "POST",
      url: 'scrape-photo.php',
      data: {'id':images[counter][0],'image':base64,'type':'candidates'},
      success: function(){

        counter ++;
        
        console.log( 'NEXT' );

        
        if( counter == images.length ) 
        {
          clearInterval(int);
          console.log( 'DONE' );
        }
        
      }
    });
    
    
  })


  
  
  
  

  
},2500);


var getDataUri = function (targetUrl, callback) {
    var xhr = new XMLHttpRequest();
    xhr.onload = function () {
        var reader = new FileReader();
        reader.onloadend = function () {
            callback(reader.result);
        };
        reader.readAsDataURL(xhr.response);
    };
    var proxyUrl = 'https://cors-anywhere.herokuapp.com/';
    xhr.open('GET', proxyUrl + targetUrl);
    xhr.responseType = 'blob';
    xhr.send();
};
  

  
  
</script>
  
</body>
</html>