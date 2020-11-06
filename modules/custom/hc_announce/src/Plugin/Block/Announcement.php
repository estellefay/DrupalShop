<?php
namespace Drupal\hc_announce\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Component\Datetime\Time;

/**
 * Provides a 'Announcement' block.
 *
 * @Block(
 *  id = "announcement",
 *  admin_label = @Translation("Announcement"),
 * )
 */
class Announcement extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var array
   */
  protected $config;

  protected $time;


  /**
   * Constructs a new Announcement object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\State\StateInterface $state
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    StateInterface $state
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->config = $state->get('hc_announcement');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    $build['#title'] = $this->config['title'];
    $build['#attributes'] = [
      'class' => ['announcement'],
    ];

    $build['announcement'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['announcement__content']],
      'content' => [
        '#type' => 'processed_text',
        '#text' => $this->config['announcement']['value'],
        '#format' => $this->config['announcement']['format'],
      ],
    ];

    if (empty($this->config['enabled'])) {
      return $build;
    }

    $now = \Drupal::time()->getRequestTime();
    $start = (new \DateTime($this->config['start_date'] . ' 00:00:00'))->getTimestamp();
    $end = (new \DateTime($this->config['end_date'] . ' 23:59:59'))->getTimestamp();
    // dump($now);
    // dump($start);
    // dump($end);

    if ($now > $start || $now < $end) {
      return $build;
    }
  }

}