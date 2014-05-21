EmeraldBeans
=============
EmeraldBeans は俺々コマンド管理クラスだ。

### 定数とディレクトリの準備
動作には LIB と APP 定数の指定が必要。
LIB はディレクトリパス、 APP はアプリケーション名を指定する。

```
<?php
define('LIB', '/Users/hoge/Desktop/Test/library');
define('APP', 'App');
```

LIB ディレクトリの中には APP アプリケーション名の空ディレクトリを作る。

```
$ mkdir /Users/hoge/Desktop/Test/library/App
```


### 起動スクリプトを作る
EmeraldBeans のコマンドを呼ぶスクリプトを作る。  

```
$ touch ./run
$ chmod +x ./run
$ vi ./run
```

```
<?php
use Emerald\CLI;

CLI::getInstance()->execute($argv);
```

### 起動スクリプトを動かす
引数なしで起動すると動かせるコマンドのリストが表示される。

```
$ ./run

-- EmeraldBeans CommandsList --
  Generate:                      引数に指定した名前で新しいコマンドを生成します
```

引数にコマンドを指定すればコマンドクラスが動く。

```
$ ./run Generate Foo
  success:  Foo command is created!
```

LIB/APP/Commands ディレクトリにコマンドクラスが生成されている。  
生成したクラスに任意の処理を記述できる。

```
$ ls library/App/commands/
Foo.php

$ vi library/App/commands/Foo.php
```
