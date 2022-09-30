<?php

/**
 * @file
 * Creates a block which displays the the time
 */

namespace Drupal\time_ticker\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\time_ticker\TimeService;
use Drupal\Core\PageCache\ResponsePolicy\KillSwitch;


/**
 * Provides the time ticker block.
 *
 * @Block(
 *   id = "time_ticker",
 *   admin_label = @Translation("Time ticker Block")
 * )
 */
class TimeBlock extends BlockBase implements ContainerFactoryPluginInterface{

  /**
   * @var TimeService $time
   */
  protected $time;

    /**
   * The kill switch.
   *
   * @var \Drupal\Core\PageCache\ResponsePolicy\KillSwitch
   */
  protected $killSwitch;

  /**
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param Drupal\time_ticker\TimeService $time
   * @param \Drupal\Core\PageCache\ResponsePolicy\KillSwitch $killSwitch
   *  The page cache kill switch service.
   */

  public function __construct(array $configuration, $plugin_id, $plugin_definition, TimeService $time, KillSwitch $killSwitch) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->time = $time;
    $this->killSwitch = $killSwitch;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('time_ticker.time'),
      $container->get('page_cache_kill_switch')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
/* Added $this->killSwitch->trigger(); to invalidate the cache for anonymous users as well */
    $this->killSwitch->trigger();
    $config   = \Drupal::config('time_ticker.settings');
    $country  = $config->get('country');
    $city     = $config->get('city');
    $date_time     = $this->time->getTime();
    return [
      '#theme' => 'time_ticker_block',
      '#current_time' => $date_time,
      '#country' => $country,
      '#city' => $city,
      '#cache' => [
        /* This will work for logged in users only */
        'max-age' => 0,
      ],
    ];
   
  }
   
}
