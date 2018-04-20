[{if $oDetailsProduct->getFPrice()}]
    <label id="productPrice" class="price">
        [{assign var="sFrom" value=""}]
        [{assign var="fPrice" value=$oDetailsProduct->getFPrice()}]
        [{if $oDetailsProduct->isParentNotBuyable()}]
            [{assign var="fPrice" value=$oDetailsProduct->getFVarMinPrice()}]
            [{if $oDetailsProduct->isRangePrice()}]
                [{assign var="sFrom" value="PRICE_FROM"|oxmultilangassign}]
            [{/if}]
        [{/if}]
        <span[{if $tprice && $tprice->getBruttoPrice() > $price->getBruttoPrice()}] class="text-danger"[{/if}]>
                                            <span class="price-from">[{$sFrom}]</span>
                                            <span class="price">[{$fPrice}]</span>
                                            <span class="currency">[{$currency->sign}]</span>
            [{if $oView->isVatIncluded()}]
                <span class="price-markup">*</span>
            [{/if}]
            <span class="hidden">
                                                <span itemprop="price">[{$fPrice}] [{$currency->sign}]</span>
                                            </span>
                                        </span>
    </label>
[{/if}]
[{if $oDetailsProduct->loadAmountPriceInfo() || !empty($oDetailsProduct->getProductDiscounts())}]
    [{include file="pcsg/productdiscounts/priceinfo.tpl"}]
[{/if}]