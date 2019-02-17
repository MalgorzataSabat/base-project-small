<?php

class Base_View_Helper_Timestamp extends Zend_View_Helper_HtmlElement
{
  const MINUTE = 60;
  const HOUR = 3600;
  const DAY = 86400;
  const WEEK = 604800;
  const MONTH = 2419200; // 4 weeks approximation
  const YEAR = 31536000; // 365 days approximation
  
  protected $_tag = 'span';

  public function timestamp($time = null, $attribs = array())
  {
    if( null === $time )
    {
      $time = time();
    }

    else if( is_string($time) && !is_numeric($time) )
    {
      $time = strtotime($time);
    }
    
    else if( $time instanceof Zend_Date )
    {
      $time = $time->toValue();
    }

    if( !is_numeric($time) )
    {
      throw new Zend_View_Exception(sprintf('Unknown timestamp format: %s (%s) ', $time, gettype($time)));
    }
    
    // Prepare attributes
    // Format: 'Tue, 08 Dec 2009 19:59:52 -0800'
    $tag = $this->_tag;
    if( isset($attribs['tag']) ) {
      $tag = $attribs['tag'];
      unset($attribs['tag']);
    }
    $attribs['class'] = ( empty($attribs['class']) ? 'timestamp' : $attribs['class'] . ' timestamp' );
    $attribs['title'] = date("D, j M Y G:i:s O", $time);

    return '<'
      . $tag
      . $this->_htmlAttribs($attribs)
      . '>'
      . $this->calculateDefaultTimestamp($time)
      . '</'
      . $tag
      . '>'
      ;
  }

  public function setTag($tag)
  {
    $this->_tag = $tag;
    return $this;
  }

  public function calculateDefaultTimestamp($time)
  {
    $now = time();
    $deltaNormal = $time - $now;
    //$deltaNormal = $now - $time;
    $delta = abs($deltaNormal);
    $isPlus = ($deltaNormal > 0);
    $zDate = new Zend_Date($time);

    // Right now
    if( $delta < 1 )
    {
      $val = null;
      if( $isPlus ) {
        $key = 'timestamp now';
      } else {
        $key = 'timestamp now';
      }
    }

    // less than a minute
    else if( $delta < 60 )
    {
      $val = null;
      if( $isPlus ) {
        $key = 'timestamp in a few seconds';
      } else {
        $key = 'timestamp a few seconds ago';
      }
    }



    // less than an hour ago
    else if( $delta < self::HOUR )
    {
      $val = floor($delta / 60);
      if( $isPlus ) {
        $key = array('timestamp in %s minute', 'timestamp in %s minutes', $val);
      } else {
        $key = array('timestamp one minute ago', 'timestamp %s minutes ago', $val);
      }
    }
    // less than 12 hours ago, or less than a day ago and same day
    else if( $delta < self::HOUR * 12 || ($delta < self::DAY && date('d', $time) == date('d', $now)) )
    {
      $val = floor($delta / (60 * 60));
      if( $isPlus ) {
        $key = array('timestamp in %s hour', 'timestamp in %s hours', $val);
      } else {
        $key = array('timestamp one hour ago', 'timestamp %s hours ago', $val);
      }
    }

    // less than a week ago and same week
    else if( $delta < self::WEEK && date('W', $time) == date('W', $now) )
    {
      return $zDate->toString('EE HH:mm');
    }

    // less than a year and same year
    else if( $delta < self::YEAR && date('Y', $time) == date('Y', $now) )
    {
        return $zDate->toString('dd MMM, HH:mm');
    }

    // Otherwise use the full date
    else
    {
        return $zDate->toString('dd MMM YYYY');
    }


      $translator = $this->view->getHelper('translate');
    if( $translator ) {
        if(is_array($key) && count($key) > 1){
            $key = @$val > 1 ? $key[1] : $key[0];
        }


        return sprintf($translator->translate($key), $val);
    } else {
      $key = is_array($string) ? $string[0] : $key;
      return sprintf($string, $val);
    }
  }
}