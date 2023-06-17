<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

if( isset( $_GET['delete'] ) )
{
  
  $query = 'DELETE FROM articles
    WHERE id = '.$_GET['delete'].'
    LIMIT 1';
  mysqli_query( $connect, $query );
  
  mysqli_query( $connect, 'DELETE FROM articleRidings WHERE articleId = '.$_GET['delete'] );
  mysqli_query( $connect, 'DELETE FROM articleCandidates WHERE articleId = '.$_GET['delete'] );
  mysqli_query( $connect, 'DELETE FROM articleParties WHERE articleId = '.$_GET['delete'] );
  
  set_message( 'Article has been deleted' );
  
  header( 'Location: articles.php' );
  die();
  
}

$query = 'SELECT *,
  (
    SELECT GROUP_CONCAT(nameEnglish SEPARATOR "<br>")
    FROM ridings
    INNER JOIN articleRidings
    ON ridings.id = articleRidings.ridingId
    WHERE articleRidings.articleId = articles.id
  ) AS ridings,
  (
    SELECT GROUP_CONCAT(CONCAT(first," ",last) SEPARATOR "<br>")
    FROM candidates
    INNER JOIN articleCandidates
    ON candidates.id = articleCandidates.candidateId
    WHERE articleCandidates.articleId = articles.id
  ) AS candidates,
  (
    SELECT GROUP_CONCAT(nameEnglish SEPARATOR "<br>")
    FROM parties
    INNER JOIN articleParties
    ON parties.id = articleParties.partyId
    WHERE articleParties.articleId = articles.id
  ) AS parties
  FROM articles
  WHERE electionId = '.$_SESSION['e'].'
  ORDER BY date';
$result = mysqli_query( $connect, $query );

include 'includes/wideimage/WideImage.php';

?>

<h2>Manage Articles</h2>

<table>
  <tr>
    <th></th>
    <th>ID</th>
    <th>Title</th>
    <th>Riding</th>
    <th>Candidate</th>
    <th>Party</th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
  </tr>
  <?php while( $record = mysqli_fetch_assoc( $result ) ): ?>
    <tr>
      <td align="center">
        <img src="image.php?type=article&id=<?php echo $record['id']; ?>&width=40&height=40">
      </td>
      <td><?php echo $record['id']; ?></td>
      <td><?php echo htmlentities( $record['title'] ); ?></td>
      <td><?php echo htmlentities( $record['ridings'] ); ?></td>
      <td><?php echo $record['candidates']; ?></td>
      <td><?php echo $record['parties']; ?></td>
      <td align="center"><a href="articles_photo.php?id=<?php echo $record['id']; ?>"><i class="fas fa-camera"></i></a></td>
      <td align="center"><a href="articles_edit.php?id=<?php echo $record['id']; ?>"><i class="fas fa-edit"></i></a></td>
      <td align="center">
        <a href="articles.php?delete=<?php echo $record['id']; ?>" onclick="javascript:confirm('Are you sure you want to delete this article?');"><i class="fas fa-trash-alt"></i></a>
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

<p><a href="articles_add.php"><i class="fas fa-plus-square"></i> Add Article</a></p>


<?php

include( 'includes/footer.php' );

?>