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
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param Drupal\time_ticker\TimeService $time
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, TimeService $time) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('time_ticker.time')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $config = \Drupal::config('time_ticker.settings');
    $country = $config->get('country');
    $city     = $config->get('city');

    return [
      '#theme' => 'time_ticker_block',
      '#current_time' => $this->time->getTime(),
      '#country' => $country,
      '#city' => $city,
      '#cache' => [
        'max-age' => 0,
      ],
    ];
   
  }
   
}
