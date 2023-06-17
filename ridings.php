<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( isset( $_GET['delete'] ) )
{
  
  $query = 'DELETE FROM ridings
    WHERE id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query( $connect, $query );
  
  mysqli_query( $connect, 'DELETE FROM articleRidings WHERE ridingId = '.$_GET['delete'] );
  
  set_message( 'Riding has been deleted' );
  
  header( 'Location: ridings.php' );
  die();
  
}

$query = 'SELECT *,
  (
    SELECT COUNT(*)
    FROM candidates
    WHERE ridingId = ridings.id
  ) AS candidates,
  (
    SELECT SUM(votes)
    FROM candidates
    WHERE ridingId = ridings.id
  ) AS votes,
  (
    SELECT nameEnglish
    FROM provinces
    WHERE provinceId = provinces.id
  ) AS provinceName
  FROM ridings
  WHERE electionId = '.$_SESSION['e'].'
  ORDER BY updateDate ASC';
$result = mysqli_query( $connect, $query );

?>

<h2>Manage Ridings</h2>

<table>
  <tr>
    <th>ID</th>
    <th>Official ID</th>
    <th>English</th>
    <th>French</th>
    <th>Candidates</th>
    <th>Province</th>
    <th>Votes</th>
    <th>Updated</th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  <?php while( $record = mysqli_fetch_assoc( $result ) ): ?>
    <tr>
      <td><?php echo $record['id']; ?></td>
      <td><?php echo $record['officialId']; ?></td>
      <td><?php echo htmlentities( $record['nameEnglish'] ); ?></td>
      <td><?php echo htmlentities( $record['nameFrench'] ); ?></td>
      <td><?php echo $record['candidates']; ?></td>
      <td><?php echo $record['provinceName']; ?></td>
      <td><?php echo $record['votes']; ?></td>
      <td style="white-space: nowrap;"><?php echo time_elapsed_string( $record['updateDate'] ); ?></td>
      <td align="center"><a href="ridings_votes.php?id=<?php echo $record['id']; ?>"><i class="fas fa-chart-line"></i></a></td>
      <td align="center"><a href="ridings_edit.php?id=<?php echo $record['id']; ?>"><i class="fas fa-edit"></i></a></td>
      <td align="center">
        <?php if( !$record['candidates'] ): ?>
          <a href="ridings.php?delete=<?php echo $record['id']; ?>" onclick="javascript:confirm('Are you sure you want to delete this riding?');"><i class="fas fa-trash-alt"></i></a>
        <?php else: ?>
          <i class="fas fa-trash-alt mute"></i>
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p><a href="ridings_add.php"><i class="fas fa-plus-square"></i> Add Riding</a></p>


<?php

include( 'includes/footer.php' );

?>