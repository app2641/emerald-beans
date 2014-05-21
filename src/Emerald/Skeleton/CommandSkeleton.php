<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

class ${command} extends AbstractCommand implements CommandInterface
{

    /**
     * コマンドの実行
     *
     * @param Array $params  パラメータ配列
     * @return void
     **/
    public function execute (Array $params)
    {
        try {

        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }



    /**
     * ヘルプメッセージの表示
     *
     * @return String
     **/
    public static function help ()
    {
        return '';
    }
}
