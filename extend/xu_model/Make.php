<?php


namespace xu_model;

use think\Db;

/**
 * 定制 数据库 模型类
 *
 * Class Make
 *
 * @package xu_model
 */
class Make {

    protected $conf = null;

    public function __construct(array $conf = []) {
        $this->conf = array_merge($conf, include 'make.model.php');
    }

    /**
     * 创建 模型类
     *
     * @param string $database
     *
     * @return $this
     */
    public function getDataBaseProp(string $database) {
        $modelTpl = file_get_contents($this->conf['input_tpl_file']);
        $dataPre = $this->conf['database_pre'];
        $outModelDir = $this->conf['out_tpl_dir'];

        $tables = $this->getCon()->getTables($database);
        foreach ($tables as $table) {
            $fs = $this->getCon()->getFields($table);
            array_walk($fs, function (&$v, $k) {
                $t = [
                    'before_field' => $k,
                ];
                $v['comment'] && $t['comment'] = $v['comment'];
                $v = $t;
            });
            // 没有前缀的表名
            $name = implode('', array_map('ucfirst', explode('_', str_replace($dataPre, '', $table))));
            $replace = array_merge($this->conf['replace'], [
                    '%name'          => $name,
                    '%prop'          => var_export($fs, true),
                    '%table_comment' => (string)current($this->getDb()->query("select table_name,table_comment from information_schema.tables where table_schema='$database' and table_name='$table'"))['table_comment']
                ]);
            $m = str_replace(array_keys($replace), array_values($replace), $modelTpl);
            $fName = implode(DIRECTORY_SEPARATOR, [$outModelDir, $name]) . '.php';
            if ($this->conf['is_delete_before'] && file_exists($fName)) {
                var_dump("[ $database.$table ] 成功删除之前存在的模型类");
                unlink($fName);
            }
            $ok = file_put_contents($fName, $m);
            if (false !== $ok) {
                var_dump("[ $database.$table ] 成功创建模型类");
            }
        }
        return $this;
    }

    /**
     * @return \think\db\ConnectionInterface
     */
    public function getCon() {
        return app()->db->getConnection();
    }

    /**
     * @return Db
     */
    public function getDb() {
        return app()->db;
    }
}