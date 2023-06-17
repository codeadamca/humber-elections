<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( !isset( $_GET['id'] ) )
{
  
  header( 'Location: team.php' );
  die();
  
}

if( isset( $_POST['first'] ) )
{
  
  if( $_POST['first'] and $_POST['last'] )
  {
    
    $query = 'UPDATE team SET
      first = "'.mysqli_real_escape_string( $connect, $_POST['first'] ).'",
      last = "'.mysqli_real_escape_string( $connect, $_POST['last'] ).'",
      programId = "'.$_POST['programId'].'",
      bio = "'.mysqli_real_escape_string( $connect, $_POST['bio'] ).'"
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    mysqli_query( $connect, $query );

    set_message( 'Team member has been updated' );
    
  }

  header( 'Location: team.php' );
  die();
  
}


if( isset( $_GET['id'] ) )
{
  
  $query = 'SELECT *
    FROM team
    WHERE id = '.$_GET['id'].'
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  if( !mysqli_num_rows( $result ) )
  {
    
    header( 'Location: team.php' );
    die();
    
  }
  
  $record = mysqli_fetch_assoc( $result );
  
}

?>

<h2>Edit Team Member</h2>

<form method="post">
  
  <label for="first">First Name:</label>
  <input type="text" name="first" id="first" value="<?php echo htmlentities( $record['first'] ); ?>">
    
  <br>
  
  <label for="last">Last Name:</label>
  <input type="text" name="last" id="last" value="<?php echo htmlentities( $record['last'] ); ?>">
    
  <br>
  
  <label for="programId">Program:</label>
  <?php
  
  $query = 'SELECT *
    FROM teamPrograms
    ORDER BY name';
  $result = mysqli_query( $connect, $query );
  
  echo '<select name="programId" id="programId">
    <option value="0"></option>';
  while( $program = mysqli_fetch_assoc( $result ) )
  {
    echo '<option value="'.$program['id'].'"';
    if( $program['id'] == $record['programId'] ) echo ' selected="selected"';
    echo '>'.htmlentities( $program['name'] ).'</option>';
  }
  echo '</select>';
  
  ?>
  
  <br>
  
  <label for="bio">Bio:</label>
  <textarea type="text" name="bio" id="bio" rows="5"><?php echo htmlentities( $record['bio'] ); ?></textarea>
  
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
  
  <input type="submit" value="Edit Team Member">
  
</form>

<p><a href="team.php"><i class="fas fa-arrow-circle-left"></i> Return to Team List</a></p>


<?php

include( 'includes/footer.php' );

?>