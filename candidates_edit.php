<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( !isset( $_GET['id'] ) )
{
  
  header( 'Location: candidates.php' );
  die();
  
}

if( isset( $_POST['first'] ) )
{
  
  if( $_POST['first']  and $_POST['last'] )
  {
    
    $query = 'UPDATE candidates SET
      first = "'.mysqli_real_escape_string( $connect, $_POST['first'] ).'",
      last = "'.mysqli_real_escape_string( $connect, $_POST['last'] ).'",
      votes = "'.$_POST['votes'].'",
      ridingId = "'.( $_POST['ridingId'] ? $_POST['ridingId'] : 0 ).'",
      partyId = "'.( $_POST['partyId'] ? $_POST['partyId'] : 0 ).'"
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    mysqli_query( $connect, $query );
        
    set_message( 'Candidate has been updated' );
    
  }

  header( 'Location: candidates.php' );
  die();
  
}


if( isset( $_GET['id'] ) )
{
  
  $query = 'SELECT *
    FROM candidates
    WHERE id = '.$_GET['id'].'
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  if( !mysqli_num_rows( $result ) )
  {
    
    header( 'Location: candidates.php' );
    die();
    
  }
  
  $record = mysqli_fetch_assoc( $result );
  
}

?>

<h2>Edit Candidate</h2>

<form method="post">
  
  <label for="first">First Name:</label>
  <input type="text" name="first" id="first" value="<?php echo htmlentities( $record['first'] ); ?>">
  
  <br>
  
  <label for="last">Last Name:</label>
  <input type="text" name="last" id="last" value="<?php echo htmlentities( $record['last'] ); ?>">
  
  <br>
  
  <label for="votes">Votes:</label>
  <input type="text" name="votes" id="votes" value="<?php echo htmlentities( $record['votes'] ); ?>">
  
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
    echo '<option value="'.$riding['id'].'"';
    if( $riding['id'] == $record['ridingId'] ) echo ' selected="selected"';
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
  
  echo '<select name="partyId" id="partyId">
    <option value="0"></option>';
  while( $party = mysqli_fetch_assoc( $result ) )
  {
    echo '<option value="'.$party['id'].'"';
    if( $party['id'] == $record['partyId'] ) echo ' selected="selected"';
    echo '>'.htmlentities( $party['nameEnglish'] ).'</option>';
  }
  echo '</select>';
  
  ?>
  
  <br>
  
  <input type="submit" value="Edit Candidate">
  
</form>

<p><a href="candidates.php"><i class="fas fa-arrow-circle-left"></i> Return to Candidate List</a></p>


<?php

include( 'includes/footer.php' );

?>