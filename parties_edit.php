<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( !isset( $_GET['id'] ) )
{
  
  header( 'Location: parties.php' );
  die();
  
}

if( isset( $_POST['nameEnglish'] ) )
{
  
  if( $_POST['nameEnglish'] and $_POST['nameFrench'] )
  {
    
    $query = 'UPDATE parties SET
      nameEnglish = "'.mysqli_real_escape_string( $connect, $_POST['nameEnglish'] ).'",
      nameFrench = "'.mysqli_real_escape_string( $connect, $_POST['nameFrench'] ).'",
      candidateId = "'.$_POST['candidateId'].'",
      globalId = "'.mysqli_real_escape_string( $connect, $_POST['globalId'] ).'",
      colour = "'.mysqli_real_escape_string( $connect, $_POST['colour'] ).'",
      featured = "'.mysqli_real_escape_string( $connect, $_POST['featured'] ).'"
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    mysqli_query( $connect, $query );
        
    set_message( 'Party has been updated' );
    
  }

  header( 'Location: parties.php' );
  die();
  
}


if( isset( $_GET['id'] ) )
{
  
  $query = 'SELECT *
    FROM parties
    WHERE id = '.$_GET['id'].'
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  if( !mysqli_num_rows( $result ) )
  {
    
    header( 'Location: parties.php' );
    die();
    
  }
  
  $record = mysqli_fetch_assoc( $result );
  
}

?>

<h2>Edit Party</h2>

<form method="post">
  
  <label for="nameEnglish">English Name:</label>
  <input type="text" name="nameEnglish" id="nameEnglish" value="<?php echo htmlentities( $record['nameEnglish'] ); ?>">
  
  <br>
  
  <label for="nameFrench">French Name:</label>
  <input type="text" name="nameFrench" id="nameFrench" value="<?php echo htmlentities( $record['nameFrench'] ); ?>">
  
  <br>
  
  <label for="colour">Colour:</label>
  <input type="text" name="colour" id="colour" value="<?php echo htmlentities( $record['colour'] ); ?>">
  
  <br>
  
  <label for="candidateId">Candidate:</label>
  <?php
  
  $query = 'SELECT id,first,last
    FROM candidates
    WHERE electionid = '.$_SESSION['e'].'
    ORDER BY last,first';
  $result = mysqli_query( $connect, $query );
  
  echo '<select name="candidateId" id="candidateId">
    <option value="0"></option>';
  while( $candidate = mysqli_fetch_assoc( $result ) )
  {
    echo '<option value="'.$candidate['id'].'"';
    if( $candidate['id'] == $record['candidateId'] ) echo ' selected="selected"';
    echo '>'.htmlentities( $candidate['first'].' '.$candidate['last'] ).'</option>';
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
  
  <input type="submit" value="Edit Party">
  
</form>

<p><a href="provinces.php"><i class="fas fa-arrow-circle-left"></i> Return to Party List</a></p>


<?php

include( 'includes/footer.php' );

?>