<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( isset( $_GET['delete'] ) )
{
  
  $query = 'DELETE FROM users
    WHERE id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query( $connect, $query );
  
  set_message( 'User has been deleted' );
  
  header( 'Location: users.php' );
  die();
  
}

$query = 'SELECT *
  FROM users 
  '.( ( $_SESSION['id'] != 1 and $_SESSION['id'] != 4 ) ? 'WHERE id = '.$_SESSION['id'].' ' : '' ).'
  ORDER BY last,first';
$result = mysqli_query( $connect, $query );

?>

<h2>Manage Users</h2>

<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  <?php while( $record = mysqli_fetch_assoc( $result ) ): ?>
    <tr>
      <td><?php echo $record['id']; ?></td>
      <td><?php echo htmlentities( $record['first'] ); ?> <?php echo htmlentities( $record['last'] ); ?></td>
      <td><a href="mailto:<?php echo htmlentities( $record['email'] ); ?>"><?php echo htmlentities( $record['email'] ); ?></a></td>
      <td align="center"><a href="users_edit.php?id=<?php echo $record['id']; ?>"><i class="fas fa-edit"></i></a></td>
      <td align="center">
        <?php if( $_SESSION['id'] != $record['id'] ): ?>
          <a href="users.php?delete=<?php echo $record['id']; ?>" onclick="javascript:confirm('Are you sure you want to delete this user?');"><i class="fas fa-trash-alt"></i></a>
        <?php else: ?>
          <i class="fas fa-trash-alt mute"></i>
        <?php endif; ?>
      </td>
      <td align="center">
        <?php if( $record['status'] ): ?>
          <i class="fas fa-toggle-on bright"></i>
        <?php else: ?>
          <i class="fas fa-toggle-off mute"></i>
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p><a href="users_add.php"><i class="fas fa-plus-square"></i> Add User</a></p>


<?php

include( 'includes/footer.php' );

?>