<?php

namespace PCSG\ProductDiscounts\Application\Model;

/**
 * Class DiscountList
 *
 * @package PCSG\ProductDiscounts\Application\Model
 */
class DiscountList extends DiscountList_parent
{

    public function getAllArticleDiscounts($oArticle,$oUser)
    {
        $aList = [];
        $aDiscList = $this->_getList($oUser)->getArray();
        foreach ($aDiscList as $oDiscount) {
            

            if ($this->isGlobalDiscount()) {
                $aList[$oDiscount->getId()] = $oDiscount;
                continue;
            }

            $sArticleId = $oArticle->getProductId();
            if (!isset($this->_aHasArticleDiscounts[$sArticleId])) {
                $blResult = $this->_isArticleAssigned($oArticle) || $this->_isCategoriesAssigned($oArticle->getCategoryIds());

                $aList[$oDiscount->getId()] = $oDiscount;
                continue;
            }
        }

        return $aList;
    }

}