<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( isset( $_GET['delete'] ) )
{
  
  $query = 'DELETE FROM parties
    WHERE id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query( $connect, $query );
  
  mysqli_query( $connect, 'DELETE FROM articleParties WHEREpartyId = '.$_GET['delete'] );
  
  set_message( 'Party has been deleted' );
  
  header( 'Location: parties.php' );
  die();
  
}

$query = 'SELECT *,
  (
    SELECT COUNT(*)
    FROM candidates
    WHERE partyId = parties.id
  ) AS candidates,
  (
    SELECT CONCAT(first," ",last)
    FROM candidates
    WHERE candidates.id = parties.candidateId
  ) AS leader
  FROM parties
  WHERE electionId = '.$_SESSION['e'].'
  ORDER BY nameEnglish';
$result = mysqli_query( $connect, $query );

?>

<h2>Manage Parties</h2>

<table>
  <tr>
    <th>ID</th>
    <th>Colour</th>
    <th>English</th>
    <th>French</th>
    <th>Leader</th>
    <th>Candidates</th>

    <th></th>
    <th></th>
    <th></th>
  </tr>
  <?php while( $record = mysqli_fetch_assoc( $result ) ): ?>
    <tr>
      <td><?php echo $record['id']; ?></td>
      <td style="color:<?php echo $record['colour']; ?>"><?php echo $record['colour']; ?></td>
      <td><?php echo htmlentities( $record['nameEnglish'] ); ?></td>
      <td><?php echo htmlentities( $record['nameFrench'] ); ?></td>
      <td><?php echo htmlentities( $record['leader'] ); ?></td>
      <td><?php echo $record['candidates']; ?></td>
      <td align="center"><a href="parties_edit.php?id=<?php echo $record['id']; ?>"><i class="fas fa-edit"></i></a></td>
      <td align="center">
        <?php if( !$record['candidates'] ): ?>
          <a href="parties.php?delete=<?php echo $record['id']; ?>" onclick="javascript:confirm('Are you sure you want to delete this party?');"><i class="fas fa-trash-alt"></i></a>
        <?php else: ?>
          <i class="fas fa-trash-alt mute"></i>
        <?php endif; ?>
      </td>
      <td align="center">
        <?php if( $record['featured'] ): ?>
          <i class="fas fa-toggle-on bright"></i>
        <?php else: ?>
          <i class="fas fa-toggle-off mute"></i>
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p><a href="parties_add.php"><i class="fas fa-plus-square"></i> Add Party</a></p>


<?php

include( 'includes/footer.php' );

?>