<?php

class Customer extends Model
{

    public  $customerId                       = null;
    public  $companyName                      = null;
    public  $companyAddress                   = null;
    public  $companyPostalCode                = null;
    public  $companyCity                      = null;
    public  $companyEmail                     = null;
    public  $companyPhone                     = null;
    public  $companyWebsite                   = null;
    public  $gender;
    public  $firstName;
    public  $insertion;
    public  $lastName;
    public  $address;
    public  $houseNumber;
    public  $houseNumberAddition;
    public  $postalCode;
    public  $city;
    public  $mobilePhone                      = null;
    public  $phone                            = null;
    public  $fax                              = null;
    public  $email;
    public  $password;
    public  $contactBySms                     = 1;
    public  $contactByEmail                   = 1;
    public  $didSignOffForEmail               = 0;
    public  $confirmCode                      = null;
    public  $bounceRuleId; // only used when connected to bounce sync
    public  $bounceRuleTitle; // only used when connected to bounce sync
    public  $bounceRuleDescription; // only used when connected to bounce sync
    public  $online                           = 0;
    public  $locked; // locked timestamp
    public  $lockedReason; // reason for getting locked
    public  $receiveCommunicationVerification = 0;
    public  $lastLogin                        = null;
    public  $created                          = null;
    public  $modified                         = null;
    private $aOrders                          = null;
    private $aCustomerGroups                  = null;
    public  $countryId;

    /**
     * @var \Customer
     */
    protected static $customer;

    /**
     * @var \Country
     */
    protected static $country;

    /**
     * @return \Customer
     */
    public static function getCurrent()
    {
        if (!static::$customer) {
            static::$customer = Session::get(CustomerManager::SESSION) ?: null;
        }

        return static::$customer;
    }

    /**
     * validate object
     */
    public function validate()
    {
        if (empty($this->gender)) {
            $this->setPropInvalid('gender');
        }
        if (empty($this->firstName)) {
            $this->setPropInvalid('firstName');
        }
        if (empty($this->lastName)) {
            $this->setPropInvalid('lastName');
        }
        if (empty($this->address)) {
            $this->setPropInvalid('address');
        }
        if (!is_numeric($this->houseNumber)) {
            $this->setPropInvalid('houseNumber');
        }
        if (empty($this->postalCode)) {
            $this->setPropInvalid('postalCode');
        }
        if (empty($this->city)) {
            $this->setPropInvalid('city');
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->setPropInvalid('email');
        }
        if (CustomerManager::emailExists($this->email, $this->customerId)) {
            $this->setPropInvalid('emailExists');
        }
        if (empty($this->password) || (!empty($this->password) && strlen($this->password) < 8)) {
            $this->setPropInvalid('password');
        }
        if (!is_numeric($this->contactBySms)) {
            $this->setPropInvalid('contactBySms');
        }
        if (!is_numeric($this->contactByEmail)) {
            $this->setPropInvalid('contactByEmail');
        }
        if (!is_numeric($this->online)) {
            $this->setPropInvalid('online');
        }
    }

    /**
     * mask pasword for session use
     */
    function maskPass()
    {
        $this->password = 'XXX';
    }

    /**
     * get full name of client
     *
     * @return type string
     */
    function getFullName()
    {
        return _e($this->firstName . (!empty($this->insertion) ? ' ' . $this->insertion . ' ' : ' ') . $this->lastName);
    }

    /**
     * get all Order objects related to this Customer
     *
     * @return array Order $this->aOrders
     */
    public function getOrders()
    {
        if ($this->aOrders === null) {
            $this->aOrders = OrderManager::getOrdersByCustomerId($this->customerId);
        }

        return $this->aOrders;
    }

    /**
     * get all CustomGroup objects related to this Customer
     *
     * @return array CustomGroup $this->aCustomerGroups
     */
    public function getCustomerGroups()
    {
        if ($this->aCustomerGroups === null) {
            $this->aCustomerGroups = CustomerGroupManager::getCustomerGroupsByCustomerId($this->customerId);
        }

        return $this->aCustomerGroups;
    }

    /**
     * set customer CustomGroups
     *
     * @param array of CustomGroup objects
     */
    public function setCustomerGroups(array $aCustomerGroups)
    {
        $this->aCustomerGroups = $aCustomerGroups;
    }

    /**
     * check whether customer is linked to the CustomerGroup
     *
     * @param int $iCustomerGroupId
     *
     * @return boolean
     */
    public function isLinkedToCustomerGroup($iCustomerGroupId)
    {
        # return by default false
        $bResult = false;

        # when not yet set, get linked CustomerGroups
        if (empty($this->aCustomerGroups)) {
            $this->getCustomerGroups();
        }

        # loop through linked CustomerGroups
        foreach ($this->aCustomerGroups as $oCustomerGroup) {
            # check whether user is linked to customGroup
            if ($oCustomerGroup->customerGroupId == $iCustomerGroupId) {
                $bResult = true;
            }
        }

        # return result
        return $bResult;
    }

    /**
     * set default required data for csv import
     */
    public function setDefaultCsvImportValues()
    {
        # set required data for database
        $this->gender                           = 'U';
        $this->address                          = 'Unknown';
        $this->houseNumber                      = 0;
        $this->houseNumberAddition              = null;
        $this->postalCode                       = '0000AA';
        $this->city                             = 'Unknown';
        $this->contactBySms                     = 1;
        $this->contactByEmail                   = 1;
        $this->didSignOffForEmail               = 0;
        $this->receiveCommunicationVerification = 0;
        $this->online                           = 1;
        $this->password                         = hashPasswordForDb(md5($this->email));
    }

    /**
     * @return \Country
     */
    public function getCountry()
    {
        if (!static::$country) {
            static::$country = CountryManager::getCountryById($this->countryId);
        }

        return static::$country;
    }
}

?>