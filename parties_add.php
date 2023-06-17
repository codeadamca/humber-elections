<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( isset( $_POST['nameEnglish'] ) )
{
  
  if( $_POST['nameEnglish'] and $_POST['nameFrench'] and $_POST['colour'] )
  {
    
    $query = 'INSERT INTO parties (
        nameEnglish,
        nameFrench,
        colour,
        electionId,
        candidateId,
        featured
      ) VALUES (
         "'.mysqli_real_escape_string( $connect, $_POST['nameEnglish'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['nameFrench'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['colour'] ).'",
         '.$_SESSION['e'].',
         "'.mysqli_real_escape_string( $connect, $_POST['candidateId'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['featured'] ).'"
      )';
    mysqli_query( $connect, $query );

    set_message( 'Party has been added' );
    
  }

  header( 'Location: parties.php' );
  die();
  
}

?>

<h2>Add Party</h2>

<form method="post">
  
  <label for="nameEnglish">English Name:</label>
  <input type="text" name="nameEnglish" id="nameEnglish">
  
  <br>
  
  <label for="nameFrench">French Name:</label>
  <input type="text" name="nameFrench" id="nameFrench">
    
  <br>
  
  <label for="colour">RGB Colour:</label>
  <input type="text" name="colour" id="colour">
    
  <br>
  
  <label for="candidateId">Leader:</label>
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
    echo '<option value="'.$candidate['id'].'">'.htmlentities( $candidate['first'].' '.$candidate['last'] ).'</option>';
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
  
  <input type="submit" value="Add Party">
  
</form>

<p><a href="parties.php"><i class="fas fa-arrow-circle-left"></i> Return to Party List</a></p>


<?php

include( 'includes/footer.php' );

?>