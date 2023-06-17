<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

secure();

include( 'includes/header.php' );

?>

  <h2>JSON Feeds</h2>

  <h3>Results for all Ridings</h3>
  <p><a href="json-results.php?e=<?php echo $_SESSION['e']; ?>"><i class="fas fa-rss"></i> http://humberelections.professoradamthomas.com/json-results.php?e=<?php echo $_SESSION['e']; ?></a></p>
  <p><a href="json-results.php?e=<?php echo $_SESSION['e']; ?>&sample"><i class="fas fa-search"></i> View Sample Data</a></p>

  <h3>Results for Prime Minister</h3>
  <p><a href="json-prime.php?e=<?php echo $_SESSION['e']; ?>"><i class="fas fa-rss"></i> http://humberelections.professoradamthomas.com/json-prime.php?e=<?php echo $_SESSION['e']; ?></a></p>
  <p><a href="json-prime.php?e=<?php echo $_SESSION['e']; ?>&sample"><i class="fas fa-search"></i> View Sample Data</a></p>

  <hr>

  <h3>List Articles</h3>
  <p><a href="json-articles.php?e=<?php echo $_SESSION['e']; ?>"><i class="fas fa-rss"></i> http://humberelections.professoradamthomas.com/json-articles.php?e=<?php echo $_SESSION['e']; ?></a></p>
  <p><a href="json-articles.php?e=<?php echo $_SESSION['e']; ?>&sample"><i class="fas fa-search"></i> View Sample Data</a>

  <hr>

  <h3>List Team Members</h3>
  <p><a href="json-team.php?e=<?php echo $_SESSION['e']; ?>"><i class="fas fa-rss"></i> http://humberelections.professoradamthomas.com/json-team.php?e=<?php echo $_SESSION['e']; ?></a></p>
  <p><a href="json-team.php?e=<?php echo $_SESSION['e']; ?>&sample"><i class="fas fa-search"></i> View Sample Data</a>

  <hr>

  <h3>List of Candidates</h3>
  <p><a href="json-candidates.php?e=<?php echo $_SESSION['e']; ?>"><i class="fas fa-rss"></i> http://humberelections.professoradamthomas.com/json-candidates.php?e=<?php echo $_SESSION['e']; ?></a></p>
  <p><a href="json-candidates.php?e=<?php echo $_SESSION['e']; ?>&sample"><i class="fas fa-search"></i> View Sample Data</a>

  <h3>List of Provinces</h3>
  <p><a href="json-provinces.php?e=<?php echo $_SESSION['e']; ?>"><i class="fas fa-rss"></i> http://humberelections.professoradamthomas.com/json-provinces.php?e=<?php echo $_SESSION['e']; ?></a></p>
  <p><a href="json-provinces.php?e=<?php echo $_SESSION['e']; ?>&sample"><i class="fas fa-search"></i> View Sample Data</a>

  <h3>List of Parties</h3>
  <p><a href="json-parties.php?e=<?php echo $_SESSION['e']; ?>"><i class="fas fa-rss"></i> http://humberelections.professoradamthomas.com/json-parties.php?e=<?php echo $_SESSION['e']; ?></a></p>
  <p><a href="json-parties.php?e=<?php echo $_SESSION['e']; ?>&sample"><i class="fas fa-search"></i> View Sample Data</a>

  <h3>List of Ridings</h3>
  <p><a href="json-ridings.php?e=<?php echo $_SESSION['e']; ?>"><i class="fas fa-rss"></i> http://humberelections.professoradamthomas.com/json-ridings.php?e=<?php echo $_SESSION['e']; ?></a></p>
  <p><a href="json-ridings.php?e=<?php echo $_SESSION['e']; ?>&sample"><i class="fas fa-search"></i> View Sample Data</a>

  <?php

include( 'includes/footer.php' );

?>