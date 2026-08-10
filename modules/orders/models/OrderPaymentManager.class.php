<?php

class OrderPaymentManager
{

    /**
     * get the status' label by statusId
     *
     * @param int $iStatus
     *
     * @return string
     */
    public static function getLabelByStatus($iStatus)
    {
        if (!is_numeric($iStatus)) {
            return null;
        }
        switch ($iStatus) {
            case OrderPayment::STATUS_CANCELED:
                return 'Betaling geannuleerd';
                break;
            case OrderPayment::STATUS_AWAITING_PAYMENT:
                return 'Wachten op betaling';
                break;
            case OrderPayment::STATUS_FAILED:
                return 'Betaling mislukt';
                break;
            case OrderPayment::STATUS_ACCEPTED:
                return 'Betaling afgerond';
                break;
            case OrderPayment::STATUS_OTHER:
                return 'Zie externe status';
                break;
            default:
                return $iStatus;
                break;
        }
    }

    /**
     * get a OrderPayment by id
     *
     * @param int $iPaymentId
     *
     * @return OrderPayment
     */
    public static function getPaymentById($iPaymentId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `order_payments`
                    WHERE
                        `orderPaymentId` = ' . db_int($iPaymentId) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "OrderPayment");
    }

    /**
     * get a OrderPayment by PID (virtual generated payment id)
     *
     * @param int $sPID
     *
     * @return OrderPayment
     */
    public static function getPaymentByPID($sPID)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `order_payments`
                    WHERE
                        MD5(CONCAT(`orderId`,`created`)) = ' . db_str($sPID) . '
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "OrderPayment");
    }

    /**
     * get the latest OrderPayment related to an Order by orderId
     *
     * @return OrderPayment
     */
    public static function getPaymentByOrderId($iOrderId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `order_payments`
                    WHERE
                        `orderId` = ' . db_int($iOrderId) . '
                    ORDER BY
                        `modified` DESC
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "OrderPayment");
    }

    /**
     * get the OrderPayment objects related to an Order by orderId
     *
     * @return array OrderPayment
     */
    public static function getPaymentHistoryByOrderId($iOrderId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `order_payments`
                    WHERE
                        `orderId` = ' . db_int($iOrderId) . '
                    ORDER BY
                        `modified` DESC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "OrderPayment");
    }

    /**
     * save an OrderPayment
     *
     * @param OrderPayment $oStatus
     */
    public static function savePayment(OrderPayment $oPayment)
    {
        # save the OrderPayment
        $sQuery = ' INSERT INTO `order_payments`(
                        `orderPaymentId`,
                        `price`,
                        `status`,
                        `externalStatus`,
                        `externalPaymentReference`,
                        `response`,
                        `created`,
                        `orderId`,
                        `paymentMethodId`,
                        `paymentMethodName`
                        
                    )
                    VALUES (
                        ' . db_int($oPayment->orderPaymentId) . ',
                        ' . db_deci($oPayment->price) . ',
                        ' . db_int($oPayment->status) . ',
                        ' . db_str($oPayment->externalStatus) . ',
                        ' . db_str($oPayment->externalPaymentReference) . ',
                        ' . db_str($oPayment->response) . ',
                        NOW(),
                        ' . db_int($oPayment->orderId) . ',
                        ' . db_int($oPayment->paymentMethodId) . ',
                        ' . db_str(
                $oPayment->getPaymentMethod()
                    ->getTranslations()->name
            ) . '
                    )
                    ON DUPLICATE KEY UPDATE
                        `price`=VALUES(`price`),
                        `status`=VALUES(`status`),
                        `externalStatus`=VALUES(`externalStatus`),
                        `externalPaymentReference`=VALUES(`externalPaymentReference`),
                        `response`=VALUES(`response`),
                        `orderId`=VALUES(`orderId`),
                        `paymentMethodId`=VALUES(`paymentMethodId`),
                        `paymentMethodName`=VALUES(`paymentMethodName`)
                    ;';

        $oDb = DBConnections::get();
        $oDb->query($sQuery, QRY_NORESULT);

        if ($oPayment->orderPaymentId === null) {
            $oPayment->orderPaymentId = $oDb->insert_id;
        }
    }

}

?>