<?php

namespace Drupal\advanced_migrations;

use Drupal\Component\Plugin\Derivative\DeriverBase;

/**
 * Deriver for the product variations.
 */
class ProductVariationsDeriver extends DeriverBase {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    // Two variations based on the fulfilment methods:
    // - 1 for in store pickup
    // - 2 for deliver.
    $map = [
      1 => [
        'field' => 'price_pick_up',
        'name' => 'in_store',
      ],
      2 => [
        'field' => 'price_delivered',
        'name' => 'delivered',
      ]
    ];
    foreach ([1, 2] as $value) {
      $derivative = $base_plugin_definition;
      // Set the constant to be used in the migration lookup of the
      // fulfilment_method migration.
      $derivative['source']['constants']['fulfilment_method'] = $value;

      // Prepare a constant to be used for building the SKU.
      $derivative['source']['constants']['_variation'] = $map[$value]['name'];

      // Map the price value from the source data.
      $derivative['process']['price/number'] = $map[$value]['field'];

      // Build the SKU based on the the product ID and fulfilment attribute.
      $derivative['process']['sku'] = [
        [
          'plugin' => 'concat',
          'source' => [
            'id',
            'constants/_variation',
          ],
          'delimiter' => '-',
        ],
      ];
      $this->derivatives[$map[$value]['name']] = $derivative;
    }

    return $this->derivatives;
  }

}
