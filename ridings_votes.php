<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( count( $_POST ) )
{
  
  foreach( $_POST['votes'] as $key => $value )
  {
    
    $query = 'UPDATE candidates SET
      votes = '.$value.'
      WHERE id = '.$key.'
      LIMIT 1';
    mysqli_query( $connect, $query );
    
  }
  
  $query = 'UPDATE ridings SET
    updateDate = NOW()
    WHERE id = '.$_GET['id'].'
    LIMIT 1';
  mysqli_query( $connect, $query );
  
  set_message( 'Riding votes have been deleted' );
  
  header( 'Location: ridings.php' );
  die();
  
}

$query = 'SELECT id,
  first,
  last,
  votes,
  (
    SELECT nameEnglish
    FROM parties
    WHERE candidates.partyId = id
  ) AS partyName
  FROM candidates
  WHERE electionId = '.$_SESSION['e'].'
  AND ridingId = '.$_GET['id'].'
  ORDER BY last,first';
$result = mysqli_query( $connect, $query );

include 'includes/wideimage/WideImage.php';

?>

<h2>Party Candidates</h2>

<form method="post">

  <table>
    <tr>
      <th></th>
      <th>ID</th>
      <th>Name</th>
      <th>Party Name</th>
      <th>Votes</th>
    </tr>


    <?php while( $record = mysqli_fetch_assoc( $result ) ): ?>
      <tr>
        <td align="center">
          <img src="image.php?type=candidate&id=<?php echo $record['id']; ?>&width=40&height=40">
        </td>
        <td><?php echo $record['id']; ?></td>
        <td><?php echo htmlentities( $record['first'] ); ?> <?php echo htmlentities( $record['last'] ); ?></td>
        <td><?php echo htmlentities( $record['partyName'] ); ?></td>
        <td><input type="text" name="votes[<?php echo $record['id']; ?>]" value="<?php echo $record['votes']; ?>" style="width:100px;"></td>
      </tr>
    <?php endwhile; ?>
  </table>
  
  <input type="submit" value="Save Riding Votes">
  
</form>

<p><a href="candidates_add.php"><i class="fas fa-plus-square"></i> Add Candidate</a></p>


<?php

include( 'includes/footer.php' );

?>