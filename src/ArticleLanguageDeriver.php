<?php

namespace Drupal\advanced_migrations;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Deriver for the article translations.
 */
class ArticleLanguageDeriver extends DeriverBase implements ContainerDeriverInterface {

  /**
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * ArticleLanguageDeriver constructor.
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
    $url = 'modules/custom/advanced_migrations/data/articles/languages/' . $language->getId();
    $base_plugin_definition['source']['urls'][] = $url;

    return $base_plugin_definition;
  }

}
