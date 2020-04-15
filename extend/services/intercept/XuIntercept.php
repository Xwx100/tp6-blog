<?php


namespace services\intercept;

/**
 * Class XuIntercept
 *
 * @method mixed auth() 认证 成功
 * @method mixed setSession(string $str='cli') （req模式 | cli模式） 设置 session
 * @method mixed redirect()
 * @method mixed json()
 * @package services\intercept
 */
class XuIntercept {

    protected $urlToDo = null;
    protected $conf = null;
    protected $isCli = null;

    /**
     * XuIntercept constructor.
     *
     * @param array $conf ['setSession' => '', 'redirect', 'json', 'auth'
     */
    public function __construct(array $conf) {
        $this->conf = array_merge($conf, include 'xu_intercept.php');
        $this->makeIsCli();
    }

    public function run() {
        if ($this->conf['isOpen']) {
            if ($this->auth()) {
                $this->setSession('req');
            } else {

            }
        } else {
            // 设置 临时 用户 cli
            $this->setSession('cli');
            // cli 模式 固定 json
            if ($this->isCli) {
                return $this->json();
            }
        }
        // 请求 模式 根据 url 调度
        // url 调度 默认 json方法
        return $this->startUrlToDo();
    }

    public function makeIsCli() {
        !isset($this->isCli) && ($this->isCli = PHP_SAPI === 'cli');
    }

    /**
     * 开启 url 调度 （url调度 默认 json方法）
     *
     * @return mixed|null
     */
    public function startUrlToDo() {
        if (!$this->urlToDo) {
            return $this->json();
        }
        $curUrl = $_REQUEST['s'];
        foreach ($this->conf['interceptUrl'] as $url => $toDo) {
            if (false !== stripos($curUrl, $url)) {
                if (is_callable($toDo)) {
                    return call_user_func($toDo, $this);
                }
            }
        }
        return $this->json();
    }

    /**
     * 检查 url 调度
     *
     * @throws \Exception
     */
    public function checkUrlToDo() {
        if (empty($this->conf['interceptUrl'])) {
            return;
        }
        if (empty($_REQUEST['s'])) {
            throw new \Exception('[ conf.interceptUrl ] 为空');
        }
        $this->urlToDo = true;
    }

    /**
     * 调用
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments) {
        return call_user_func_array($this->conf[$name], $arguments);
    }
}

