<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

if( isset( $_POST['email'] ) )
{
  
  $query = 'SELECT *
    FROM users
    WHERE email = "'.$_POST['email'].'"
    AND password = "'.md5( $_POST['password'] ).'"
    AND status = 1
    LIMIT 1';
  $result = mysqli_query( $connect, $query );
  
  if( mysqli_num_rows( $result ) )
  {
    
    $record = mysqli_fetch_assoc( $result );
    
    $_SESSION['e'] = $_POST['electionId'] ? $_POST['electionId'] : LIVE_ELECTION;
    $_SESSION['id'] = $record['id'];
    
    header( 'Location: /dashboard.php' );
    die();
    
  }
  else
  {
    
    set_message( 'Incorrect email and/or password' );
    
    header( 'Location: /' );
    die();
    
  } 
  
}

include( 'includes/header.php' );

?>

<div style="max-width: 400px; margin:auto">

  <form method="post">

    <label for="email">Email:</label>
    <input type="text" name="email" id="email">

    <br>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password">

    <br>
    
    <label for="eledtionId">Election:</label>
    <?php

    $query = 'SELECT *
      FROM elections
      ORDER BY name';
    $result = mysqli_query( $connect, $query );

    echo '<select name="eledtionId" id="eledtionId">
      <option value=""></option>';
    while( $election = mysqli_fetch_assoc( $result ) )
    {
      echo '<option value="'.$election['id'].'"';
      if( $election['id'] == LIVE_ELECTION ) echo ' selected="selected"';
      echo '>'.utf8_encode( $election['name'] ).'</option>';
    }
    echo '</select>';

    ?>
    
    <br>

    <input type="submit" value="Login">

  </form>
  
</div>

<?php

include( 'includes/footer.php' );

?>