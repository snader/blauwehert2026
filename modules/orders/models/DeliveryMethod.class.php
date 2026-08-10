<?php

class DeliveryMethod extends Model
{

    public  $deliveryMethodId = null;
    public  $system_name;
    public  $price; // price without tax
    public  $freeFromPrice; // price with tax from where there are no extra costs (default null -> always extra price)
    public  $deliveryTime; // Delivery time in days or period
    public  $order            = 99999;
    private $aPaymentMethods  = null; //array with different lists of paymentMethods

    /**
     * validate object
     */

    public function validate()
    {
        if (!is_numeric($this->price)) {
            $this->setPropInvalid('price');
        }
        if (!empty($this->freeFromPrice) && !is_numeric($this->freeFromPrice)) {
            $this->setPropInvalid('freeFromPrice');
        }
        if (empty($this->system_name)) {
            $this->setPropInvalid('system_name');
        }
    }

    /**
     * check if is deletable
     *
     * @return boolean
     */
    public function isDeletable()
    {
        return true;
    }

    /**
     * check if is editable
     *
     * @return boolean
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * get all payment methods for this delivery method
     *
     * @param string $sList
     *
     * @return array PaymentMethod
     */
    public function getPaymentMethods($sList = 'all')
    {
        if (!isset($this->aPaymentMethods[$sList])) {
            switch ($sList) {
                case 'all':
                    $this->aPaymentMethods[$sList] = PaymentMethodManager::getPaymentMethodsByFilter(['deliveryMethodId' => $this->deliveryMethodId]);
                    break;
                default:
                    die('no option');
                    break;
            }
        }

        return $this->aPaymentMethods[$sList];
    }

    /**
     * set paymentMethods
     *
     * @param array  $aPaymentMethods
     * @param string $sList (set in specific list)
     */
    public function setPaymentMethods(array $aPaymentMethods, $sList = 'all')
    {
        $this->aPaymentMethods[$sList] = $aPaymentMethods;
    }

    /**
     * get translations
     *
     * @global User $oCurrentUser
     *
     * @param       $mList , mixed
     *
     * @return DeliveryMethodTranslation
     */
    public function getTranslations($mList = 'auto')
    {
        $oCurrentUser = UserManager::getCurrentUser();

        if (!isset($this->aTranslations[$mList])) {

            if (is_numeric($mList)) {
                $aTranslations = DeliveryMethodTranslationManager::getDeliveryMethodTranslationsByFilter(['deliveryMethodId' => $this->deliveryMethodId, 'languageId' => $mList]);
                if (!empty($aTranslations)) {
                    $this->aTranslations[$mList] = $aTranslations[0];
                } else {
                    $this->aTranslations[$mList] = null;
                }
            } else {

                switch ($mList) {
                    case 'auto-admin':
                        $iLanguageId   = Locales::getAdminLocale()
                            ->getLanguage()->languageId;
                        $aTranslations = DeliveryMethodTranslationManager::getDeliveryMethodTranslationsByFilter(['deliveryMethodId' => $this->deliveryMethodId, 'languageId' => $iLanguageId]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'auto':
                        $aTranslations = DeliveryMethodTranslationManager::getDeliveryMethodTranslationsByFilter(['deliveryMethodId' => $this->deliveryMethodId, 'languageId' => Locales::language()]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'default':
                        $aTranslations = DeliveryMethodTranslationManager::getDeliveryMethodTranslationsByFilter(['deliveryMethodId' => $this->deliveryMethodId]);
                        if (!empty($aTranslations)) {
                            $oTranslation = $aTranslations[0];
                        } else {
                            $oTranslation = null;
                        }
                        $this->aTranslations[$mList] = $oTranslation;
                        break;
                    case 'all':
                        $this->aTranslations = DeliveryMethodTranslationManager::getDeliveryMethodTranslationsByFilter(['deliveryMethodId' => $this->deliveryMethodId]);
                        break;
                    default:
                        die('no option');
                        break;
                }
            }
        }

        return $this->aTranslations[$mList];
    }

}
