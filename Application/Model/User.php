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
     * @param object $oBasket  Shopping basket object
     * @param int    $iSuccess order success status
     */
    public function onOrderExecute($oBasket, $iSuccess)
    {
        if (is_numeric($iSuccess) && $iSuccess != 2 && $iSuccess <= 3) {
            //adding user to particular customer groups
            $Config           = $this->getConfig();
            $dMidlleCustPrice = (float)$Config->getConfigParam('sMidlleCustPrice');
            $dLargeCustPrice  = (float)$Config->getConfigParam('sLargeCustPrice');

            /** PCSG  Only Adds Oxidcustomer group on order if user is not in non_customer_groups  pcsg-projects/farrado#76 **/
            // Users in these groups are not customers
            $nonCustomerGroups = explode(
                ',',
                Registry::getConfig()->getConfigParam('non_costumer_groups')
            );

            // Comma-separated list of group IDs of groups that new customers should be added to
            $newCustomerGroups = explode(
                ',',
                Registry::getConfig()->getConfigParam('custom_new_customer_groups')
            );

            // Should the user be added to customers group?
            $addUserToCustomerGroup = true;

            foreach ($nonCustomerGroups as $group) {
                // Check if the user is in a non-customer group
                if ($this->inGroup($group)) {
                    // If he is, he shouldn't be added to customers group later
                    $addUserToCustomerGroup = false;
                }
            }

            //pcsg-projects/farrado#62
            // If user didn't order yet, add him to the group defined above
            if ($this->inGroup('oxidnotyetordered')) {
                foreach ($newCustomerGroups as $group) {
                    $this->addToGroup($group);
                }
            }
            //end pcsg-projects/farrado#62

            // Should the user be added to customers group?
            if ($addUserToCustomerGroup) {
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
