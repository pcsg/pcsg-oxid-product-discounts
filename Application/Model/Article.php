<?php

namespace PCSG\ProductDiscounts\Application\Model;

class Article extends Article_parent
{

    /**
     * Returns the active discounts for the product.
     *
     * @return array
     */
    public function getProductDiscounts()
    {
        $oDiscountList = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Application\Model\DiscountList::class);
        $aDiscounts    = $oDiscountList->getArticleDiscounts($this, $this->getArticleUser());

        return $aDiscounts;
    }
}