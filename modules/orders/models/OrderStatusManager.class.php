<?php

class OrderStatusManager
{

    /**
     * get all statuses
     *
     * @return array
     */
    public static function getAllStatuses()
    {
        return [
            OrderStatus::STATUS_NEW,
            OrderStatus::STATUS_AWAITING_PAYMENT,
            OrderStatus::STATUS_PAYED,
            OrderStatus::STATUS_READY_FOR_PROCESSING,
            OrderStatus::STATUS_PROCESSING,
            OrderStatus::STATUS_PROCESSED,
            OrderStatus::STATUS_DISPATCHED,
            OrderStatus::STATUS_CANCELED,
        ];
    }

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
            case OrderStatus::STATUS_NEW:
                return 'Aangemaakt';
                break;
            case OrderStatus::STATUS_AWAITING_PAYMENT:
                return 'Wachten op betaling';
                break;
            case OrderStatus::STATUS_PAYED:
                return 'Betaald';
                break;
            case OrderStatus::STATUS_READY_FOR_PROCESSING:
                return 'Klaar voor behandeling';
                break;
            case OrderStatus::STATUS_PROCESSING:
                return 'In behandeling';
                break;
            case OrderStatus::STATUS_PROCESSED:
                return 'Klaar voor levering';
                break;
            case OrderStatus::STATUS_DISPATCHED:
                return 'Verzonden/geleverd';
                break;
            case OrderStatus::STATUS_CANCELED:
                return 'Geannuleerd';
                break;
            default:
                return $iStatus;
                break;
        }
    }

    /**
     * convert an OrderPayment's status to an Order's status
     *
     * @param int $iStatus
     *
     * @return int
     */
    public static function getStatusByPaymentStatus($iStatus)
    {
        if (!is_numeric($iStatus)) {
            return null;
        }
        switch ($iStatus) {
            case OrderPayment::STATUS_ACCEPTED:
                return OrderStatus::STATUS_PAYED;
                break;
            default:
                return OrderStatus::STATUS_AWAITING_PAYMENT;
                break;
        }
    }

    /**
     * get the latest OrderStatus related to an Order by orderId
     *
     * @param int $iOrderId
     *
     * @return array OrderStatus
     */
    public static function getStatusByOrderId($iOrderId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `order_statuses`
                    WHERE
                        `orderId` = ' . db_int($iOrderId) . '
                    ORDER BY
                        `timestamp` DESC, `orderStatusId` DESC
                    LIMIT 1
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_UNIQUE_OBJECT, "OrderStatus");
    }

    /**
     * get OrderStatus objects related to an Order by orderId
     *
     * @param int $iOrderId
     *
     * @return array OrderStatus
     */
    public static function getStatusHistoryByOrderId($iOrderId)
    {
        $sQuery = ' SELECT
                        *
                    FROM
                        `order_statuses`
                    WHERE
                        `orderId` = ' . db_int($iOrderId) . '
                    ORDER BY
                        `timestamp` DESC, `orderStatusId` DESC
                    ;';

        $oDb = DBConnections::get();

        return $oDb->query($sQuery, QRY_OBJECT, "OrderStatus");
    }

    /**
     * save an OrderStatus
     *
     * @param OrderStatus $oStatus
     */
    public static function saveStatus(OrderStatus $oStatus)
    {
        $oDb = DBConnections::get();

        # save the OrderStatus and update the related Order
        $sQuery = ' INSERT INTO `order_statuses`(
                        `orderStatusId`,
                        `status`,
                        `timestamp`,
                        `orderId`,
                        `createdBy`
                    )
                    VALUES (
                        NULL,
                        ' . db_int($oStatus->status) . ',
                        NOW(),
                        ' . db_int($oStatus->orderId) . ',
                        ' . db_str($oStatus->createdBy) . '
                    )
                    ;';

        $oDb->query($sQuery, QRY_NORESULT);

        $sQuery = ' UPDATE `orders`
                    SET
                        `status` = ' . db_int($oStatus->status) . '
                    WHERE
                        `orderId` = ' . db_int($oStatus->orderId) . '
                    LIMIT 1
                    ;';

        $oDb->query($sQuery, QRY_NORESULT);
    }

}

?>