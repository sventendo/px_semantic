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
 * Web page type: Collection page.
 *
 * @see http://schema.org/CollectionPage Documentation on Schema.org
 *
 * @author Andre Wuttig<wuttig@portrino.de>
 */
class CollectionPage extends WebPage
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTime Date on which the content on this web page was last reviewed for accuracy and/or completeness
     */
    private $lastReviewed;

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
     * Sets lastReviewed.
     *
     * @param \DateTime $lastReviewed
     *
     * @return $this
     */
    public function setLastReviewed(\DateTime $lastReviewed = null)
    {
        $this->lastReviewed = $lastReviewed;

        return $this;
    }

    /**
     * Gets lastReviewed.
     *
     * @return \DateTime
     */
    public function getLastReviewed()
    {
        return $this->lastReviewed;
    }
}
