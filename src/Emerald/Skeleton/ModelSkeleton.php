<?php


namespace ${app}\Model;

use Garnet\Model\AbstractModel;
use Garnet\Container,
    ${app}\Factory\ModelFactory;

class ${model}Model extends AbstractModel
{

    /**
     * @var ${app}\Model\Query\${model}Query
     **/
    protected $query;



    /**
     * コンストラクタ
     *
     * @return void
     **/
    public function __construct ()
    {
        $container = new Container(new ModelFactory);
        $this->query = $container->get('${model}Query');
    }
}
