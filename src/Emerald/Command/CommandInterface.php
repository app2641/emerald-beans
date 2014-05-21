<?php


namespace Emerald\Command;

interface CommandInterface
{

    /**
     * コマンドの実行
     *
     * @param Array $params  引数パラメータ
     * @return void
     **/
    public function execute (Array $params);



    /**
     * ヘルプメッセージ
     *
     * @return String
     **/
    public static function help ();
}
