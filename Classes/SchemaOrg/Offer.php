<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2017 Andre Wuttig <wuttig@portrino.de>, portrino GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace Portrino\PxSemantic\SchemaOrg;

/**
 * An offer to transfer some rights to an item or to provide a service — for example, an offer to sell tickets to an event, to rent the DVD of a movie, to stream a TV show over the internet, to repair a motorcycle, or to loan a book.\\n\\nFor \[GTIN\](http://www.gs1.org/barcodes/technical/idkeys/gtin)-related fields, see \[Check Digit calculator\](http://www.gs1.org/barcodes/support/check\_digit\_calculator) and \[validation guide\](http://www.gs1us.org/resources/standards/gtin-validation-guide) from \[GS1\](http://www.gs1.org/).
 *
 * @see http://schema.org/Offer Documentation on Schema.org
 *
 * @author Andre Wuttig<wuttig@portrino.de>
 */
class Offer extends Intangible
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var QuantitativeValue The duration for which the given offer is valid
     */
    private $eligibleDuration;

    /**
     * @var QuantitativeValue The interval and unit of measurement of ordering quantities for which the offer or price specification is valid. This allows e.g. specifying that a certain freight charge is valid only for a certain quantity
     */
    private $eligibleQuantity;

    /**
     * @var string The currency (in 3-letter ISO 4217 format) of the price or a price component, when attached to \[\[PriceSpecification\]\] and its subtypes
     */
    private $priceCurrency;

    /**
     * @var string The offer price of a product, or of a price component when attached to PriceSpecification and its subtypes.\\n\\nUsage guidelines:\\n\\n\* Use the \[\[priceCurrency\]\] property (with \[ISO 4217 codes\](http://en.wikipedia.org/wiki/ISO\_4217#Active\_codes) e.g. "USD") instead of including \[ambiguous symbols\](http://en.wikipedia.org/wiki/Dollar\_sign#Currencies\_that\_use\_the\_dollar\_or\_peso\_sign) such as '$' in the value.\\n\* Use '.' (Unicode 'FULL STOP' (U+002E)) rather than ',' to indicate a decimal point. Avoid using these symbols as a readability separator.\\n\* Note that both \[RDFa\](http://www.w3.org/TR/xhtml-rdfa-primer/#using-the-content-attribute) and Microdata syntax allow the use of a "content=" attribute for publishing simple machine-readable values alongside more human-friendly formatting.\\n\* Use values from 0123456789 (Unicode 'DIGIT ZERO' (U+0030) to 'DIGIT NINE' (U+0039)) rather than superficially similiar Unicode symbols
     */
    private $price;

    /**
     * Sets id.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Gets id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets eligibleDuration.
     *
     * @param QuantitativeValue $eligibleDuration
     *
     * @return $this
     */
    public function setEligibleDuration(QuantitativeValue $eligibleDuration = null)
    {
        $this->eligibleDuration = $eligibleDuration;

        return $this;
    }

    /**
     * Gets eligibleDuration.
     *
     * @return QuantitativeValue
     */
    public function getEligibleDuration()
    {
        return $this->eligibleDuration;
    }

    /**
     * Sets eligibleQuantity.
     *
     * @param QuantitativeValue $eligibleQuantity
     *
     * @return $this
     */
    public function setEligibleQuantity(QuantitativeValue $eligibleQuantity = null)
    {
        $this->eligibleQuantity = $eligibleQuantity;

        return $this;
    }

    /**
     * Gets eligibleQuantity.
     *
     * @return QuantitativeValue
     */
    public function getEligibleQuantity()
    {
        return $this->eligibleQuantity;
    }

    /**
     * Sets priceCurrency.
     *
     * @param string $priceCurrency
     *
     * @return $this
     */
    public function setPriceCurrency($priceCurrency)
    {
        $this->priceCurrency = $priceCurrency;

        return $this;
    }

    /**
     * Gets priceCurrency.
     *
     * @return string
     */
    public function getPriceCurrency()
    {
        return $this->priceCurrency;
    }

    /**
     * Sets price.
     *
     * @param string $price
     *
     * @return $this
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Gets price.
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }
}
