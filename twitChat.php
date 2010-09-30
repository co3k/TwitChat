<?php

set_include_path(get_include_path().PATH_SEPARATOR.'./lib');
require_once("Net/SmartIRC.php");
require_once("twitter.class.php");
require_once("config.php");

class TwitChat
{
  function __construct(&$irc, &$twitter)
  {
    $irc->setUseSockets(TRUE);
    $irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '^quit$', $this, 'quit');
    $irc->registerActionHandler(SMARTIRC_TYPE_CHANNEL, '.*', $this, 'listen');
    $this->twitter = &$twitter;
  }

  public function listen(&$irc, &$data)
  {
    // twitter
    $post = $this->encode($data->nick).'> '.$this->encode($data->message);
    if (mb_strlen($post) > 140)
    {
      $post = mb_substr($post, 0, 137).'..';
    }
    $this->twitter->postStatus($post);
  }

  public function quit(&$irc)
  {
    $irc->quit('bye all.');
  }

  protected function encode($text)
  {
    return mb_convert_encoding($text, mb_internal_encoding(), IRC_ENCODING);
  }
}

$irc = new Net_SmartIRC();
$twitter = new Twitter(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
$bot = new TwitChat($irc, $twitter);

$irc->connect(IRC_HOST, IRC_PORT);
$irc->login(IRC_NICK, IRC_INFO);
$irc->join(array(IRC_CHANNEL));
$irc->listen();
