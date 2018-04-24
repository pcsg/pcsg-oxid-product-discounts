<?php

namespace PCSG\ProductDiscounts\Application\Model;

/**
 * Class DiscountList
 *
 * @package PCSG\ProductDiscounts\Application\Model
 */
class DiscountList extends DiscountList_parent
{

    public function getAllArticleDiscounts($oArticle, $oUser)
    {
        $aList     = [];
        $aDiscList = $this->_getList($oUser)->getArray();
        foreach ($aDiscList as $oDiscount) {
            if ($oDiscount->isArticleAvailable($oArticle)) {
                $aList[$oDiscount->getId()] = $oDiscount;
            }
        }

        return $aList;
    }

}