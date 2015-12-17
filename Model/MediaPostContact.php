<?PHP
App::uses('AppModel', 'Model');
App::uses('ConnectionManager', 'Model');

class MediaPostContact extends AppModel
{

    private $dataSource;

    public $useDbConfig = 'mediapost';

    public $useTable = false;

    public $name = 'MediaPostContact';

    public $validate = array();

    public $_schema = array(
        'sexo' => array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'lista' => array(
            'type' => 'integer',
            'null' => true,
            'length' => 11
        ),
        'endereco' => array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'cep' => array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'telefone' => array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'celular'=> array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'nome' => array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'email'=> array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'estado'=> array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'data_nascimento'=> array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'cidade'=> array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'bairro'=> array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        )
    );

    /**
     * Delete a contact
     * @param null $id
     * @param bool $cascade
     * @return bool
     */
    public function delete($id = null, $cascade = true)
    {
       if(isset($id)) {
           $this->dataSource->api->delete("contato/cod/$id");
           return true;
       }
       return parent::delete($id, $cascade);
    }


    public function __construct($id){

        $id = null;
        $table = null;
        $ds =  null;
        parent::__construct($id, $table, $ds);
        $this->dataSource = ConnectionManager::getDataSource('mediapost');
        $this->validate = array(
            'email' => array(
                'notBlank' => array('rule' => 'notBlank', 'message' => __d('media_post', 'Não deixe o e-mail em branco')),
                'email' => array('rule' => 'email', 'message' => __d('media_post', 'Digite um e-mail válido'))
            )
        );
    }


}