<?php

class PaymentMethod extends Model
{

    public $paymentMethodId       = null;
    public $system_name;
    public $price                 = 0; // price with tax
    public $isOnlinePaymentMethod = 0; // set to 1 (true) if the payment needs to be handled online (like with iDEAL)
    public $order                 = 99999;

    /**
     * validate object
     */
    public function validate()
    {
        if (!is_numeric($this->price)) {
            $this->setPropInvalid('price');
        }
        if (!is_numeric($this->isOnlinePaymentMethod)) {
            $this->setPropInvalid('isOnlinePaymentMethod');
        }
        if (!is_numeric($this->order)) {
            $this->setPropInvalid('order');
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
     * get translations
     *
     * @global User $oCurrentUser
     *
     * @param       $mList , mixed
     *
     * @return PaymentMethodTranslation
     */
    public function getTranslations($mList = 'auto')
    {
        $oCurrentUser = UserManager::getCurrentUser();

        if (!isset($this->aTranslations[$mList])) {

            if (is_numeric($mList)) {
                $aTranslations = PaymentMethodTranslationManager::getPaymentMethodTranslationsByFilter(['paymentMethodId' => $this->paymentMethodId, 'languageId' => $mList]);
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
                        $aTranslations = PaymentMethodTranslationManager::getPaymentMethodTranslationsByFilter(['paymentMethodId' => $this->paymentMethodId, 'languageId' => $iLanguageId]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'auto':
                        $aTranslations = PaymentMethodTranslationManager::getPaymentMethodTranslationsByFilter(['paymentMethodId' => $this->paymentMethodId, 'languageId' => Locales::language()]);
                        if (!empty($aTranslations)) {
                            $this->aTranslations[$mList] = $aTranslations[0];
                        } else {
                            $this->aTranslations[$mList] = null;
                        }
                        break;
                    case 'default':
                        $aTranslations = PaymentMethodTranslationManager::getPaymentMethodTranslationsByFilter(['paymentMethodId' => $this->paymentMethodId]);
                        if (!empty($aTranslations)) {
                            $oTranslation = $aTranslations[0];
                        } else {
                            $oTranslation = null;
                        }
                        $this->aTranslations[$mList] = $oTranslation;
                        break;
                    case 'all':
                        $this->aTranslations = PaymentMethodTranslationManager::getPaymentMethodTranslationsByFilter(['paymentMethodId' => $this->paymentMethodId]);
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
