<?php

use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

class Generate extends AbstractCommand implements CommandInterface
{

    /**
     * コマンド名
     *
     * @var string
     **/
    private $command_name;


    
    /**
     * コマンドの実行
     *
     * @param Array $params  パラメータ配列
     * @return void
     **/
    public function execute (Array $params)
    {
        try {
            $this->_validateParameters($params);
            
            // スケルトンファイルを取得する
            $skeleton = $this->_getSkeletonFile();

            // スケルトンファイルのコンテンツを置換
            $skeleton = str_replace('${command}', $this->command_name, $skeleton);
            file_put_contents(LIB.'/'.APP.'/Commands/'.$this->command_name.'.php', $skeleton);

            $this->log($this->command_name.' command is created!', 'success');
        
        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }



    /**
     * パラメータをバリデードする
     *
     * @param  array $params パラメータ引数
     * @return void
     **/
    private function _validateParameters ($params)
    {
        if (! isset($params[1])) {
            throw new \Exception('新しく生成するコマンド名を指定してください');
        }

        $command_dir = LIB.'/'.APP.'/Commands';
        if (! is_dir($command_dir)) {
            mkdir($command_dir);
        }

        // 既に同じ名前のコマンドがないかを確認する
        $this->command_name = ucfirst($params[1]);
        foreach (array($command_dir, dirname(__FILE__)) as $read_path) {
            if (file_exists($read_path.'/'.$this->command_name.'.php')) {
                throw new \Exception('既に同じ名前のコマンドが存在しています');
            }
        }
    }



    /**
     * スケルトンファイルを取得する
     *
     * @return String
     **/
    private function _getSkeletonFile ()
    {
        $skeleton_path = dirname(__FILE__).'/../../Skeleton/CommandSkeleton.php';
        return file_get_contents($skeleton_path);
    }



    /**
     * ヘルプメッセージの表示
     *
     * @return String
     **/
    public static function help ()
    {
        return '引数に指定した名前で新しいコマンドを生成します';
    }
}
