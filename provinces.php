<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( isset( $_GET['delete'] ) )
{
  
  $query = 'DELETE FROM provinces
    WHERE id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query( $connect, $query );
  
  set_message( 'Province has been deleted' );
  
  header( 'Location: provinces.php' );
  die();
  
}

$query = 'SELECT *,
  (
    SELECT COUNT(*)
    FROM ridings
    WHERE provinceId = provinces.id
    AND ridings.electionId = '.$_SESSION['e'].'
  ) AS ridings
  FROM provinces
  ORDER BY nameEnglish';
$result = mysqli_query( $connect, $query );

?>

<h2>Manage Provinces</h2>

<table>
  <tr>
    <th>ID</th>
    <th>English</th>
    <th>French</th>
    <th>Ridings</th>
    <th></th>
    <th></th>
  </tr>
  <?php while( $record = mysqli_fetch_assoc( $result ) ): ?>
    <tr>
      <td><?php echo $record['id']; ?></td>
      <td><?php echo htmlentities( $record['nameEnglish'] ); ?></td>
      <td><?php echo htmlentities( $record['nameFrench'] ); ?></td>
      <td><?php echo $record['ridings']; ?></td>
      <td align="center"><a href="provinces_edit.php?id=<?php echo $record['id']; ?>"><i class="fas fa-edit"></i></a></td>
      <td align="center">
        <?php if( !$record['ridings'] ): ?>
          <a href="provinces.php?delete=<?php echo $record['id']; ?>" onclick="javascript:confirm('Are you sure you want to delete this province?');"><i class="fas fa-trash-alt"></i></a>
        <?php else: ?>
          <i class="fas fa-trash-alt mute"></i>
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>

<p><a href="provinces_add.php"><i class="fas fa-plus-square"></i> Add Province</a></p>


<?php

include( 'includes/footer.php' );

?>