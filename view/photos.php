<?php
if (isset($_REQUEST['gallery']) && isset($_REQUEST['slug'])){
  $g = Util::clearXss($_REQUEST['gallery']);
  $s = Util::clearXss($_REQUEST['slug']);


  $client = ORM::for_table('client')->where('slug',$s)->find_one();

  if (is_object($client)){
    
    if (Photo::isAuthorized($client->id)){
        $clientPath = 'uploads/'.$client->slug.'/'.$g;
        $photos = ORM::for_table('photo')->where('gallery_id',$g)->find_many();
      }
    }
}
?>