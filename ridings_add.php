<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( isset( $_POST['nameEnglish'] ) )
{
  
  if( $_POST['officialId'] and $_POST['nameFrench'] and $_POST['nameEnglish'] )
  {
    
    $query = 'INSERT INTO ridings (
        officialId,
        provinceId,
        nameEnglish,
        nameFrench,
        electionId,
        globalId
      ) VALUES (
         "'.( $_POST['officialId'] ? $_POST['officialId'] : 0 ).'",
         "'.( $_POST['provinceId'] ? $_POST['provinceId'] : 0 ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['nameEnglish'] ).'",
         "'.mysqli_real_escape_string( $connect, $_POST['nameFrench'] ).'",
         '.$_SESSION['e'].',
         "'.mysqli_real_escape_string( $connect, $_POST['globalId'] ).'"
      )';
    mysqli_query( $connect, $query );
    
    set_message( 'Riding has been added' );
    
  }
  
  header( 'Location: ridings.php' );
  die();
  
}

?>

<h2>Add Riding</h2>

<form method="post">

  <label for="officialId">Official ID:</label>
  <input type="text" name="officialId" id="officialId">
  
  <br>
  
  <label for="nameEnglish">English Name:</label>
  <input type="text" name="nameEnglish" id="nameEnglish">
  
  <br>
  
  <label for="nameFrench">French Name:</label>
  <input type="text" name="nameFrench" id="nameFrench">
    
  <br>
  
  <label for="globalId">Global ID:</label>
  <input type="text" name="globalId" id="globalId">
  
  <br>
  
  <label for="provinceId">Province:</label>
  <?php
  
  $query = 'SELECT *
    FROM provinces
    ORDER BY nameEnglish';
  $result = mysqli_query( $connect, $query );
  
  echo '<select name="provinceId" id="provinceId">
    <option value="0"></option>';
  while( $province = mysqli_fetch_assoc( $result ) )
  {
    echo '<option value="'.$province['id'].'">'.htmlentities( $province['nameEnglish'] ).'</option>';
  }
  echo '</select>';
  
  ?>
  
  <br>
  
  <label for="coordinates">Coordinates:</label>
  <textarea type="text" name="coordinates" id="coordinates" rows="5"></textarea>
    
  <br>
  
  <input type="submit" value="Add Riding">
  
</form>

<p><a href="ridings.php"><i class="fas fa-arrow-circle-left"></i> Return to Riding List</a></p>


<?php

include( 'includes/footer.php' );

?>