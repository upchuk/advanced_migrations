<?php

namespace Drupal\advanced_migrations;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Deriver for the category translations.
 */
class CategoriesLanguageDeriver extends DeriverBase implements ContainerDeriverInterface {

  /**
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * ProductGroupsDeriver constructor.
   *
   * @param \Drupal\Core\Language\LanguageManagerInterface $languageManager
   */
  public function __construct(LanguageManagerInterface $languageManager) {
    $this->languageManager = $languageManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $languages = $this->languageManager->getLanguages();
    foreach ($languages as $language) {
      // We skip EN as that is the original language.
      if ($language->getId() === 'en') {
        continue;
      }

      $derivative = $this->getDerivativeValues($base_plugin_definition, $language);
      $this->derivatives[$language->getId()] = $derivative;
    }

    return $this->derivatives;
  }

  /**
   * Creates a derivative definition for each available language.
   *
   * @param array $base_plugin_definition
   * @param LanguageInterface $language
   *
   * @return mixed
   */
  protected function getDerivativeValues(array $base_plugin_definition, LanguageInterface $language) {
    $base_plugin_definition['process']['name'] = [
      'plugin' => 'skip_on_empty',
      'method' => 'row',
      'source' => 'label_' . $language->getId(),
    ];

    $base_plugin_definition['process']['langcode'] = [
      'plugin' => 'default_value',
      'default_value' => $language->getId(),
    ];

    return $base_plugin_definition;
  }

}
