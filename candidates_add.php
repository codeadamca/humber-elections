<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( isset( $_POST['first'] ) )
{
  
  if( $_POST['first']  and $_POST['last'] )
  {
    
    $query = 'INSERT INTO candidates (
        first,
        last,
        partyId,
        ridingId,
        photo,
        votes,
        electionId
      ) VALUES (
         "'.mysqli_real_escape_string( $connect, $_POST['first'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['last'] ).'",
         "'.( $_POST['partyId'] ? $_POST['partyId'] : 0 ).'",
         "'.( $_POST['ridingId'] ? $_POST['ridingId'] : 0 ).'",
         "",
         "'.$_POST['votes'].'",
         '.$_SESSION['e'].'
      )';
    mysqli_query( $connect, $query );
    
    set_message( 'Candidate has been added' );
    
  }
  
  header( 'Location: candidates.php' );
  die();
  
}

?>

<h2>Add Candidate</h2>

<form method="post">
  
  <label for="first">First Name:</label>
  <input type="text" name="first" id="first">
  
  <br>
  
  <label for="last">Last Name:</label>
  <input type="text" name="last" id="last">
    
  <br>
  
  <label for="votes">Votes:</label>
  <input type="text" name="votes" id="votes">
    
  <br>
  
  <label for="ridingId">Riding:</label>
  <?php
  
  $query = 'SELECT *
    FROM ridings
    WHERE electionid = '.$_SESSION['e'].'
    ORDER BY nameEnglish';
  $result = mysqli_query( $connect, $query );
  
  echo '<select name="ridingId" id="ridingId">
    <option value="0"></option>';
  while( $riding = mysqli_fetch_assoc( $result ) )
  {
    echo '<option value="'.$riding['id'].'">'.htmlentities( $riding['nameEnglish'] ).'</option>';
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
  
  echo '<select name="partyId" id="partyId">
    <option value="0"></option>';
  while( $party = mysqli_fetch_assoc( $result ) )
  {
    echo '<option value="'.$party['id'].'">'.htmlentities( $party['nameEnglish'] ).'</option>';
  }
  echo '</select>';
  
  ?>
  
  <br>
  
  <input type="submit" value="Add Candidate">
  
</form>

<p><a href="candidates.php"><i class="fas fa-arrow-circle-left"></i> Return to Candidate List</a></p>


<?php

include( 'includes/footer.php' );

?>