<?php

require_once __DIR__.'/../vendor/autoload.php';

class opRedisRanking
{
  private $predis;
  private $key;

  public function __construct($key)
  {
    $this->predis = new Predis\Client(array(
      'host' => sfConfig::get('op_redis_server_host', '127.0.0.1'),
      'port' => sfConfig::get('op_redis_server_port', '6379'),
    ));

    $this->key = $key;
  }

  public function setScore($id, $score)
  {
    return $this->predis->zadd($this->key, $score, $id);
  }

  public function getScore($id)
  {
    return $this->predis->zscore($this->key, $id);
  }

  public function incrementScore($id, $increment = 1)
  {
    return $this->predis->zincrby($this->key, $increment, $id);
  }

  public function getRank($id)
  {
    return $this->predis->zrank($this->key, $id);
  }

  public function getRevRank($id)
  {
    return $this->predis->zrevrank($this->key, $id);
  }

  public function getRange($start = 0, $stop = -1, $withScores = false)
  {
    if ($withScores)
    {
      return $this->predis->zrange($this->key, $start, $stop, 'WITHSCORES');
    }
    else
    {
      return $this->predis->zrange($this->key, $start, $stop);
    }
  }

  public function getRevRange($start = 0, $stop = -1, $withScores = false)
  {
    if ($withScores)
    {
      return $this->predis->zrevrange($this->key, $start, $stop, 'WITHSCORES');
    }
    else
    {
      return $this->predis->zrevrange($this->key, $start, $stop);
    }
  }
}
