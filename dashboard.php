<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

?>

<ul id="dashboard">
  <li>
    <a href="parties.php">
      <i class="fas fa-handshake fa-3x"></i>
      <br>
      Parties
    </a>
  </li>
  <li>
    <a href="ridings.php">
      <i class="fas fa-person-booth fa-3x"></i>      
      <br>
      Ridings
    </a>
  </li>
  <li>
    <a href="candidates.php">
      <i class="fas fa-user-tie fa-3x"></i>
      <br>
      Candidates
    </a>
  </li>
  <li>
    <a href="provinces.php">
      <i class="fas fa-map-marker-alt fa-3x"></i>
      <br>
      Provinces
    </a>
  </li>
  <li>
    <a href="team.php">
      <i class="fas fa-users fa-3x"></i>
      <br>
      Team
    </a>
  </li>
  <li>
    <a href="articles.php">
      <i class="fas fa-newspaper fa-3x"></i>
      <br>
      Articles
    </a>
  </li>
  <li>
    <a href="json.php">
      <i class="fas fa-rss fa-3x"></i>
      <br>
      JSON Feeds
    </a>
  </li>
  <li>
    <a href="users.php">
      <i class="fas fa-user fa-3x"></i>
      <br>
      Users
    </a>
  </li>
</ul>

<?php

include( 'includes/footer.php' );

?>