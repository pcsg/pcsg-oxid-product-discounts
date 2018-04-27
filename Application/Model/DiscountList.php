<?php

namespace PCSG\ProductDiscounts\Application\Model;

use OxidEsales\Eshop\Application\Model\User;

/**
 * Class DiscountList
 *
 * @package PCSG\ProductDiscounts\Application\Model
 */
class DiscountList extends DiscountList_parent
{

    /**
     * Returns all discounts tied to the given article (or its articlegroup, that are available to the given user
     *
     * @param \OxidEsales\Eshop\Application\Model\Article $oArticle
     * @param User $oUser
     *
     * @return array
     */
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

    /**
     * Creates discount list filter SQL to load current state discount list
     *
     * @param \OxidEsales\Eshop\Application\Model\User $oUser user object
     *
     * @return string
     */
    protected function _getFilterSelect($oUser)
    {
        $oBaseObject = $this->getBaseObject();

        $sTable = $oBaseObject->getViewName();
        $sQ     = "select ".$oBaseObject->getSelectFields()." from $sTable ";
        $sQ     .= "where ".$oBaseObject->getSqlActiveSnippet().' ';

        // defining initial filter parameters
        $sUserId    = null;
        $sGroupIds  = null;
        $sCountryId = $this->getCountryId($oUser);
        $oDb        = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();

        // checking for current session user which gives additional restrictions for user itself, users group and country
        if ($oUser) {
            // user ID
            $sUserId = $oUser->getId();

            // user group ids
            foreach ($oUser->getUserGroups() as $oGroup) {
                if ($sGroupIds) {
                    $sGroupIds .= ', ';
                }
                $sGroupIds .= $oDb->quote($oGroup->getId());
            }
        } else {
            $guestGroupID = \oxregistry::get("oxConfig")->getConfigParam("discount_guest_group_id");
            if (!empty($guestGroupID)) {
                $sGroupIds = $oDb->quote($guestGroupID);
            }
        }

        $sUserTable    = getViewName('oxuser');
        $sGroupTable   = getViewName('oxgroups');
        $sCountryTable = getViewName('oxcountry');

        $sCountrySql = $sCountryId ? "EXISTS(select oxobject2discount.oxid from oxobject2discount where oxobject2discount.OXDISCOUNTID=$sTable.OXID and oxobject2discount.oxtype='oxcountry' and oxobject2discount.OXOBJECTID=".$oDb->quote($sCountryId).")" : '0';
        $sUserSql    = $sUserId ? "EXISTS(select oxobject2discount.oxid from oxobject2discount where oxobject2discount.OXDISCOUNTID=$sTable.OXID and oxobject2discount.oxtype='oxuser' and oxobject2discount.OXOBJECTID=".$oDb->quote($sUserId).")" : '0';
        $sGroupSql   = $sGroupIds ? "EXISTS(select oxobject2discount.oxid from oxobject2discount where oxobject2discount.OXDISCOUNTID=$sTable.OXID and oxobject2discount.oxtype='oxgroups' and oxobject2discount.OXOBJECTID in ($sGroupIds) )" : '0';

        $sQ .= "and (
            select
                if(EXISTS(select 1 from oxobject2discount, $sCountryTable where $sCountryTable.oxid=oxobject2discount.oxobjectid and oxobject2discount.OXDISCOUNTID=$sTable.OXID and oxobject2discount.oxtype='oxcountry' LIMIT 1),
                        $sCountrySql,
                        1) &&
                if(EXISTS(select 1 from oxobject2discount, $sUserTable where $sUserTable.oxid=oxobject2discount.oxobjectid and oxobject2discount.OXDISCOUNTID=$sTable.OXID and oxobject2discount.oxtype='oxuser' LIMIT 1),
                        $sUserSql,
                        1) &&
                if(EXISTS(select 1 from oxobject2discount, $sGroupTable where $sGroupTable.oxid=oxobject2discount.oxobjectid and oxobject2discount.OXDISCOUNTID=$sTable.OXID and oxobject2discount.oxtype='oxgroups' LIMIT 1),
                        $sGroupSql,
                        1)
            )";

        $sQ .= " order by $sTable.oxsort ";

        return $sQ;
    }

}