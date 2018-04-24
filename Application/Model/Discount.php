<?php

namespace PCSG\ProductDiscounts\Application\Model;

/**
 * Class DiscountList
 *
 * @package PCSG\ProductDiscounts\Application\Model
 */
class Discount extends Discount_parent
{

    /**
     * Checks wethe the article is available for this product
     * @param $oArticle
     *
     * @return bool
     */
    public function isArticleAvailable($oArticle)
    {

        if ($this->isGlobalDiscount()) {
            return true;
        }

        
        if ($this->_isArticleAssigned($oArticle) || $this->_isCategoriesAssigned($oArticle->getCategoryIds())) {
            return true;
        }

        return false;
    }

}