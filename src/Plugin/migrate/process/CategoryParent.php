<?php

namespace Drupal\advanced_migrations\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipProcessException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Determines the parent of the category based on the ID.
 *
 * @MigrateProcessPlugin(
 *   id = "category_parent"
 * )
 */
class CategoryParent extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (strlen($value) === 1) {
      // If it's only 1 letter, it means it's a top level term without a parent
      // so we skip this process.
      throw new MigrateSkipProcessException();
    }

    // Otherwise, we determine the parent based on the letters before the last.
    $split = str_split($value);
    array_pop($split);
    return implode('', $split);
  }

}
