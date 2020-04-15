<?php
/**
 * Class Make
 *
 * @package app\admin\sqls
 */

namespace admin\make;


class Make {

    public $conf = null;

    public function __construct() {
        $this->conf = include 'make.admin.php';
    }

    /**
     * 格式化 sql语句文件
     *
     * @return $this
     * @throws \Exception
     */
    public function getFormatSqlFile() {
        $fmtConf = $this->conf['admin_format'];
        if (empty($fmtConf)) {
            throw new \Exception('未配置 格式化');
        }

        $inputFileName = $fmtConf['input_filename'];
        $outFileName = $fmtConf['out_filename'];
        $fm = $fmtConf['replace'];

        $sql = file_get_contents($inputFileName);
        if (false === $sql) {
            throw new \Exception('输入sql文件位置 配置出错');
        }
        $outSql = str_replace(array_keys($fm), array_values($fm), $sql);

        if (false === file_put_contents($outFileName, $outSql)) {
            throw new \Exception('输出sql文件位置 配置出错');
        };

        return $this;
    }

    /**
     * 执行sql脚本 (请自行 切换到 可用)
     */
    public function createFile() {
        shell_exec('mysql < admin.format.sql');
    }
}