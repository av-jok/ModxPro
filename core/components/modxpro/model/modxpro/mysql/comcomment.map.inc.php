<?php
$xpdo_meta_map['comComment']= array (
  'package' => 'modxpro',
  'version' => '1.1',
  'table' => 'app_community_comments',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'thread' => NULL,
    'parent' => NULL,
    'text' => '',
    'ip' => '0.0.0.0',
    'rating' => 0,
    'rating_plus' => 0,
    'rating_minus' => 0,
    'createdon' => NULL,
    'createdby' => NULL,
    'editedon' => NULL,
    'editedby' => NULL,
    'published' => NULL,
    'deleted' => 0,
    'deletedon' => NULL,
    'deletedby' => NULL,
  ),
  'fieldMeta' => 
  array (
    'thread' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
    ),
    'parent' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
    ),
    'text' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'text',
      'null' => false,
      'default' => '',
    ),
    'ip' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '16',
      'phptype' => 'string',
      'null' => false,
      'default' => '0.0.0.0',
    ),
    'rating' => 
    array (
      'dbtype' => 'smallint',
      'precision' => '5',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'rating_plus' => 
    array (
      'dbtype' => 'smallint',
      'precision' => '5',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'rating_minus' => 
    array (
      'dbtype' => 'smallint',
      'precision' => '5',
      'phptype' => 'integer',
      'null' => true,
      'default' => 0,
    ),
    'createdon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => false,
    ),
    'createdby' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
    ),
    'editedon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'editedby' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
    ),
    'published' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => false,
    ),
    'deleted' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => false,
      'default' => 0,
    ),
    'deletedon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'deletedby' => 
    array (
      'dbtype' => 'integer',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'thread' => 
    array (
      'alias' => 'thread',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'thread' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'parent' => 
    array (
      'alias' => 'parent',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'parent' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'deleted' => 
    array (
      'alias' => 'deleted',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'deleted' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'published' => 
    array (
      'alias' => 'published',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'published' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'rating' => 
    array (
      'alias' => 'rating',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'rating' => 
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
    'Total' => 
    array (
      'class' => 'comTotal',
      'local' => 'id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'local',
      'criteria' => 
      array (
        'foreign' => 
        array (
          'class' => 'comComment',
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'User' => 
    array (
      'class' => 'modUser',
      'local' => 'createdby',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'UserProfile' => 
    array (
      'class' => 'modUserProfile',
      'local' => 'createdby',
      'foreign' => 'internalKey',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Thread' => 
    array (
      'class' => 'comThread',
      'local' => 'thread',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Parent' => 
    array (
      'class' => 'comComment',
      'local' => 'parent',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Children' => 
    array (
      'class' => 'comComment',
      'local' => 'id',
      'foreign' => 'parent',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
