<?php

namespace Drupal\gtmetrix\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Implements a settings form for the GTmetrix configuration.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * An editable config.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $gtMetrixConfig;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'gtmetrix_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['gtmetrix.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('date.formatter')
    );
  }

  /**
   * Constructs a new object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, DateFormatterInterface $date_formatter) {
    parent::__construct($config_factory);
    $this->gtMetrixConfig = $this->config('gtmetrix.settings');
    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form[GTMETRIX_USERNAME] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#default_value' => $this->gtMetrixConfig->get(GTMETRIX_USERNAME),
      '#description' => $this->t('Your GTmetrix username.'),
    ];

    $form[GTMETRIX_API_KEY] = [
      '#type' => 'textfield',
      '#title' => $this->t('API key'),
      '#default_value' => $this->gtMetrixConfig->get(GTMETRIX_API_KEY),
      '#description' => $this->t('Your GTmetrix API key.'),
    ];

    $form[GTMETRIX_URL] = [
      '#type' => 'textfield',
      '#title' => $this->t('URL'),
      '#default_value' => $this->gtMetrixConfig->get(GTMETRIX_URL),
      '#description' => $this->t('The URL that should be checked by GTmetrix.'),
    ];

    $intervals = [
      3600,
      10800,
      21600,
      32400,
      43200,
      86400,
      172800,
      259200,
      604800,
      1209600,
      2419200,
    ];
    $period = array_map([$this->dateFormatter, 'formatInterval'], array_combine($intervals, $intervals));
    $period[0] = $this->t('Never');

    $form[GTMETRIX_INTERVAL] = [
      '#type' => 'select',
      '#title' => $this->t('Run test every'),
      '#default_value' => $this->gtMetrixConfig->get(GTMETRIX_INTERVAL),
      '#options' => $period,
      '#description' => $this->t('Requires a correctly configured <a href=":cron">cron maintenance task</a>.', [':cron' => Url::fromRoute('system.status')->toString()]),
    ];

    $form[GTMETRIX_OK_THRESHOLD] = [
      '#type' => 'number',
      '#title' => $this->t('OK Threshold'),
      '#default_value' => $this->gtMetrixConfig->get(GTMETRIX_OK_THRESHOLD),
      '#description' => $this->t('The percentage above which the metrics are considered OK.'),
    ];

    $form[GTMETRIX_WARNING_THRESHOLD] = [
      '#type' => 'number',
      '#title' => $this->t('Warning Threshold'),
      '#default_value' => $this->gtMetrixConfig->get(GTMETRIX_WARNING_THRESHOLD),
      '#description' => $this->t('The percentage above which the metrics should throw a warning. Any percentage below this value will throw an error.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->gtMetrixConfig
      ->set(GTMETRIX_USERNAME, $form_state->getValue(GTMETRIX_USERNAME))
      ->set(GTMETRIX_API_KEY, $form_state->getValue(GTMETRIX_API_KEY))
      ->set(GTMETRIX_URL, $form_state->getValue(GTMETRIX_URL))
      ->set(GTMETRIX_INTERVAL, $form_state->getValue(GTMETRIX_INTERVAL))
      ->set(GTMETRIX_OK_THRESHOLD, $form_state->getValue(GTMETRIX_OK_THRESHOLD))
      ->set(GTMETRIX_WARNING_THRESHOLD, $form_state->getValue(GTMETRIX_WARNING_THRESHOLD))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
