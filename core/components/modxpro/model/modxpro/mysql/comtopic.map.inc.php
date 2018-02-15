<?php
$xpdo_meta_map['comTopic']= array (
  'package' => 'modxpro',
  'version' => '1.1',
  'table' => 'app_community_topics',
  'extends' => 'xPDOSimpleObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'parent' => NULL,
    'pagetitle' => '',
    'introtext' => '',
    'content' => '',
    'createdon' => NULL,
    'createdby' => NULL,
    'published' => 0,
    'publishedon' => NULL,
    'publishedby' => 0,
    'editedon' => NULL,
    'editedby' => NULL,
    'deleted' => 0,
    'deletedon' => NULL,
    'deletedby' => 0,
    'properties' => NULL,
  ),
  'fieldMeta' => 
  array (
    'parent' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'int',
      'attributes' => 'unsigned',
      'null' => false,
    ),
    'pagetitle' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '255',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'introtext' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'content' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => true,
      'default' => '',
    ),
    'createdon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'createdby' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'int',
      'attributes' => 'unsigned',
      'null' => false,
    ),
    'published' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
      'default' => 0,
    ),
    'publishedon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'publishedby' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'int',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'editedon' => 
    array (
      'dbtype' => 'datetime',
      'phptype' => 'datetime',
      'null' => true,
    ),
    'editedby' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'int',
      'attributes' => 'unsigned',
      'null' => true,
    ),
    'deleted' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
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
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'int',
      'attributes' => 'unsigned',
      'null' => true,
      'default' => 0,
    ),
    'properties' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'json',
      'null' => true,
    ),
  ),
  'indexes' => 
  array (
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
    'pagetitle' => 
    array (
      'alias' => 'pagetitle',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'pagetitle' => 
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
    'Thread' => 
    array (
      'class' => 'TicketThread',
      'local' => 'id',
      'foreign' => 'resource',
      'cardinality' => 'one',
      'owner' => 'local',
    ),
    'Total' => 
    array (
      'class' => 'TicketTotal',
      'local' => 'id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'local',
      'criteria' => 
      array (
        'foreign' => 
        array (
          'class' => 'comTopic',
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'Parent' => 
    array (
      'class' => 'modResource',
      'local' => 'parent',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);
