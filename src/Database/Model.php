<?php
declare(strict_types=1);

/**
 * ZiyoREN Model
 *
 * Copyright 2020, Jianshan Liao
 * Released under the MIT license
 */


namespace ziyoren\Database;


class Model
{

    public $model = null;

    protected $tableName = '';      //不含前缀的表名

    protected $realTableName = '';  //完整表名


    public function __construct()
    {

        $this->model = (true === ZIYOREN_AT_SWOOLE) ? new BaseModel() : new PDO();

        if (empty($this->realTableName)) {

            if (empty($this->tableName)) {

                $this->tableName = $this->classTableName();

            }

        } else {

            $this->tableName = $this->realTableName;

            $this->model->prefixEmpty();

        }
    }


    /**
     * 将类名转为小写字符串
     * @return string
     */
    protected function classTableName() :string
    {
        return strtolower(ltrim(str_ireplace(__NAMESPACE__, '', get_called_class()), '\\'));
    }

    /**
     * 以下方法中的$table参数实现自动赋值
     * Medoo Method:
     * Query
     * select($table, $columns, $where)
     * insert($table, $data)
     * update($table, $data, $where)
     * delete($table, $where)
     * replace($table, $columns, $where)
     * get($table, $columns, $where)
     * has($table, $where)
     * rand($table, $column, $where)
     * Aggregation
     * count($table, $where)
     * max($table, $column, $where)
     * min($table, $column, $where)
     * avg($table, $column, $where)
     * sum($table, $column, $where)
     * Management
     * create($table, $columns, $options)
     * drop($table)
     */

    protected $has_table = [
        'select', 'insert', 'update', 'delete', 'replace', 'get', 'has', 'rand',
        'count', 'max', 'min', 'avg', 'sum',
        'create', 'drop',
    ];


    /**
     * 重载Medoo类的方法，实现以模型定义的表名自动赋值
     * @param $name
     * @param $arguments
     * @return false|mixed
     */
    public function __call($name, $arguments)
    {
        $name = strtolower($name);

        if (in_array($name, $this->has_table)) {

            $arguments = array_unshift($arguments, $this->tableName);

        }

        return call_user_func_array([$this->model, $name], $arguments);
    }


    public function debug(): Model
    {
        $this->model->debug();

        return $this;
    }


    /**
     * 返回一个Medoo操作对象，在该对象下可以按Medoo语法实现数据库操作
     * @return BaseModel|PDO
     */
    public function db()
    {
        return $this->model;
    }


}