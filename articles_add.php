<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( isset( $_POST['title'] ) )
{
  
  if( $_POST['title'] and $_POST['content'] )
  {
    
    $query = 'INSERT INTO articles (
        title,
        author,
        date,
        youtube,
        featured,
        status,
        content,
        photo,
        electionId,
        cutline
      ) VALUES (
         "'.mysqli_real_escape_string( $connect, $_POST['title'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['author'] ).'",
         "'.date( 'Y-m-d H:i:s', strtotime( $_POST['date'] ) ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['youtube'] ).'",
         "'.$_POST['featured'].'",
         "'.$_POST['status'] .'",
         "'.mysqli_real_escape_string( $connect, $_POST['content'] ).'",
         "",
         '.$_SESSION['e'].',
         ""
      )';
    mysqli_query( $connect, $query );
    
    $id = mysqli_insert_id( $connect );
    
    if( isset( $_POST['ridingId'] ) and count( $_POST['ridingId'] ) )
    {

      foreach( $_POST['ridingId'] as $key => $value )
      {

        $query = 'INSERT INTO articleRidings (
            articleId,
            ridingId
          ) VALUES (
            '.$id.',
            '.$value.'
          )';
        mysqli_query( $connect, $query );

      }
      
    }
    
    if( isset( $_POST['candidateId'] ) and count( $_POST['candidateId'] ) )
    {

      foreach( $_POST['candidateId'] as $key => $value )
      {

        $query = 'INSERT INTO articleCandidates (
            articleId,
            candidateId
          ) VALUES (
            '.$id.',
            '.$value.'
          )';
        mysqli_query( $connect, $query );

      }
      
    }
    
    if( isset( $_POST['partyId'] ) and count( $_POST['partyId'] ) )
    {

      foreach( $_POST['partyId'] as $key => $value )
      {

        $query = 'INSERT INTO articleParties (
            articleId,
            partyId
          ) VALUES (
            '.$id.',
            '.$value.'
          )';
        mysqli_query( $connect, $query );

      }
      
    }
    
    set_message( 'Article has been added' );
    
  }
  
  header( 'Location: articles.php' );
  die();
  
}

?>

<h2>Add Article</h2>

<form method="post">
  
  <label for="title">Title:</label>
  <input type="text" name="title" id="title">
    
  <br>

  <label for="author">Author:</label>
  <input type="text" name="author" id="author">
    
  <br>
  
  <label for="date">Date:</label>
  <input type="datetime-local" name="date" id="date">
    
  <br>

  <label for="youtube">YouTube URL:</label>
  <input type="text" name="youtube" id="youtube">
    
  <br>
  
  <label for="ridingId">Riding:</label>
  <?php
  
  $query = 'SELECT id,
    nameEnglish
    FROM ridings
    WHERE electionid = '.$_SESSION['e'].'
    ORDER BY nameEnglish';
  $result = mysqli_query( $connect, $query );
  
  echo '<select name="ridingId[]" id="ridingId" size="5" multiple>';
  while( $riding = mysqli_fetch_assoc( $result ) )
  {
    echo '<option value="'.$riding['id'].'">'.htmlentities( $riding['nameEnglish'] ).'</option>';
  }
  echo '</select>';
  
  ?>
  
  <br>
  
  <label for="partyId">Party:</label>
  <?php
  
  $query = 'SELECT id,nameEnglish
    FROM parties
    WHERE electionid = '.$_SESSION['e'].'
    ORDER BY nameEnglish';
  $result = mysqli_query( $connect, $query );
  
  echo '<select name="partyId[]" id="partyId" size="5" multiple>';
  while( $party = mysqli_fetch_assoc( $result ) )
  {
    echo '<option value="'.$party['id'].'">'.htmlentities( $party['nameEnglish'] ).'</option>';
  }
  echo '</select>';
  
  ?>
  
  <br>
  
  <label for="candidateId">Candidate:</label>
  <?php
  
  $query = 'SELECT id,first,last
    FROM candidates
    WHERE electionid = '.$_SESSION['e'].'
    ORDER BY last,first';
  $result = mysqli_query( $connect, $query );
  
  echo '<select name="candidateId[]" id="candidateId" size="10" multiple>';
  while( $candidate = mysqli_fetch_assoc( $result ) )
  {
    echo '<option value="'.$candidate['id'].'">'.htmlentities( $candidate['first'].' '.$candidate['last'] ).'</option>';
  }
  echo '</select>';
  
  ?>
  
  <br>
  
  <label for="content">Content:</label>
  <textarea type="text" name="content" id="content" rows="10"></textarea>
      
  <script>

  ClassicEditor
    .create( document.querySelector( '#content' ) )
    .then( editor => {
        console.log( editor );
    } )
    .catch( error => {
        console.error( error );
    } );
    
  </script>
  
  <br>
  
  <label for="status">Status:</label>
  <?php
  
  $values = array( 0 => 'Inactive', 1 => 'Active' );
  
  echo '<select name="status" id="status">';
  foreach( $values as $key => $value )
  {
    echo '<option value="'.$key.'"';
    echo '>'.$value.'</option>';
  }
  echo '</select>';
  
  ?>
  
  <br>
  
  <label for="featured">Featured:</label>
  <?php
  
  $values = array( 0 => 'Not Featured', 1 => 'Featured' );
  
  echo '<select name="featured" id="featured">';
  foreach( $values as $key => $value )
  {
    echo '<option value="'.$key.'"';
    echo '>'.$value.'</option>';
  }
  echo '</select>';
  
  ?>
  
  <br>
  
  <input type="submit" value="Add Article">
  
</form>

<p><a href="articles.php"><i class="fas fa-arrow-circle-left"></i> Return to Article List</a></p>


<?php

include( 'includes/footer.php' );

?>