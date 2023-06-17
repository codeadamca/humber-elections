<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( !isset( $_GET['id'] ) )
{
  
  header( 'Location: articles.php' );
  die();
  
}

if( isset( $_POST['title'] ) )
{
  
  if( $_POST['title'] and $_POST['content'] )
  {
    
    $query = 'UPDATE articles SET
      title = "'.mysqli_real_escape_string( $connect, $_POST['title'] ).'",
      author = "'.mysqli_real_escape_string( $connect, $_POST['author'] ).'",
      date = "'.date( 'Y-m-d H:i:s', strtotime( $_POST['date'] ) ).'",
      content = "'.addslashes( $_POST['content'] ).'",
      youtube = "'.mysqli_real_escape_string( $connect, $_POST['youtube'] ).'",
      featured = "'.$_POST['featured'].'",
      status = "'.$_POST['status'].'"
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    mysqli_query( $connect, $query );

    mysqli_query( $connect, 'DELETE FROM articleRidings WHERE articleId = '.$_GET['id'] );
    
    if( isset( $_POST['ridingId'] ) and count( $_POST['ridingId'] ) )
    {

      foreach( $_POST['ridingId'] as $key => $value )
      {

        $query = 'INSERT INTO articleRidings (
            articleId,
            ridingId
          ) VALUES (
            '.$_GET['id'].',
            '.$value.'
          )';
        mysqli_query( $connect, $query );

      }
      
    }

    mysqli_query( $connect, 'DELETE FROM articleCandidates WHERE articleId = '.$_GET['id'] );
    
    if( isset( $_POST['candidateId'] ) and count( $_POST['candidateId'] ) )
    {

      foreach( $_POST['candidateId'] as $key => $value )
      {

        $query = 'INSERT INTO articleCandidates (
            articleId,
            candidateId
          ) VALUES (
            '.$_GET['id'].',
            '.$value.'
          )';
        mysqli_query( $connect, $query );

      }
      
    }
    
    mysqli_query( $connect, 'DELETE FROM articleParties WHERE articleId = '.$_GET['id'] );
    
    if( isset( $_POST['partyId'] ) and count( $_POST['partyId'] ) )
    {

      foreach( $_POST['partyId'] as $key => $value )
      {

        $query = 'INSERT INTO articleParties (
            articleId,
            partyId
          ) VALUES (
            '.$_GET['id'].',
            '.$value.'
          )';
        mysqli_query( $connect, $query );

      }
      
    }
    
    set_message( 'Article has been updated' );
    
  }

  header( 'Location: articles.php' );
  die();
  
}


if( isset( $_GET['id'] ) )
{
  
  $query = 'SELECT *,
    (
      SELECT GROUP_CONCAT(articleRidings.ridingId)
      FROM ridings
      INNER JOIN articleRidings
      ON ridings.id = articleRidings.ridingId
      WHERE articleRidings.articleId = articles.id
    ) AS ridings,
    (
      SELECT GROUP_CONCAT(articleCandidates.candidateId)
      FROM candidates
      INNER JOIN articleCandidates
      ON candidates.id = articleCandidates.candidateId
      WHERE articleCandidates.articleId = articles.id
    ) AS candidates,
    (
      SELECT GROUP_CONCAT(articleParties.partyId)
      FROM parties
      INNER JOIN articleParties
      ON parties.id = articleParties.partyId
      WHERE articleParties.articleId = articles.id
    ) AS parties
    FROM articles
    WHERE id = '.$_GET['id'].'
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  if( !mysqli_num_rows( $result ) )
  {
    
    header( 'Location: articles.php' );
    die();
    
  }
  
  $record = mysqli_fetch_assoc( $result );

  $record['parties'] = explode( ',', $record['parties'] );
  $record['ridings'] = explode( ',', $record['ridings'] );
  $record['candidates'] = explode( ',', $record['candidates'] );
  
}

?>

<h2>Edit Article</h2>

<form method="post">
  
  <label for="title">Title:</label>
  <input type="text" name="title" id="title" value="<?php echo htmlentities( $record['title'] ); ?>">
    
  <br>
    
  <label for="author">Author:</label>
  <input type="text" name="author" id="author" value="<?php echo htmlentities( $record['author'] ); ?>">
    
  <br>
  
  <label for="date">Date:</label>
  <input type="datetime-local" name="date" id="date" value="<?php echo date( 'Y-m-d\TH:i', strtotime( $record['date'] ) ); ?>">
    
  <br>
  
  <label for="youtube">YouTube URL:</label>
  <input type="text" name="youtube" id="youtube" value="<?php echo htmlentities( $record['youtube'] ); ?>">
    
  <br>
  
  <label for="ridingId">Riding:</label>
  <?php
  
  $query = 'SELECT *
    FROM ridings
    WHERE electionid = '.$_SESSION['e'].'
    ORDER BY nameEnglish';
  $result = mysqli_query( $connect, $query );
  
  echo '<select name="ridingId[]" id="ridingId" size="5" multiple>';
  while( $riding = mysqli_fetch_assoc( $result ) )
  {
    echo '<option value="'.$riding['id'].'"';
    if( in_array( $riding['id'], $record['ridings'] ) ) echo ' selected="selected"';
    echo '>'.htmlentities( $riding['nameEnglish'] ).'</option>';
  }
  echo '</select>';
  
  ?>
  
  <br>
  
  <label for="partyId">Party:</label>
  <?php
  
  $query = 'SELECT *
    FROM parties
    WHERE electionid = '.$_SESSION['e'].'
    ORDER BY nameEnglish';
  $result = mysqli_query( $connect, $query );
  
  echo '<select name="partyId[]" id="partyId" size="5" multiple>';
  while( $party = mysqli_fetch_assoc( $result ) )
  {
    echo '<option value="'.$party['id'].'"';
    if( in_array( $party['id'], $record['parties'] ) ) echo ' selected="selected"';
    echo '>'.htmlentities( $party['nameEnglish'] ).'</option>';
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
    echo '<option value="'.$candidate['id'].'"';
    if( in_array( $candidate['id'], $record['candidates'] ) ) echo ' selected="selected"';
    echo '>'.htmlentities( $candidate['first'].' '.$candidate['last'] ).'</option>';
  }
  echo '</select>';
  
  ?>
  
  <br>
  
  <label for="content">Content:</label>
  <textarea type="text" name="content" id="content" rows="5"><?php echo htmlentities( $record['content'] ); ?></textarea>
  
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
    if( $key == $record['status'] ) echo ' selected="selected"';
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
    if( $key == $record['featured'] ) echo ' selected="selected"';
    echo '>'.$value.'</option>';
  }
  echo '</select>';
  
  ?>
  
  <br>
  
  <input type="submit" value="Edit Article">
  
</form>

<p><a href="articles.php"><i class="fas fa-arrow-circle-left"></i> Return to Article List</a></p>


<?php

include( 'includes/footer.php' );

?>