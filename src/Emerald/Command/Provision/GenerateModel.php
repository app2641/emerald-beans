<?php


use Emerald\Command\AbstractCommand;
use Emerald\Command\CommandInterface;

class GenerateModel extends AbstractCommand implements CommandInterface
{

    /**
     * モデル名
     *
     * @var string
     **/
    private $model_name;



    /**
     * コマンドの実行
     *
     * @param  array $params  パラメータ配列
     * @return void
     **/
    public function execute (array $params)
    {
        try {
            $this->_validateParameters($params);
            
            // モデル用ディレクトリの初期化
            $this->_initModelDirectory();

            // モデルクラスファイルを生成する
            $this->_generateModelClassFile();

            // ModelFactoryクラスを更新する
            $this->_updateModelFactory();

            $this->log('generated '.$this->model_name.'Model!', 'success');
        
        } catch (\Exception $e) {
            $this->errorLog($e->getMessage());
        }
    }


    /**
     * パラメータのバリデート
     *
     * @param  array $params
     * @return void
     **/
    private function _validateParameters ($params)
    {
        if (! isset($params[1])) {
            throw new \Exception('新しく生成するモデルクラス名を指定してください');
        }

        if (! defined('LIB')) {
            throw new \Exception('LIB 定数(libraryディレクトリパス)が指定されていません');
        }

        if (! defined('APP')) {
            throw new \Exception('APP定数が指定されていません');
        }


        $this->model_name = ucfirst(strtolower($params[1]));
        if (file_exists(LIB.'/'.APP.'/Model/'.$this->model_name.'Model.php')) {
            throw new \Exception('既にモデルクラスが存在します');
        }
    }


    /**
     * モデルディレクトリの生成
     *
     * @return void
     **/
    private function _initModelDirectory ()
    {
        $model_dir  = LIB.'/'.APP.'/Model';

        if (! is_dir($model_dir)) {
            mkdir($model_dir);
        }

        if (! is_dir($model_dir.'/Query')) {
            mkdir($model_dir.'/Query');
        }

        if (! is_dir($model_dir.'/Column')) {
            mkdir($model_dir.'/Column');
        }

        if (! is_dir(LIB.'/'.APP.'/Factory')) {
            mkdir(LIB.'/'.APP.'/Factory');
        }

        if (! file_exists(LIB.'/'.APP.'/Factory/ModelFactory.php')) {
            $this->_generateModelFactoryFile();
        }
    }


    /**
     * ModelFactory.php を生成する
     *
     * @return void
     **/
    private function _generateModelFactoryFile ()
    {
        $path = LIB.'/'.APP.'/Factory/ModelFactory.php';
        $skeleton_path = __DIR__.'/../../Skeleton/ModelFactorySkeleton.php';
        copy($skeleton_path, $path);
    }


    /**
     * モデルクラスを生成する
     *
     * @return void
     **/
    private function _generateModelClassFile ()
    {
        $skeleton_dir = __DIR__.'/../../Skeleton';
        $model_dir    = LIB.'/'.APP.'/Model';
        $file_path    = $model_dir.'/%s'.$this->model_name.'%s.php';

        // Modelクラス
        $skeleton = file_get_contents($skeleton_dir.'/ModelSkeleton.php');
        $skeleton = str_replace('${app}', APP, $skeleton);
        $skeleton = str_replace('${model}', $this->model_name, $skeleton);
        file_put_contents(sprintf($file_path, '', 'Model'), $skeleton);

        // Queryクラス
        $skeleton = file_get_contents($skeleton_dir.'/QuerySkeleton.php');
        $skeleton = str_replace('${app}', APP, $skeleton);
        $skeleton = str_replace('${query}', $this->model_name, $skeleton);
        file_put_contents(sprintf($file_path, 'Query/', 'Query'), $skeleton);

        // Columnクラス
        $skeleton = file_get_contents($skeleton_dir.'/ColumnSkeleton.php');
        $skeleton = str_replace('${app}', APP, $skeleton);
        $skeleton = str_replace('${column}', $this->model_name, $skeleton);
        file_put_contents(sprintf($file_path, 'Column/', 'Column'), $skeleton);
    }


    /**
     * ModelFactoryクラスを更新する
     *
     * @return void
     **/
    private function _updateModelFactory ()
    {
        $factory   = LIB.'/'.APP.'/Factory/ModelFactory.php';
        $data      = file_get_contents($factory);

        $use_text  = 'use '.APP.'\Model\%s'.$this->model_name.'%s;'.PHP_EOL;
        $func_text = 'public function build'.$this->model_name.'%s ()'.PHP_EOL.
            '    {'.PHP_EOL.
            '        return new '.$this->model_name.'%s();'.PHP_EOL.
            '    }'.PHP_EOL.PHP_EOL.PHP_EOL.
            '    /* ${%s} */';


        // Modelクラス
        if (! preg_match('/build'.$this->model_name.'Model/', $data)) {
            $text = sprintf($use_text, '', 'Model', 'Model').
                '/* ${UseModel} */';
            $data = str_replace('/* ${UseModel} */', $text, $data);

            $text = sprintf($func_text, 'Model', 'Model', 'Model');
            $data = str_replace('/* ${Model} */', $text, $data);
        }

        // Queryクラス
        if (! preg_match('/build'.$this->model_name.'Query/', $data)) {
            $text = sprintf($use_text, 'Query\\', 'Query').
                '/* ${UseQuery} */';
            $data = str_replace('/* ${UseQuery} */', $text, $data);

            $text = sprintf($func_text, 'Query', 'Query', 'Query');
            $data = str_replace('/* ${Query} */', $text, $data);
        }

        // Columnクラス
        if (! preg_match('/build'.$this->model_name.'Column/', $data)) {
            $text = sprintf($use_text, 'Column\\', 'Column').
                '/* ${UseColumn} */';
            $data = str_replace('/* ${UseColumn} */', $text, $data);

            $text = sprintf($func_text, 'Column', 'Column', 'Column');
            $data = str_replace('/* ${Column} */', $text, $data);
        }


        $data = str_replace('${app}', APP, $data);
        file_put_contents($factory, $data);
    }


    /**
     * ヘルプメッセージの表示
     *
     * @return String
     **/
    public static function help ()
    {
        return '引数に指定した名前のモデルクラスを生成します';
    }
}


