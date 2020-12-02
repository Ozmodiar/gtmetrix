<?php

namespace Drupal\gtmetrix\Helper;

use Drupal\Core\Config\ConfigFactory;

/**
 * Defines the GtMetrixHelper class.
 */
class GtMetrixHelper {

  /**
   * GTmetrix configuration object.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * GtMetrixHelper constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The Drupal config factory.
   */
  public function __construct(ConfigFactory $config_factory) {
    $this->config = $config_factory->getEditable('gtmetrix.settings');
  }

  /**
   * Determine hook_requirements severity based on PageSpeed and YSlow scores.
   *
   * @param int $pagespeed_score
   *   The PageSpeed score.
   * @param int $yslow_score
   *   The YSlow score.
   *
   * @return int
   *   The hook_requirements severity.
   */
  public function determineSeverity(int $pagespeed_score, int $yslow_score) {
    if ($pagespeed_score > $this->config->get(GTMETRIX_OK_THRESHOLD) && $yslow_score > $this->config->get(GTMETRIX_OK_THRESHOLD)) {
      return GTMETRIX_SEVERITY_OK;
    }
    if ($pagespeed_score > $this->config->get(GTMETRIX_WARNING_THRESHOLD) && $yslow_score > $this->config->get(GTMETRIX_WARNING_THRESHOLD)) {
      return GTMETRIX_SEVERITY_WARNING;
    }

    return GTMETRIX_SEVERITY_ERROR;
  }

}
