<?php
/**
 * Created by IntelliJ IDEA.
 * User: moein
 * Date: 10/7/16
 * Time: 4:04 PM
 */

namespace moein7tl\Yii2ExcelAction\actions;

use moein7tl\Yii2ExcelAction\exceptions\InvalidColumnsException;
use Yii;
use yii\db\ActiveRecord;
use yii\rest\Action;
use moonland\phpexcel\Excel;
use moein7tl\Yii2ExcelAction\exceptions\InvalidModelException;

class ExportAction extends Action {

    public $columns;
    public $headers;
    public $properties;
    public $queryColumns;

    public function run()
    {
        /**
         * @var $model ActiveRecord
         */
        $model      =   $this->modelClass;
        $condition   =   [];

        if (!isset($model) || ! class_exists($model) || is_subclass_of()) {
            throw new InvalidModelException("Model {$model} doesn't exist.");
        }
        
        if (!isset($this->queryColumns) || count($this->queryColumns) == 0){
            throw new InvalidColumnsException("Query Columns should defined.");
        }

        $data   =   Yii::$app->request->post();

        foreach ($this->queryColumns as $column) {
            $condition[]    =   [$column => $data[$column]];
        }

        return Excel::export([
            'asAttachment'  =>  true,
            'mode'          =>  'export',
            'columns'       =>  $this->columns,
            'headers'       =>  $this->headers,
            'properties'    =>  $this->properties,
            'models'        =>  $model::find()->where($condition)->all()
        ]);
    }
}