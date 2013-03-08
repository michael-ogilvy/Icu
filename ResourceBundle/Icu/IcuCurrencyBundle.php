<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Icu\ResourceBundle\Icu;

use Symfony\Component\Icu\ResourceBundle\CurrencyBundle;

/**
 * An ICU-specific implementation of {@link \Symfony\Component\Icu\ResourceBundle\CurrencyBundleInterface}.
 *
 * This class normalizes the data of the ICU .res files to satisfy the contract
 * defined in {@link \Symfony\Component\Icu\ResourceBundle\CurrencyBundleInterface}.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IcuCurrencyBundle extends CurrencyBundle
{
    const INDEX_SYMBOL = 0;

    const INDEX_NAME = 1;

    const INDEX_FRACTION_DIGITS = 0;

    const INDEX_ROUNDING_INCREMENT = 1;

    /**
     * {@inheritdoc}
     */
    public function getCurrencyNames($locale)
    {
        $names = parent::getCurrencyNames($locale);

        $collator = new \Collator($locale);
        $collator->asort($names);

        return $names;
    }

    /**
     * {@inheritdoc}
     */
    public function getFractionDigits($currency)
    {
        $entry = $this->readEntry('supplementaldata', 'CurrencyMeta');

        if (!isset($entry[$currency][self::INDEX_FRACTION_DIGITS])) {
            // The 'DEFAULT' key contains the fraction digits and the rounding
            // increment that are common for a lot of currencies.
            // Only currencies with different values are added to the icu-data
            // (e.g: CHF and JPY)
            return $entry['DEFAULT'][self::INDEX_FRACTION_DIGITS];
        }

        return $entry[$currency][self::INDEX_FRACTION_DIGITS];
    }

    /**
     * {@inheritdoc}
     */
    public function getRoundingIncrement($currency)
    {
        $entry = $this->readEntry('supplementaldata', 'CurrencyMeta');

        if (!isset($entry[$currency][self::INDEX_ROUNDING_INCREMENT])) {
            // The 'DEFAULT' key contains the fraction digits and the rounding
            // increment that are common for a lot of currencies.
            // Only currencies with different values are added to the icu-data
            // (e.g: CHF and JPY)
            return $entry['DEFAULT'][self::INDEX_ROUNDING_INCREMENT];
        }

        return $entry[$currency][self::INDEX_ROUNDING_INCREMENT];
    }
}
