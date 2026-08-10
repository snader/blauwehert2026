<div id="customerInfo">
    <b><?= _e(CLIENT_NAME) ?></b><br>
</div>
<table width="180mm" cellspacing="0" cellpadding="0">
    <tr>
        <td align="right" width="180mm"><h2>Order</h2></td>
    </tr>
    <tr>
        <td width="180mm">
            <table width="180mm" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan="2" width="180mm" class="bottomBorder">&nbsp;</td>
                </tr>
                <tr>
                    <td width="50mm">&nbsp;<b>Orderdatum</b></td>
                    <td width="130mm"><b>Ordernummer</b></td>
                </tr>
                <tr>
                    <td colspan="2" width="180mm" class="topBorder">&nbsp;</td>
                </tr>
            </table>
            <table class="pullUp">
                <tr>
                    <td width="50mm">&nbsp;<?= (new Date($this->created))->format('%d %B %Y'); ?></td>
                    <td width="130mm"><?= $this->orderId; ?></td>
                </tr>
                <tr>
                    <td colspan="2" width="180mm" class="topBorder">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table id="orderDetail" width="180mm" cellspacing="0" cellpadding="0" border="0">
    <thead>
    <tr>
        <td width="15mm">&nbsp;<b>Artikel</b></td>
        <td><b>Omschrijving</b></td>
        <td width="8mm" align="right"><b>Aantal</b></td>
        <td width="20mm" align="right"><b>Prijs</b></td>
        <td width="10mm" align="right"><b>BTW %</b></td>
        <td width="22mm" align="right"><b>Totaalbedrag</b></td>
    </tr>
    <tr>
        <td colspan="6" width="180mm" class="topBorder">&nbsp;</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->getProducts() as $oProduct) { ?>
        <tr>
            <td>&nbsp;<?= $oProduct->getProduct()->catalogProductId ?></td>
            <td><?= wordwrap($oProduct->productName, 50, '<br />\n'); ?></td>
            <td align="right"><?= $oProduct->amount; ?></td>
            <td align="right"><?= decimal2valuta($oProduct->getSalePrice(false)); ?></td>
            <td align="right"><?= $oProduct->getTaxPercentage(true) ?></td>
            <td align="right"><?= decimal2valuta($oProduct->getSubTotalPrice(true)); ?></td>
        </tr>
    <?php } ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="6" class="bottomBorder" width="180mm">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
        <td colspan="2" align="right">
            <table cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td width="50mm">Subtotaal</td>
                </tr>
                <tr>
                    <td>Korting</td>
                </tr>
                <tr>
                    <td>Aflever</td>
                </tr>
                <tr>
                    <td>Betaal</td>
                </tr>
                <tr>
                    <td><b>Totaal</b></td>
                </tr>
                <tr>
                    <td>BTW</td>
                </tr>
                <tr>
                    <td>Totaal excl. BTW</td>
                </tr>
            </table>
        </td>
        <td>
            <table cellspacing="0" cellpadding="0" border="0" align="right">
                <tr>
                    <td style="text-align: right"><?= decimal2valuta($this->getSubtotalProducts(true)); ?></td>
                </tr>
                <tr>
                    <td style="text-align: right"><?= decimal2valuta($this->getDiscount(true)); ?></td>
                </tr>
                <tr>
                    <td style="text-align: right"><?= decimal2valuta($this->getPaymentPrice()); ?></td>
                </tr>
                <tr>
                    <td style="text-align: right"><?= decimal2valuta($this->getDeliveryPrice()); ?></td>
                </tr>
                <tr>
                    <td style="text-align: right"><b><?= decimal2valuta($this->getTotal(true)); ?></b></td>
                </tr>
                <tr>
                    <td style="text-align: right"><?= decimal2valuta($this->getBTW()); ?></td>
                </tr>
                <tr>
                    <td style="text-align: right"><?= decimal2valuta($this->getTotal(false)); ?></td>
                </tr>
            </table>
        </td>
    </tr>
    </tfoot>
</table>