<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Sharapov\ZendFilter\I18n;

use Locale;

class AlnumDash extends \Zend\I18n\Filter\Alnum
{
  /**
   * @var array
   */
  protected $options = [
      'locale'            => null,
      'allow_white_space' => false,
      'allow_dashes'      => false,
      'allow_underscores' => false
  ];

  /**
   * Sets the allow_dashes option
   *
   * @param  bool $flag
   * @return AlnumDash Provides a fluent interface
   */
  public function setAllowDashes($flag = true)
  {
    $this->options['allow_dashes'] = (bool) $flag;
    return $this;
  }

  /**
   * Whether dashes is allowed
   *
   * @return bool
   */
  public function getAllowDashes()
  {
    return $this->options['allow_dashes'];
  }

  /**
   * Sets the allow_underscores option
   *
   * @param  bool $flag
   * @return AlnumDash Provides a fluent interface
   */
  public function setAllowUnderscores($flag = true)
  {
    $this->options['allow_underscores'] = (bool) $flag;
    return $this;
  }

  /**
   * Whether underscores is allowed
   *
   * @return bool
   */
  public function getAllowUnderscores()
  {
    return $this->options['allow_underscores'];
  }

  /**
   * Defined by Zend\Filter\FilterInterface
   *
   * Returns $value as string with all non-alphanumeric, dash and underscore characters removed
   *
   * @param  string|array $value
   * @return string|array
   */
  public function filter($value)
  {
    if (!is_scalar($value) && !is_array($value)) {
      return $value;
    }

    $whiteSpace = $this->options['allow_white_space'] ? '\s' : '';
    $dashes = $this->options['allow_dashes'] ? '-' : '';
    $underscores = $this->options['allow_underscores'] ? '_' : '';
    $language   = Locale::getPrimaryLanguage($this->getLocale());

    if (!static::hasPcreUnicodeSupport()) {
      // POSIX named classes are not supported, use alternative a-zA-Z0-9 match
      $pattern = '/[^a-zA-Z0-9' . $whiteSpace . $dashes . $underscores . ']/';
    } elseif ($language == 'ja'|| $language == 'ko' || $language == 'zh') {
      // Use english alphabet
      $pattern = '/[^a-zA-Z0-9'  . $whiteSpace . $dashes . $underscores . ']/u';
    } else {
      // Use native language alphabet
      $pattern = '/[^\p{L}\p{N}' . $whiteSpace . $dashes . $underscores . ']/u';
    }

    return preg_replace($pattern, '', $value);
  }
}
