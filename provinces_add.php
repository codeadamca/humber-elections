<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( isset( $_POST['nameEnglish'] ) )
{
  
  if( $_POST['nameEnglish'] and $_POST['nameFrench'] )
  {
    
    $query = 'INSERT INTO provinces (
        nameEnglish,
        nameFrench
      ) VALUES (
         "'.mysqli_real_escape_string( $connect, $_POST['nameEnglish'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['nameFrench'] ).'"
      )';
    mysqli_query( $connect, $query );
    
    set_message( 'Province has been added' );
    
  }

  header( 'Location: provinces.php' );
  die();
  
}

?>

<h2>Add Province</h2>

<form method="post">
  
  <label for="nameEnglish">English Name:</label>
  <input type="text" name="nameEnglish" id="nameEnglish">
  
  <br>
  
  <label for="nameFrench">French Name:</label>
  <input type="text" name="nameFrench" id="nameFrench">
    
  <br>
  
  <input type="submit" value="Add Province">
  
</form>

<p><a href="provinces.php"><i class="fas fa-arrow-circle-left"></i> Return to Province List</a></p>


<?php

include( 'includes/footer.php' );

?>