<?PHP
App::uses('AppModel', 'Model');

class MediaPostList extends AppModel
{

    public $useDbConfig = 'mediapost';

    public $useTable = false;

    public $name = 'MediaPostList';

    public $validate = array();

    public $_schema = array(
        'cod' => array(
            'type' => 'integer',
            'null' => true,
            'length' => 11
        ),
        'nome' => array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'total' => array(
            'type' => 'integer',
            'null' => true,
            'length' => 11
        ),
        'total_ativo' => array(
            'type' => 'integer',
            'null' => true,
            'length' => 11
        ),
        'total_inativo' => array(
            'type' => 'integer',
            'null' => true,
            'length' => 11
        ),
    );

    public function __construct($id){

        $id = null;
        $table = null;
        $ds =  null;
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'nome' => array(
                'notBlank' => array('rule' => 'notBlank', 'message' => __d('media_post','Não deixe o nome da Lista em branco'))
            )
        );
    }


}