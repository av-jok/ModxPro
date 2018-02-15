<?php
$xpdo_meta_map['appAuthClient']= array (
  'package' => 'modxpro',
  'version' => '1.1',
  'table' => 'app_auth_clients',
  'extends' => 'xPDOObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'id' => NULL,
    'secret' => NULL,
    'redirect_uri' => NULL,
    'grant_types' => NULL,
    'scope' => NULL,
    'user_id' => NULL,
    'title' => NULL,
    'logo' => NULL,
    'description' => NULL,
  ),
  'fieldMeta' => 
  array (
    'id' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
    ),
    'secret' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'varchar',
      'null' => false,
    ),
    'redirect_uri' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
    ),
    'grant_types' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'scope' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
    'user_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'int',
      'null' => true,
    ),
    'title' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
    'logo' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'secret' => 
    array (
      'alias' => 'secret',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'secret' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'user_id' => 
    array (
      'alias' => 'user_id',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'user_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'composites' => 
  array (
    'Codes' => 
    array (
      'class' => 'AppAuthCode',
      'local' => 'id',
      'foreign' => 'client_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
  'aggregates' => 
  array (
    'User' => 
    array (
      'class' => 'modUser',
      'local' => 'user_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'UserProfile' => 
    array (
      'class' => 'modUserProfile',
      'local' => 'user_id',
      'foreign' => 'internalKey',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
