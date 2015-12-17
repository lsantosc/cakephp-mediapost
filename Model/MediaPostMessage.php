<?php
App::uses('AppModel', 'Model');
App::uses('ConnectionManager', 'Model');

class MediaPostMessage extends AppModel
{
    private $dataSource;

    public $useDbConfig = 'mediapost';

    public $useTable = false;

    public $name = 'MediaPostMessage';

    public $validate = array();

    public $_schema = array(
        'lista' => array(
            'type' => 'integer',
            'null' => true,
            'length' => 11
        ),
        'uidcli' => array(
            'type' => 'integer',
            'null' => true,
            'length' => 11
        ),
        'cod' => array(
            'type' => 'integer',
            'null' => true,
            'length' => 11
        ),
        'assunto' => array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'pasta' => array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'ganalytics' => array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'nome_remetente' => array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'email_remetente' => array(
            'type' => 'string',
            'null' => true,
            'length' => 256
        ),
        'mensagem' => array(
            'type' => 'text',
            'null' => true
        ),
        'texto' => array(
            'type' => 'text',
            'null' => true
        )
    );


    /**
     * Schedule a message trigger in Media Post (extra method)
     * @param $messageId
     * @param $listId
     * @param array $conditions
     * @return string
     */
    public function send($messageId, $listId, $conditions = array())
    {
        if(!is_array($listId)) $listId = array($listId);
        $data = array(
            'lista' => $listId,
        );
        if(!empty($conditions)) $data['filtro'] = $conditions;
        return $this->dataSource->api->put("envio/cod/".$messageId, $data);
    }


    public function __construct($id){
        $id = null;
        $table = null;
        $ds =  null;
        parent::__construct($id, $table, $ds);
        $this->dataSource = ConnectionManager::getDataSource('mediapost');
        $this->validate = array(
            'assunto' => array(
                'notBlank' => array('rule' => 'notBlank', 'message' => __d('media_post','Não deixe o assunto em branco.'))
            )
        );
    }

}