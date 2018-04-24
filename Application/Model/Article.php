<?php

namespace PCSG\ProductDiscounts\Application\Model;

use \OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Application\Model\DiscountList;

class Article extends Article_parent
{
    
    /**
     * Returns the active discounts for the product.
     *
     * @return array
     */
    public function getProductDiscounts()
    {
        $oDiscountList = Registry::get(\PCSG\ProductDiscounts\Application\Model\DiscountList::class);
        $aDiscounts    = $oDiscountList->getAllArticleDiscounts($this, $this->getArticleUser());

        return $aDiscounts;
    }
}