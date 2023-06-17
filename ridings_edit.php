<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( !isset( $_GET['id'] ) )
{
  
  header( 'Location: ridings.php' );
  die();
  
}

if( isset( $_POST['nameEnglish'] ) )
{
  
  if( $_POST['officialId'] and $_POST['nameFrench'] and $_POST['nameEnglish'] )
  {
    
    $query = 'UPDATE ridings SET
      officialId = "'.( $_POST['officialId'] ? $_POST['officialId'] : 0 ).'",
      provinceId = "'.( $_POST['provinceId'] ? $_POST['provinceId'] : 0 ).'",
      nameEnglish = "'.mysqli_real_escape_string( $connect, $_POST['nameEnglish'] ).'",
      nameFrench = "'.mysqli_real_escape_string( $connect, $_POST['nameFrench'] ).'",
      globalId = "'.mysqli_real_escape_string( $connect, $_POST['globalId'] ).'"
      WHERE id = '.$_GET['id'].'
      LIMIT 1';
    mysqli_query( $connect, $query );
        
    set_message( 'Riding has been updated' );
    
  }

  header( 'Location: ridings.php' );
  die();
  
}


if( isset( $_GET['id'] ) )
{
  
  $query = 'SELECT *
    FROM ridings
    WHERE id = '.$_GET['id'].'
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  if( !mysqli_num_rows( $result ) )
  {
    
    header( 'Location: ridings.php' );
    die();
    
  }
  
  $record = mysqli_fetch_assoc( $result );
  
}

?>

<h2>Edit Riding</h2>

<form method="post">
  
  <label for="officialId">Official ID:</label>
  <input type="text" name="officialId" id="officialId" value="<?php echo htmlentities( $record['officialId'] ); ?>">
  
  <br>
  
  <label for="nameEnglish">English Name:</label>
  <input type="text" name="nameEnglish" id="nameEnglish" value="<?php echo htmlentities( $record['nameEnglish'] ); ?>">
  
  <br>
  
  <label for="nameFrench">French Name:</label>
  <input type="text" name="nameFrench" id="nameFrench" value="<?php echo htmlentities( $record['nameFrench'] ); ?>">
    
  <br>
  
  <label for="globalId">Global ID:</label>
  <input type="text" name="globalId" id="globalId" value="<?php echo htmlentities( $record['globalId'] ); ?>">
  
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
    echo '<option value="'.$province['id'].'"';
    if( $province['id'] == $record['provinceId'] ) echo ' selected="selected"';
    echo '>'.htmlentities( $province['nameEnglish'] ).'</option>';
  }
  echo '</select>';
  
  ?>
    
  <br>
  
  <input type="submit" value="Edit Riding">
  
</form>

<p><a href="ridings.php"><i class="fas fa-arrow-circle-left"></i> Return to Riding List</a></p>


<?php

include( 'includes/footer.php' );

?>