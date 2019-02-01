<?php
require("helpers.php");

define('group_id_names', array(
    0 => 'NOT LOGGED IN',
    4 => 'MSRP Standard',
    5 => 'MSRP Advanced',
    6 => 'Tier 1',
    7 => 'Tier 2',
    8 => 'Tier 3',
    9 => 'Tier 4',
    10 => 'Tier 5'
));

define('group_discount', array(
    0 => 0,
    4 => 0,
    5 => 0,
    6 => 60.00,
    7 => 58.00,
    8 => 55.80,
    9 => 55.80,
    10 => 55.80,
));

/*
 * This should be an M1 product export file. We parse the file and create an array of the data in order to extract it more easily.
 */
$filename = 'latest_product_export_modified.csv';
$file = fopen($filename, 'r');
$data = csvFileToArray($file);

/*
 * We create or update a csv file and add the header columns needed to correctly import advanced pricing in Magento 2.
 */
$new_csv_file = 'advanced_pricing_import.csv';
$advanced_pricing_csv_file = fopen($new_csv_file, 'w'); // Create/update new file for import.
addHeaders($advanced_pricing_csv_file); // We add the Magento 2 header values to the first row of the csv file needed to correctly import advanced product pricing.

/*
 * We have to iterate through the M1 product csv file and extract the necessary data to populate the new csv file used to import advanced pricing.
 */
foreach ($data as $product)
{
    if(!empty($product['cws_tier_price'])) // Check if product contains Tier Pricing.
    {
        processTierPricing($product, $advanced_pricing_csv_file);
    }
    if(!empty($product['cws_group_price'])) // Check if product contains Group Pricing.
    {
        processDiscountPricing($product, $advanced_pricing_csv_file);
    }
}

echo "<h1>Complete!</h1>";




















