<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( isset( $_GET['delete'] ) )
{
  
  $query = 'DELETE FROM team
    WHERE id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query( $connect, $query );
  
  set_message( 'Team member has been deleted' );
  
  header( 'Location: team.php' );
  die();
  
}

$query = 'SELECT *,
  (
    SELECT name
    FROM teamPrograms
    WHERE team.programId = id
  ) AS programName
  FROM team
  WHERE electionId = '.$_SESSION['e'].'
  ORDER BY last,first';
$result = mysqli_query( $connect, $query );

include 'includes/wideimage/WideImage.php';

?>

<h2>Manage Team</h2>

<table>
  <tr>
    <th></th>
    <th>ID</th>
    <th>Name</th>
    <th>Program</th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  <?php while( $record = mysqli_fetch_assoc( $result ) ): ?>
    <tr>
      <td align="center">
        <img src="image.php?type=team&id=<?php echo $record['id']; ?>&width=40&height=40">
      </td>
      <td><?php echo $record['id']; ?></td>
      <td><?php echo htmlentities( $record['first'] ); ?> <?php echo utf8_encode( $record['last'] ); ?></td>
      <td><?php echo htmlentities( $record['programName'] ); ?></td>
      <td align="center"><a href="team_photo.php?id=<?php echo $record['id']; ?>"><i class="fas fa-camera"></i></a></td>
      <td align="center"><a href="team_edit.php?id=<?php echo $record['id']; ?>"><i class="fas fa-edit"></i></a></td>
      <td align="center">
        <a href="team.php?delete=<?php echo $record['id']; ?>" onclick="javascript:confirm('Are you sure you want to delete this team member?');"><i class="fas fa-trash-alt"></i></a>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p><a href="team_add.php"><i class="fas fa-plus-square"></i> Add Team Member</a></p>


<?php

include( 'includes/footer.php' );

?>