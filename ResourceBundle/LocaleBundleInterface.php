<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Icu\ResourceBundle;

/**
 * Gives access to locale-related ICU data.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface LocaleBundleInterface extends ResourceBundleInterface
{
    /**
     * Returns the name of a locale.
     *
     * @param string $locale   The locale to return the name in.
     * @param string $ofLocale The locale to return the name of (e.g. "de_AT").
     *
     * @return string|null The name of the locale or NULL if not found.
     */
    public function getLocaleName($locale, $ofLocale);

    /**
     * Returns the names of all known locales.
     *
     * @param string $locale The locale to return the name in.
     *
     * @return string[] A list of locale names indexed by locale codes.
     */
    public function getLocaleNames($locale);
}
