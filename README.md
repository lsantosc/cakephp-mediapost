**CakePHP MediaPost Datasource**
==========================

With this datasource, you can easily manage some functionalities of [MediaPost](http://mediapost.com.br).

It uses the [Diego Matos](https://github.com/diegosmts) [MediaPost-API](https://github.com/diegosmts/MediaPost-API).

*Configuration*
--------
> Save in the  folder **app/Plugin/MediaPost**, with git clone or downloading the zip source;
> Load plugin in app/Config/bootstrap.php:
> ```php
> CakePlugin::load('MediaPost');
>```
> Use the *bootstrap.sample.php* to configure consumerKey, consumerSecret, token and tokenSecret;
> **or** place in app/Config/bootstrap.php:
>```php
> Configure::write('MediaPost', array(
>   'consumerKey' => 'consumer-key-here',
>   'consumerSecret' => 'consumer-secret-here',
>   'token' => 'token-here',
>   'tokenSecret' => 'token-secret-here'
> ));
>```
> Place in app/Config/database.php:
> ```php
> public $mediapost = array(
>      'datasource' => 'MediaPost.MediaPostSource'
> );
> ```

*Usage Example*
--------

**Lists**
```php
// load model
public $uses = array(
     'MediaPost.MediaPostList',
);
// or in your action
$this->loadModel('MediaPost.MediaPostList');

// Retrieve lists
$lists = $this->MediaPostList->find('all');

// Retrieve a list by id/code
$list = $this->MediaPostList->find('first', array(
	'conditions' => array('id' => 1)
));

// Save one list
$this->MediaPostList->save(
	array(
		'MediaPostList' => array(
			 // If the code is passed, the registry is updated in MediaPost
			 'cod' => 1,
	         'nome' => 'List Name',
		)
	)
);

// Save many
$this->MediaPostList->saveMany(array(
	array(
		'MediaPostList' => array(
			 'cod' => 1,
	         'nome' => List Name 1
		)
	),
	array(
		'MediaPostList' => array(
			 'cod' => 1,
	         'nome' => 'List Name 2'
		)
	),
));


```
**Contacts**

```php
// load model
public $uses = array(
     'MediaPost.MediaPostContact',
);
// or in your action
$this->loadModel('MediaPost.MediaPostContact');

// List contact available fields
$fields = $this->MediaPostContact->find('all', array(
	'conditions' => array('fields' => true)
));

// Find by contact id/code
$contact = $this->MediaPostContact->find('first', array(
	'conditions' => array(
		'id' => 1
	)
));

// Find by contact email
$contact = $this->MediaPostContact->find('first', array(
	'conditions' => array(
		'email' => 'johndoe@example.com'
	)
));

// Save one contact
$this->MediaPostContact->save(
	array(
		'MediaPostContact' => array(
			 'lista' => 1,
	         'nome' => 'John Doe',
	         'email' => 'johndoe@example.com'
		)
	)
);

// Save many
$this->MediaPostContact->saveMany(array(
	array(
		'MediaPostContact' => array(
			 'lista' => 1,
	         'nome' => 'John Doe',
	         'email' => 'johndoe@example.com'
		)
	),
	array(
		'MediaPostContact' => array(
			 'lista' => 1,
	         'nome' => 'Edgar Allen',
	         'email' => 'edgarallen@example.com'
		)
	),
));

// Delete a Contact
$this->MediaPostContact->delete(1);

```

**Message**
```php
// load model
public $uses = array(
     'MediaPost.MediaPostMessage',
);
// or in your action
$this->loadModel('MediaPost.MediaPostMessage');

// Create/Save a message
 $this->MediaPostMessage->save(array(
    'uidcli' => 123, // Your system code, used to reference only
 	// If code is passed, the message will be updated
	'cod' => 1,
	'pasta' => 'Campaign Name'
    'nome_remetente' => 'John Doe',
    'email_remetente' => 'johndoe@anotherdomainexample.com',
    'assunto' => 'Send test',
    'mensagem' => "<i>Example</i> of a message, with some <strong>HTML</strong> inside."
 ));


// Send a message
// Ex.: $this->MediaPostMessage->send($messageId, $listId, $filters = array());
$this->MediaPostMessage->send(5, 4, array(
	'nome' => 'Jonh'
));

```