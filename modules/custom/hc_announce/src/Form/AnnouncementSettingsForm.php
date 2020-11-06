<?php
namespace Drupal\hc_announce\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\State\StateInterface;

/**
 * Class AnnouncementSettingsForm.
 */
class AnnouncementSettingsForm extends FormBase {

  /**
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;


  /**
   * Constructs a new AnnouncementSettingsForm object.
   *
   * @param \Drupal\Core\State\StateInterface $state
   */
  public function __construct(StateInterface $state) {
    $this->state = $state;
  }


  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('state')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'announcement_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->state->get('hc_announcement');

    $form['enabled'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Enable announcement'),
        '#default_value' => !empty($config['enabled']) ? $config['enabled'] : FALSE,
      ];

    $form['announcement'] = [
        '#type' => 'details',
        '#title' => $this->t('Announcement settings'),
        '#open' => TRUE,
        '#states' => [
            'visible' => [
            ':input[name="enabled"]' => ['checked' => TRUE],
            ],
        ],
    ];

    $form['announcement']['start_date'] = [
        '#type' => 'date',
        '#title' => $this->t('Start date'),
        '#default_value' => '',
        '#required' => TRUE,
    ];

    $form['announcement']['end_date'] = [
        '#type' => 'date',
        '#title' => $this->t('End date'),
        '#default_value' => '',
        '#required' => TRUE,
    ];

    $form['announcement']['title'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Announcement title'),
        '#default_value' => '',
        '#maxlength' => 64,
        '#size' => 64,
    ];

    $form['announcement']['announcement'] = [
        '#type' => 'text_format',
        '#title' => $this->t('Announcement'),
        '#default_value' => '',
        '#required' => TRUE,
        '#format' => 'full_html',
    ];
  

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

    /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    if ($values['end_date'] < $values['start_date']) {
      $form_state->setErrorByName('end_date', $this->t('The ending date must be greater or equal than the starting date.'));
    }

    $this->state->set('hc_announcement', [
        'enabled' => $values['enabled'],
        'start_date' => $values['start_date'],
        'end_date' => $values['end_date'],
        'title' => $values['title'],
        'announcement' => $values['announcement'],
      ]);
  
      $this->messenger()->addStatus($this->t('The configuration options have been saved.'));
    }

}