<?php

/**
 * @file
 * Contains the Time Ticker service.
 */

namespace Drupal\time_ticker;

use Drupal\Core\Datetime\DrupalDateTime;

class TimeService {

  /**
   * Get current time as per selected timezone.
   *
   */
  public function getTime() { 
    
    $config = \Drupal::config('time_ticker.settings');
    $timezone = $config->get('timezone');

    
    $current_time = new DrupalDateTime('now','UTC');

    $userTimezone = new \DateTimeZone($timezone);

    $date_time = new DrupalDateTime();

    $timezone_offset = $userTimezone->getOffset($date_time->getPhpDateTime());

    $time_interval = \DateInterval::createFromDateString($timezone_offset . 'seconds');

    $current_time->add($time_interval);

    $result = $current_time->format('jS M Y - h:i A');

    
    return $result; 

  }
 
}
