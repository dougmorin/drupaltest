<?php

namespace Drupal\Tests\porterstemmer\Unit;

use Drupal\Tests\UnitTestCase;

/**
 * Base methods for all PECL stem_english() tests.
 */
abstract class PorterPeclBase extends UnitTestCase {

  use TestItemsTrait;

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = array('porterstemmer');

  public $has_pecl_stem = FALSE;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->has_pecl_stem = extension_loaded('stem') && function_exists('stem_english');
  }

}
