<?php

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

pre( $_POST );

$query = 'UPDATE '.$_POST['type'].' SET photo = "'.$_POST['image'].'" WHERE id = '.$_POST['id'].' LIMIT 1';
echo $query;

mysqli_query( $connect, $query );