<?php
require_once '../grplib-php/init.php';
require_once 'lib/htm.php';

$pagetitle = 'Communities'; $mnselect = 'community';
print printHeader('old');
print printMenu('old');

require_once '../grplib-php/community-helper.php';
require_once 'lib/htmCommunity.php';
print '<div id="main-body">
';
print '
<div class="body-content" id="community-top">


  <div class="headline">
    <h2 class="headline-text">Communities</h2>
    <form method="GET" action="/titles/search" class="search">
      <input type="text" name="query" placeholder="Search Communities" minlength="2" maxlength="20"><input type="submit" value="q" title="Search">
    </form>
  </div>
';
if(!empty($_SESSION['pid'])) {
$search_favorite_communitities = $mysql->query('SELECT * FROM favorites WHERE favorites.pid = "'.$_SESSION['pid'].'" ORDER BY created_at DESC');
if($search_favorite_communitities->num_rows != 0) {
print '<h3 class="label">Favorite Communities</h3>
<ul class="list community-list">
';
while($favorites = $search_favorite_communitities->fetch_assoc()) {
$fav_comm = $mysql->query('SELECT * FROM communities WHERE communities.community_id = "'.$favorites['community_id'].'"')->fetch_assoc();
printCommunity($fav_comm);
}
print '
  </ul>
<div class="buttons-content">
      <a href="/communities/favorites" class="button">Show More</a>
    </div>';

} }
print '
  <div id="identified-user-banner">
    <a href="/identified_user_posts" data-pjax="#body" class="list-button us">
      <span class="title">Get the latest news here!</span>
      <span class="text">Posts from Verified Users</span>
    </a>
  </div>


  <div id="tab-wiiu-body" class="tab-body">
    
    

    <h3 class="label label-wiiu">
      New Communities
      
    </h3>

    <ul class="list community-list community-title-list">
';
$titles_show1 = $mysql->query('SELECT * FROM titles WHERE titles.platform_id IS NOT NULL AND titles.hidden != 1 ORDER BY titles.created_at DESC LIMIT 20');
while($titles_show = $titles_show1->fetch_assoc()) {
print printTitle($titles_show, ($mysql->query('SELECT * FROM communities WHERE communities.olive_title_id = "'.$titles_show['olive_title_id'].'" AND communities.type != "4" LIMIT 2')->num_rows == 2 ? true : false));
}
print '

    </ul>
    
  </div>
  

  <h3 class="label">Special</h3>
  <ul class="list community-list community-title-list">

';
$titles_show2 = $mysql->query('SELECT * FROM titles WHERE titles.platform_id IS NULL AND titles.hidden != 1 ORDER BY titles.created_at DESC LIMIT 20');
while($titles_show3 = $titles_show2->fetch_assoc()) {
print printTitle($titles_show3, ($mysql->query('SELECT * FROM communities WHERE communities.olive_title_id = "'.$titles_show['olive_title_id'].'" AND communities.type != "4" LIMIT 2')->num_rows == 2 ? true : false));
}
print '

  </ul>

</div>

      </div>';


print printFooter('old');
grpfinish($mysql);

