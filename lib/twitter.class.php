<?php
require_once('vendor/twitteroauth.php');

class Twitter extends TwitterOAuth
{
  function getFriends($lite = false, $id = null)
  {
    $url = 'statuses/friends';
    if ($id) $url .= '/'.$id;

    return $this->get($url, array('lite' => $lite));
  }

  function getFriendsTimeLine($lite = false, $id = null)
  {
    $url = 'statuses/friends_timeline';
    if ($id) $url .= '/'.$id;

    return $this->get($url, array('lite' => $lite));
  }

  function getFollowers($lite = false, $id = null)
  {
    $url = 'statuses/followers';
    if ($id) $url .= '/'.$id;

    return $this->get($url, array('lite' => $lite));
  }

  function postStatus($status)
  {
    $params = array('status' => $status);
    return $this->post('statuses/update', $params);
  }

  function postFollow($id)
  {
    $params = array('id' => $id);
    return $this->post('friendships/create', $params);
  }

  function postUnfollow($id)
  {
    $params = array('id' => $id);
    return $this->post('friendships/destroy', $params);
  }
}
