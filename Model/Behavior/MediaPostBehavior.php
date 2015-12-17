<?php
/**
 * Class MediaPostBehavior
 * A Behavior used to automatically save a contact
 */
App::import('Model', 'MediaPost.MediaPostContact');
class MediaPostBehavior extends ModelBehavior
{

    /**
     * Get list id
     * @var int
     */
    protected $listId;

    /**
     * Get fields to parse
     * key is field from model
     * value is field to save in MediaPostContact
     * @var array
     */
    protected $fields = array('name' => 'nome', 'email' => 'email');

    /**
     * Array to save
     * @var array
     */
    protected $toSave = array();

    /**
     * A MediaPostContact Instance
     * @var MediaPostContact
     */
    private $Contact;

    /**
     * Setup
     * @param Model $model
     * @param array $config
     * @throws ErrorException
     */
    public function setup(Model $model, $config = array())
    {
        parent::setup($model, $config);

        if(empty($config['list'])) throw new ErrorException('The list id is necessary to save the contact.');
        $this->listId = $config['list'];

        // If key fields exists
        if(!empty($config['fields'])) $this->fields = $config['fields'];

        $this->Contact = new MediaPostContact(null);

    }

    public function afterSave(Model $model, $created, $options = array())
    {
        if(!empty($this->toSave)) $this->Contact->save($this->toSave);
        return parent::afterSave($model, $created, $options);
    }

    public function beforeValidate(Model $model, $options = array())
    {
        if (!empty($model->data[$model->alias])) {
            $save = array('lista' => $this->listId);
            foreach ($model->data[$model->alias] as $field => $value) {
                if(!empty($this->fields[$field])) {
                    $this->toSave[$this->fields[$field]] = $value;
                }
            }
            $this->toSave = array_merge($save, $this->toSave);
        }
        return parent::beforeValidate($model, $options);
    }


}