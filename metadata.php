<?php

/**
 * Metadata version
 */
$sMetadataVersion = '2.0';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'pcsgproductdiscounts',
    'title'       => array(
        'de' => 'PCSG::Produktrabatte',
        'en' => 'PCSG::Product discounts',
    ),
    'description' => array(
        'de' => 'Zeigt die Produkt Rabatte direkt auf der Artikelseite an.',
        'en' => 'Displays the articles discounts directly on the articles page',
    ),
    'thumbnail'   => 'out/pictures/pcsg.png',
    'version'     => '1.0.0',
    'author'      => 'PCSG - Computer & Internet Service OHG',
    'url'         => 'https://pcsg.de/',
    'email'       => 'support@pcsg.de',
    'extend'      => array(
        \OxidEsales\Eshop\Application\Model\Article::class      => \PCSG\ProductDiscounts\Application\Model\Article::class,
        \OxidEsales\Eshop\Application\Model\DiscountList::class => \PCSG\ProductDiscounts\Application\Model\DiscountList::class,
        \OxidEsales\Eshop\Application\Model\Discount::class     => \PCSG\ProductDiscounts\Application\Model\Discount::class,
    ),
    'files'       => array(),
    'templates'   => array(
        'pcsg/productdiscounts/priceinfo.tpl' => "pcsg/productdiscounts/views/tpl/page/details/inc/priceinfo.tpl"
    ),
    'blocks'      => array(
        array(
            'template' => 'page/details/inc/productmain.tpl',
            'block'    => 'details_productmain_price_value',
            'file'     => 'views/blocks/page/details/inc/productmain/details_productmain_price_value.tpl'
        ),
    ),
    'settings'    => array(
        array('group' => 'guests', 'name' => 'discount_guest_group_id', 'type' => 'str', 'value' => ''),
    ),
    'events'      => array(),
);
