<?php

class AjaxAdminController extends AdminController
{
    /**
     * @inheritdoc
     */
    protected static $allowed_actions = [
        'dataTable',
        'get',
        'add',
        'remove',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!UserManager::getCurrentUser() && !Request::isPost()) {
            throw new Exception('Not authorized');
        }

        Request::setParameterNames(
            [
                'MasterModel',
                'MasterId',
                'SlaveModel',
                'SlaveId',
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function index()
    {
        return Router::httpError(403);
    }

    /**
     * Get action
     *
     * Retrieves the requested model's data by the given filter
     */
    public function get()
    {
        $sSlaveModelName = Request::param('SlaveModel');
        if (empty($sSlaveModelName)) {
            $this->getRenderEngine()
                ->clearVariables()
                ->setVariables([
                    'rows' => [],
                ]);
            $this->jsend();
            return;
        }

        $sSlaveModel  = sprintf('%1$sManager', $sSlaveModelName);
        $sSlaveMethod = sprintf('get%1$sByFilter', $this->getPlural($sSlaveModelName));
        $aFilter      = json_decode((string) Request::postVar('filter') ?: '{}', true);
        if (!is_array($aFilter)) {
            $aFilter = [];
        }
        $aFilter['q'] = Request::postVar('term');
        $aObjects     = $sSlaveModel::$sSlaveMethod($aFilter);

        $this->getRenderEngine()
            ->clearVariables()
            ->setVariables(
                [
                    'rows' => $this->mapObjects($aObjects),
                ]
            );

        $this->jsend();
    }

    /**
     * Add action
     *
     * Adds a relation between the master and the slave models
     */
    public function add()
    {
        if (CSRFSynchronizerToken::validate()) {
            $sMasterModel    = sprintf('%1$sManager', Request::param('MasterModel'));
            $sRelationMethod = sprintf('save%1$s%2$sRelation', Request::param('MasterModel'), Request::param('SlaveModel'));

            $sMasterModel::$sRelationMethod(Request::param('MasterId'), Request::param('SlaveId'));
        }

        $this->dataTable();
    }

    /**
     * Remove action
     *
     * Removes a relation between the master and the slave models
     */
    public function remove()
    {
        if (CSRFSynchronizerToken::validate()) {
            $sMasterModel    = sprintf('%1$sManager', Request::param('MasterModel'));
            $sRelationMethod = sprintf('delete%1$s%2$sRelation', Request::param('MasterModel'), Request::param('SlaveModel'));

            $sMasterModel::$sRelationMethod(Request::param('MasterId'), Request::param('SlaveId'));
        }

        $this->dataTable();
    }

    /**
     * DataTable action
     *
     * Retrieve the data table for the given relation
     */
    public function dataTable()
    {
        $sMasterModelName = Request::param('MasterModel');
        $sMasterModel     = sprintf('%1$sManager', $sMasterModelName);
        $sGetMethod       = sprintf('get%1$sById', $sMasterModelName);
        $oObject          = $sMasterModel::$sGetMethod(Request::param('MasterId'));

        $sSlaveModelName = Request::param('SlaveModel');
        $sSlaveMethod    = sprintf('get%1$s', $this->getPlural($sSlaveModelName));
        $aRelations      = $oObject && method_exists($oObject, $sSlaveMethod) ? $oObject->$sSlaveMethod('all') : [];

        if (empty($aRelations) && $sSlaveModelName) {
            $sSlaveManager = sprintf('%1$sManager', $sSlaveModelName);
            $sSlaveFilter  = sprintf('get%1$sByFilter', $this->getPlural($sSlaveModelName));
            if (class_exists($sSlaveManager) && method_exists($sSlaveManager, $sSlaveFilter)) {
                $aRelations = $sSlaveManager::$sSlaveFilter([
                    'pageId'  => Request::param('MasterId'),
                    'showAll' => true,
                ]);
            }
        }

        $sRender = $this->getRenderEngine()
            ->clearVariables()
            ->setVariables(
                [
                    'aMapping' => $this->mapObjects($aRelations),
                ]
            )
            ->render('relationTable', true, 'autocomplete');

        $this->getRenderEngine()
            ->clearVariables()
            ->setVariables(
                [
                    'html' => $sRender,
                ]
            );

        $this->jsend();
    }

    /**
     * Retrieve the plural form of the given model
     *
     * @param string $sModel
     *
     * @return string
     */
    protected function getPlural(string $sModel)
    : string {
        if ($sModel[strlen($sModel) - 1] == 'y') {
            return sprintf('%1$sies', substr($sModel, 0, -1));
        }

        return sprintf('%1$ss', $sModel);
    }

    /**
     * Create a simple mapping for given models
     *
     * @param Model[] $aObjects
     *
     * @return array
     */
    protected function mapObjects(array $aObjects)
    {
        $aMapping = [];
        foreach ($aObjects as $oObject) {
            array_push(
                $aMapping,
                (object) [
                    'value' => getId($oObject),
                    'label' => getTitle($oObject),
                ]
            );
        }

        return $aMapping;
    }
}











