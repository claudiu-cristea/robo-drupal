<?php

namespace Drupal\Robo;

/**
 * Provides an interface for Robo Drupal Task.
 */
interface RoboDrupalFileInterface {

  /**
   * The PHPCS Drupal sniffer path.
   *
   * @var string
   */
  const PHPCS_DRUPAL_SNIFFER = './vendor/drupal/coder/coder_sniffer/Drupal';

  /**
   * A list of files or directories to be scanned by PHPCS.
   *
   * @var string[]
   */
  const PHPCS_FILES = [
    'web/modules/custom/',
    'web/profiles/custom/',
    'web/themes/custom/',
  ];

  /**
   * A list of file extensions to be scanned by PHPCS.
   *
   * @var string[]
   */
  const PHPCS_EXTENSIONS = [
    'php',
    'inc',
    'module',
    'install',
    'info',
    'test',
    'profile',
    'theme',
    'css',
    'js',
  ];

  /**
   * A list of ignore patterns to not be scanned by PHPCS.
   *
   * @var string[]
   */
  const PHPCS_IGNORES = [
    '*.gif',
    '*.png',
    '*.min.css',
    '*.min.css',
  ];

  /**
   * Distribution configuration file.
   *
   * Extensions of \Drupal\Robo\RoboFileBase can override this value with one
   * specific to the project. By overriding this value to NULL, the
   * configuration read from CONFIG_FILE_LOCAL will be initialized without a
   * fallback.
   *
   * Allowed file formats: xml, ini, php, yaml and json.
   *
   * @var string|null
   */
  const CONFIG_FILE_DIST = 'config.dist.yml';

  /**
   * Local configuration file.
   *
   * Extensions of \Drupal\Robo\RoboFileBase can override this value with one
   * specific to the project. Used for the current installations specific
   * configurations. Configurations from this file are overriding those in
   * CONFIG_FILE_DIST. In case this const is overwritten with NULL, only
   * CONFIG_FILE_DIST will be used to compute the project configuration.
   *
   * Allowed file formats: xml, ini, php, yaml and json.
   *
   * @var string|null
   */
  const CONFIG_FILE_LOCAL = 'config.local.yml';

}
