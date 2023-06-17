<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( isset( $_GET['delete'] ) )
{
  
  $query = 'DELETE FROM candidates
    WHERE id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query( $connect, $query );
  
  mysqli_query( $connect, 'DELETE FROM articleCandidates WHERE candidateId = '.$_GET['delete'] );
  
  set_message( 'Candidate has been deleted' );
  
  header( 'Location: candidates.php' );
  die();
  
}

$query = 'SELECT id,
  first,
  last,
  votes,
  (
    SELECT nameEnglish
    FROM ridings
    WHERE candidates.ridingId = id
  ) AS ridingName,
  (
    SELECT nameEnglish
    FROM parties
    WHERE candidates.partyId = id
  ) AS partyName
  FROM candidates
  WHERE electionId = '.$_SESSION['e'].'
  ORDER BY last,first';
$result = mysqli_query( $connect, $query );

include 'includes/wideimage/WideImage.php';

?>

<h2>Manage Candidates</h2>

<table>
  <tr>
    <th></th>
    <th>ID</th>
    <th>Name</th>
    <th>Party Name</th>
    <th>Riding</th>
    <th>Votes</th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  <?php while( $record = mysqli_fetch_assoc( $result ) ): ?>
    <tr>
      <td align="center">
        <img src="image.php?type=candidate&id=<?php echo $record['id']; ?>&width=40&height=40">
      </td>
      <td><?php echo $record['id']; ?></td>
      <td><?php echo htmlentities( $record['first'] ); ?> <?php echo htmlentities( $record['last'] ); ?></td>
      <td><?php echo htmlentities( $record['partyName'] ); ?></td>
      <td><?php echo htmlentities( $record['ridingName'] ); ?></td>
      <td><?php echo $record['votes']; ?></td>
      <td align="center"><a href="candidates_photo.php?id=<?php echo $record['id']; ?>"><i class="fas fa-camera"></i></a></td>
      <td align="center"><a href="candidates_edit.php?id=<?php echo $record['id']; ?>"><i class="fas fa-edit"></i></a></td>
      <td align="center">
        <a href="candidates.php?delete=<?php echo $record['id']; ?>" onclick="javascript:confirm('Are you sure you want to delete this candidate?');"><i class="fas fa-trash-alt"></i></a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p><a href="candidates_add.php"><i class="fas fa-plus-square"></i> Add Candidate</a></p>


<?php

include( 'includes/footer.php' );

?>