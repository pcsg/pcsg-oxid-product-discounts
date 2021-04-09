<?php

namespace PCSG\ProductDiscounts\Application\Model;

use OxidEsales\Eshop\Core\Registry;

/**
 * User manager.
 * Performs user managing function, as assigning to groups, updating
 * information, deletion and other.
 *
 */
class User extends User_parent
{
    /**
     * Called after saving an order.
     *
     * @param object $oBasket Shopping basket object
     * @param int $iSuccess order success status
     */
    public function onOrderExecute($oBasket, $iSuccess)
    {
        if (is_numeric($iSuccess) && $iSuccess != 2 && $iSuccess <= 3) {
            //adding user to particular customer groups
            $myConfig         = $this->getConfig();
            $dMidlleCustPrice = (float)$myConfig->getConfigParam('sMidlleCustPrice');
            $dLargeCustPrice  = (float)$myConfig->getConfigParam('sLargeCustPrice');

            /** PCSG  Only Adds Oxidcustomer group on order if user is not in non_customer_groups  pcsg-projects/farrado#76 **/
            // Users in these groups are not customers
            $nonCustomerGroups = explode(
                ',',
                Registry::getConfig()->getConfigParam('non_costumer_groups')
            );
            $newCustomerGroupHash = Registry::getConfig()->getConfigParam('custom_new_customer_group');
            $addtoCustomers       = true;

            foreach ($nonCustomerGroups as $group) {
                if ($this->inGroup($group)) {
                    $addtoCustomers = false;
                }
            }

            $usergroups = $this->__get('oGroups')->getArray();

            //pcsg-projects/farrado#62
            foreach($usergroups as $Group){
                if($Group->_sOXID === 'oxidnotyetordered'){
                    $this->addToGroup($newCustomerGroupHash);
                }
            }
            //end pcsg-projects/farrado#62

            if ($addtoCustomers) {
                $this->addToGroup('oxidcustomer');
            }
            /** END PCSG **/

            $dBasketPrice = $oBasket->getPrice()->getBruttoPrice();
            if ($dBasketPrice < $dMidlleCustPrice) {
                $this->addToGroup('oxidsmallcust');
            }
            if ($dBasketPrice >= $dMidlleCustPrice && $dBasketPrice < $dLargeCustPrice) {
                $this->addToGroup('oxidmiddlecust');
            }
            if ($dBasketPrice >= $dLargeCustPrice) {
                $this->addToGroup('oxidgoodcust');
            }

            if ($this->inGroup('oxidnotyetordered')) {
                $this->removeFromGroup('oxidnotyetordered');
            }
        }
    }
}
