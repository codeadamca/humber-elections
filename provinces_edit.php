<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( !isset( $_GET['id'] ) )
{
  
  header( 'Location: provinces.php' );
  die();
  
}

if( isset( $_POST['nameEnglish'] ) )
{
  
  if( $_POST['nameEnglish'] and $_POST['nameFrench'] )
  {
    
    $query = 'UPDATE provinces SET
      nameEnglish = "'.mysqli_real_escape_string( $connect, $_POST['nameEnglish'] ).'",
      nameFrench = "'.mysqli_real_escape_string( $connect, $_POST['nameFrench'] ).'"
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    mysqli_query( $connect, $query );
        
    set_message( 'Province has been updated' );
    
  }

  header( 'Location: provinces.php' );
  die();
  
}


if( isset( $_GET['id'] ) )
{
  
  $query = 'SELECT *
    FROM provinces
    WHERE id = '.$_GET['id'].'
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  if( !mysqli_num_rows( $result ) )
  {
    
    header( 'Location: provinces.php' );
    die();
    
  }
  
  $record = mysqli_fetch_assoc( $result );
  
}

?>

<h2>Edit Province</h2>

<form method="post">
  
  <label for="nameEnglish">English Name:</label>
  <input type="text" name="nameEnglish" id="nameEnglish" value="<?php echo htmlentities( $record['nameEnglish'] ); ?>">
  
  <br>
  
  <label for="nameFrench">French Name:</label>
  <input type="text" name="nameFrench" id="nameFrench" value="<?php echo htmlentities( $record['nameFrench'] ); ?>">
  
  <br>
  
  <input type="submit" value="Edit Province">
  
</form>

<p><a href="provinces.php"><i class="fas fa-arrow-circle-left"></i> Return to Province List</a></p>


<?php

include( 'includes/footer.php' );

?>