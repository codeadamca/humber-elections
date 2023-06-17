<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( isset( $_POST['first'] ) )
{
  
  if( $_POST['first'] and $_POST['last'] )
  {
    
    $query = 'INSERT INTO team (
        first,
        last,
        programId,
        bio,
        photo,
        electionId
      ) VALUES (
         "'.mysqli_real_escape_string( $connect, $_POST['first'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['last'] ).'",
         "'.$_POST['programId'].'",
         "'.mysqli_real_escape_string( $connect, $_POST['bio'] ).'",
         "",
         '.$_SESSION['e'].'
      )';
    mysqli_query( $connect, $query );
    
    set_message( 'Team member has been added' );
    
  }
  
  header( 'Location: team.php' );
  die();
  
}

?>

<h2>Add Team Member</h2>

<form method="post">
  
  <label for="first">First:</label>
  <input type="text" name="first" id="first">
    
  <br>
  
  <label for="last">Last:</label>
  <input type="text" name="last" id="last">
    
  <br>
  
  <label for="program">Program:</label>
  <?php
  
  $query = 'SELECT *
    FROM teamPrograms
    ORDER BY name';
  $result = mysqli_query( $connect, $query );
  
  echo '<select name="programId" id="programId">
    <option value="0"></option>';
  while( $program = mysqli_fetch_assoc( $result ) )
  {
    echo '<option value="'.$program['id'].'">'.htmlentities( $program['name'] ).'</option>';
  }
  echo '</select>';
  
  ?>
  
  <br>
  
  <label for="bio">Bio:</label>
  <textarea type="text" name="bio" id="bio" rows="10"></textarea>
      
  <script>

  ClassicEditor
    .create( document.querySelector( '#bio' ) )
    .then( editor => {
        console.log( editor );
    } )
    .catch( error => {
        console.error( error );
    } );
    
  </script>
  
  <br>
  
  <input type="submit" value="Add Team Member">
  
</form>

<p><a href="team.php"><i class="fas fa-arrow-circle-left"></i> Return to Team List</a></p>


<?php

include( 'includes/footer.php' );

?>