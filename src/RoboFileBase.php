<?php

namespace Drupal\Robo;

use Cheppers\Robo\Phpcs\LoadPhpcsTasks;
use Cheppers\LintReport\Reporter\BaseReporter;
use League\Container\ContainerInterface;
use Robo\Tasks;

/**
 * Provides a base class for wrapping useful Drupal tasks.
 *
 * In the project root create a new file called RoboFile.php containing a class
 * with the same name, extending this abstract class, and customize it for your
 * needs.
 * @code
 * class RoboFile extends RoboFileBase {
 *
 *   const PHPCS_IGNORES = [
 *     ...
 *     // Ignore PHPCS check on Twitter Bootstrap library.
 *     'web/themes/custom/mytheme/bootstrap/',
 *     ...
 *   ];
 *
 *   ...
 * }
 * @endcode
 */
abstract class RoboFileBase extends Tasks {

  use LoadPhpcsTasks;

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
    $files = $files ?: static::PHPCS_FILES;
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
