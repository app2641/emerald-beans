<?php


namespace Emerald;

use Emerald\Command\AbstractCommand;

class CLI extends AbstractCommand
{

    /**
     * @var CLI
     **/
    private static $instance;



    /**
     * クローン
     *
     * @return void
     **/
    private final function __clone ()
    {
        throw new \Exception('Clone is not allowed against '.get_class($this));
    }



    /**
     * インスタンスを取得する
     *
     * @return CLI
     **/
    public static function getInstance ()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }



    /**
     * 引数で渡されたパラメータのクラスを生成して実行する
     *
     * @param Array $params  パラメータ配列
     * @return boolean
     **/
    public function execute ($params)
    {
        try {
            // LIB定数があるかどうかを判別する
            if (! defined('LIB')) {
                throw new \Exception('LIB定数(libraryディレクトリ)を指定してください。');
            }

            // APP定数があるかどうかを判別する
            if (! defined('APP')) {
                throw new \Exception('APP定数(アプリケーション名)を指定してください');
            }


            if (count($params) > 1) {
                self::getInstance()->executeCommand($params);
            } else {
                self::getInstance()->renderCommandList();
            }

        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }

        return true;
    }



    /**
     * コマンドの実行
     *
     * @param  array  パラメータ引数
     * @return void
     **/
    private function executeCommand ($params)
    {
        array_shift($params);
        $command = ucfirst($params[0]);
        $command_dir = LIB.'/'.APP.'/Commands';

        // コマンドクラスのパスを取得する
        $read_paths = array($command_dir, dirname(__FILE__).'/Command/Provision');
        $command_path = false;
        foreach ($read_paths as $read_path) {
            if (file_exists($read_path.'/'.$command.'.php')) {
                $command_path = $read_path.'/'.$command.'.php';
            }
        }

        // コマンドクラスの有無を確認する
        if ($command_path === false) {
            throw new \InvalidArgumentException(sprintf('%s command is not found!', $command));
        }

        // コマンドの実行
        require_once $command_path;
        $class = new $command;
        $class->execute($params);
    }



    /**
     * コマンドリストを表示する
     *
     * @return void
     **/
    public function renderCommandList ()
    {
        echo PHP_EOL.pack('c',0x1B).'[1m-- EmeraldBeans CommandsList --'.pack('c',0x1B).'[0m'.PHP_EOL;

        $command_dir = LIB.'/'.APP.'/Commands';
        $read_paths = array($command_dir, dirname(__FILE__).'/Command/Provision');

        foreach ($read_paths as $read_path) {
            if ($dh = @opendir($read_path)) {
                while ($command = readdir($dh)) {
                    // コマンドクラスを取得していく
                    if (! is_dir($read_path.'/'.$command) && preg_match('/\.php/', $command)) {
                        require_once $read_path.'/'.$command;

                        $limit = 30;
                        $class = str_replace('.php', '', $command);
                        $class_str = mb_strlen($class);

                        $help = $class::help();
                        $help = explode(PHP_EOL, $help);
                        $text = '';

                        foreach ($help as $row => $help_message) {
                            $space = '';
                            $class_name = ' ';

                            if ($row == 0) {
                                // 一行目はクラス名を記載する
                                $space_limit = $limit - $class_str;
                                $class_name  = '  '.pack('c',0x1B).'[1;33m'.$class.':';
                            } else {
                                $space_limit = $limit;
                            }

                            for ($i = 0; $i < $space_limit; $i++) {
                                $space .= ' ';
                            }

                            echo $class_name.$space.pack('c',0x1B).'[0m'.$help_message.PHP_EOL;
                        }
                    }
                }
                closedir($dh);
            }
        }

        echo PHP_EOL;
    }
}

