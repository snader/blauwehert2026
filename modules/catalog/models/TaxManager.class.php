<?php

class TaxManager
{

    # list of percentage ids
    const taxPercentageId_high = 1;
    const taxPercentageId_low  = 2;
    const taxPercentageId_free = 3;

    /**
     * get all percentages
     *
     * @return array
     */
    public static function getAllPercentageIds()
    {
        return [
            self::taxPercentageId_high,
            self::taxPercentageId_low,
            self::taxPercentageId_free,
        ];
    }

    /**
     * get the percentage by id number
     *
     * @param int  $iTaxPercentageId    the id which is set in this class in the TAX_PERCENTAGE constants
     * @param bool $bWithPercentageSign wether or not to return as string with %-sign
     *
     * @return mixed percentage
     */
    public static function getPercentageById($iTaxPercentageId, $bWithPercentageSign = false)
    {
        if (!is_numeric($iTaxPercentageId)) {
            return false;
        }

        switch ($iTaxPercentageId) {
            case self::taxPercentageId_high:
                $iPercentage = 21;
                break;
            case self::taxPercentageId_low:
                $iPercentage = 9;
                break;
            case self::taxPercentageId_free:
                $iPercentage = 0;
                break;
            default:
                return false;
                break;
        }

        if ($bWithPercentageSign) {
            return (string)$iPercentage . '%';
        } else {
            return $iPercentage;
        }
    }

    /**
     * add tax to a price and return the new price
     *
     * @param float $fPrice     price without tax
     * @param int   $iTaxPercentage
     * @param type  &$fTaxPrice reference of the price of the tax
     *
     * @return float
     */
    public static function addTax($fPriceWithoutTax, $iTaxPercentage, &$fTaxPrice = null)
    {
        $fTaxPrice = $fPriceWithoutTax * ($iTaxPercentage / 100);

        return $fPriceWithoutTax + $fTaxPrice;
    }

    /**
     * subtract tax (VAT) from a price and return the new price
     *
     * @param float $fPrice     price with tax
     * @param int   $iTaxPercentage
     * @param type  &$fTaxPrice reference of the price of the tax
     *
     * @return float
     */
    public static function subtractTax($fPriceWithTax, $iTaxPercentage, &$fTaxPrice = null)
    {
        $fPriceWithoutTax = ($fPriceWithTax / ($iTaxPercentage + 100)) * 100;
        $fTaxPrice        = $fPriceWithTax - $fPriceWithoutTax;

        return $fPriceWithoutTax;
    }

}

?>