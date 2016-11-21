<?php

namespace Drupal\Robo;

use Cheppers\Robo\Phpcs\LoadPhpcsTasks;
use Cheppers\LintReport\Reporter\BaseReporter;
use League\Container\ContainerInterface;
use NordCode\RoboParameters\FileConfigurable;
use Robo\Tasks;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

/**
 * Provides a base class for wrapping useful Drupal tasks.
 *
 * In the project root create a new file called RoboFile.php containing a class
 * with the same name, extending this abstract class, and customize it for your
 * needs.
 * @code
 * class RoboFile extends RoboDrupalFileBase {
 *
 *   const PHPCS_IGNORES = [
 *     ...
 *     // Ignore PHPCS check on Twitter Bootstrap library.
 *     'web/themes/custom/mytheme/bootstrap/',
 *     ...
 *   ];
 *
 *   // Use a different configuration file name and location.
 *   const CONFIG_FILE_LOCAL = 'config/site_config.xml';
 *
 *   ...
 * }
 * @endcode
 */
abstract class RoboDrupalFileBase extends Tasks implements RoboDrupalFileInterface {

  use LoadPhpcsTasks;
  use FileConfigurable;

  /**
   * Constructs a RoboFile for Drupal tasks.
   */
  public function __construct() {
    $has_dist_config = static::CONFIG_FILE_DIST !== NULL;
    $has_local_config = static::CONFIG_FILE_LOCAL !== NULL;
    if ($has_dist_config && !file_exists(static::CONFIG_FILE_DIST)) {
      throw new FileNotFoundException('A file named ' . static::CONFIG_FILE_DIST . ' should exist.');
    }
    if ($has_local_config && !file_exists(static::CONFIG_FILE_LOCAL)) {
      throw new FileNotFoundException('A file named ' . static::CONFIG_FILE_LOCAL . ' should be created.');
    }

    if ($has_dist_config && $has_local_config) {
      $this
        ->useBoilerplate(static::CONFIG_FILE_DIST)
        ->loadConfiguration(static::CONFIG_FILE_LOCAL);
    }
    elseif ($has_dist_config && !$has_local_config) {
      $this->loadConfiguration(static::CONFIG_FILE_DIST);
    }
    elseif (!$has_dist_config && $has_local_config) {
      $this->loadConfiguration(static::CONFIG_FILE_LOCAL);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setContainer(ContainerInterface $container) {
    $this->container = $container;
    BaseReporter::lintReportConfigureContainer($this->container);
    return $this;
  }

  /**
   * Checks files for Drupal coding standards.
   *
   * If a space delimited list of files is provided, only those files will be
   * checked. If the list of files is missed, all custom code directories are
   * scanned.
   *
   * @param string[] $files
   *   (optional) A list of files to be checked. If missed the default custom
   *   code locations will be scanned.
   *
   * @return \Robo\Result
   *   A robo result object.
   */
  public function phpcs(array $files) {
    $files = $files ? array_filter($files, function ($file) {
      foreach (static::PHPCS_FILES as $pattern) {
        if (strpos($file, $pattern) === 0) {
          return TRUE;
        }
      }
      return FALSE;
    }) : static::PHPCS_FILES;

    return $this->taskPhpcsLintFiles()
      ->setStandard(static::PHPCS_DRUPAL_SNIFFER)
      ->setColors(TRUE)
      ->setFailOn('warning')
      ->addLintReporter('verbose:StdOutput', 'lintVerboseReporter')
      ->setExtensions(static::PHPCS_EXTENSIONS)
      ->setFiles($files)
      ->setIgnore(static::PHPCS_IGNORES)
      ->run();
  }

}
