<?php

header('Access-Control-Allow-Origin: *'); 

include( 'includes/database.php' );
include( 'includes/config.php' );
include( 'includes/functions.php' );

$query = 'SELECT parties.id,
  parties.nameEnglish,
  parties.nameFrench
  FROM parties
  WHERE electionid = '.$_GET['e'].'
  ORDER BY nameEnglish';
$result = mysqli_query( $connect, $query );

$parties = array();

while( $record = mysqli_fetch_assoc( $result ) )
{
  foreach( $record as $key => $value )
  {
    $record[$key] = htmlentities( $value );
  }
  $parties[$record['id']] = $record;
}

$query = 'SELECT candidates.id,
  candidates.first,
  candidates.last,
  candidates.partyId,
  candidates.ridingId,
  candidates.votes
  FROM candidates
  WHERE electionid = '.$_GET['e'].'
  ORDER BY last,first';
$result = mysqli_query( $connect, $query );

$candidates = array();

while( $record = mysqli_fetch_assoc( $result ) )
{
  foreach( $record as $key => $value )
  {
    $record[$key] = htmlentities( $value );
  }
  $record['photo'] = 'http://humberelections.professoradamthomas.com/image.php?type=candidate&id='.$record['id'];
  $candidates[$record['id']] = $record;
}

$query = 'SELECT ridings.id,
  ridings.nameEnglish,
  ridings.nameFrench,
  ridings.provinceId,
  ridings.officialId
  FROM ridings
  WHERE electionid = '.$_GET['e'].'
  ORDER BY officialId ASC';
$result = mysqli_query( $connect, $query );

$ridings = array();

while( $record = mysqli_fetch_assoc( $result ) )
{
  
  foreach( $record as $key => $value )
  {
    $record[$key] = htmlentities( $value );
  }
  $ridings[$record['id']] = $record;
  
}

$query = 'SELECT articles.id,
  articles.title,
  articles.author,
  articles.content,
  articles.youtube,
  articles.featured,
  articles.status,
  articles.date,
  articles.cutline,
  (
    SELECT GROUP_CONCAT(articleRidings.ridingId)
    FROM ridings
    INNER JOIN articleRidings
    ON ridings.id = articleRidings.ridingId
    WHERE articleRidings.articleId = articles.id
  ) AS ridings,
  (
    SELECT GROUP_CONCAT(articleCandidates.candidateId)
    FROM candidates
    INNER JOIN articleCandidates
    ON candidates.id = articleCandidates.candidateId
    WHERE articleCandidates.articleId = articles.id
  ) AS candidates,
  (
    SELECT GROUP_CONCAT(articleParties.partyId)
    FROM parties
    INNER JOIN articleParties
    ON parties.id = articleParties.partyId
    WHERE articleParties.articleId = articles.id
  ) AS parties
  FROM articles
  WHERE electionid = '.$_GET['e'].'
  ORDER BY date ASC';
$result = mysqli_query( $connect, $query );

$data = array();

while( $record = mysqli_fetch_assoc( $result ) )
{
  
  foreach( $record as $key => $value )
  {
    if( $key != 'content' ) $record[$key] = htmlentities( $value );
  }

  $articleParties = explode( ',', $record['parties'] );  
  $record['parties'] = array();
  foreach( $articleParties as $key => $value )
  {
    if( $value ) $record['parties'][$value] = $parties[$value];
  }
  
  $articleCandidates = explode( ',', $record['candidates'] );  
  
  $record['candidates'] = array();
  foreach( $articleCandidates as $key => $value )
  {
    if( $value ) $record['candidates'][$value] = $candidates[$value];
  }
  
  $articleRidings = explode( ',', $record['ridings'] );  
  $record['ridings'] = array();
  foreach( $articleRidings as $key => $value )
  {
    if( $value ) $record['ridings'][$value] = $ridings[$value];
  }
  
  $record['photo'] = 'http://humberelections.professoradamthomas.com/image.php?type=article&id='.$record['id'];
  $data[$record['id']] = $record;
  
}

if( isset( $_GET['sample'] ) )
{
  pre( $data );  
}
else
{

  if( isset( $_GET['callback'] ) ) echo $_GET['callback'].'(';

  echo json_encode( $data );

  if( isset( $_GET['callback'] ) ) echo ')';

}

?>