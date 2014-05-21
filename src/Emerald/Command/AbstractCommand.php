<?php


namespace Emerald\Command;

abstract class AbstractCommand
{

    /**
     * ターミナルにログを表示する
     *
     * @param String $msg  ログメッセージ
     * @param String $title  タイトル
     * @return void
     **/
    public final function log ($msg, $title = null)
    {
        if (is_null($title)) {
            $log = '  '.pack('c',0x1B)."[1m". $msg.pack('c',0x1B)."[0m".PHP_EOL;

        } else {
            $log = '  '.pack('c',0x1B)."[1;32m". $title.':'. pack('c',0x1B)."[0m".'  '.
                pack('c',0x1B)."[1m". $msg. pack('c',0x1B)."[0m".PHP_EOL;
        }

        echo $log;
    }



    /**
     * エラーログを表示する
     *
     * @param String $msg  ログメッセージ
     * @return void
     **/
    public final function errorLog ($msg)
    {
        $log = '  '.pack('c',0x1B)."[1;31m". $msg.pack('c',0x1B)."[0m".PHP_EOL;
        echo $log;
    }
}
