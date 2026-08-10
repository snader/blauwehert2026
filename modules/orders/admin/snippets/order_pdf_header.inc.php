<header>
    <table id="companyInfo" width="100%">
        <tr><?php

            $oSetting = SettingManager::getSettingByName('orderLogoImage');

            if (!empty($oSetting->value)) {
                $iImageId = intval($oSetting->value);
                $oImage   = ImageManager::getImageFileByImageAndReference($iImageId, 'original');
                ?>
                <td><img id="logo" src="<?= CLIENT_HTTP_URL ?>/<?= $oImage->link ?>" width="100" style="width:100px;"/></td>
            <?php } else { ?>
                <td width="100mm">&nbsp;</td>
            <?php } ?>
            <td>
                <table id="companyInfo">
                    <tr>
                        <td colspan="2"><b>Loyals online B.V.</b><br/>
                            Industrieweg 15<br/>
                            3641 RK Mijdrecht<br/>
                            Nederland
                        </td>
                    </tr>
                    <tr>
                        <td>Tel:<br/>
                            Fax:<br/>
                            E-Mail:<br/>
                            Website:<br/>
                            BTW nr:<br/>
                            KvK:<br/>
                            Postbank:<br/>
                            Rabobank:<br/>
                            IBAN:<br/>
                            BIC:<br/></td>
                        <td>0297 - 38 52 52<br/>
                            0297 - 38 52 53<br/>
                            hello@loyals.nl<br/>
                            https://loyals.nl<br/>
                            NL 8176.70.750B01<br/>
                            Utrecht 30174040<br/>
                            91.500.67<br/>
                            1113.45.030<br/>
                            NL79 RABO 0111 3450 30<br/>
                            RABONL2U
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</header>