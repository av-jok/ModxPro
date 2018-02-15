<?php
$xpdo_meta_map['comTotal']= array (
  'package' => 'modxpro',
  'version' => '1.1',
  'table' => 'app_community_totals',
  'extends' => 'xPDOObject',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
    'id' => NULL,
    'class' => NULL,
    'topics' => NULL,
    'comments' => NULL,
    'views' => NULL,
    'stars' => NULL,
    'rating' => NULL,
    'rating_plus' => NULL,
    'rating_minus' => NULL,
  ),
  'fieldMeta' => 
  array (
    'id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'index' => 'pk',
    ),
    'class' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'index' => 'pk',
    ),
    'topics' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => true,
    ),
    'comments' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => true,
    ),
    'views' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => true,
    ),
    'stars' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => true,
    ),
    'rating' => 
    array (
      'dbtype' => 'smallint',
      'precision' => '5',
      'phptype' => 'integer',
      'null' => true,
    ),
    'rating_plus' => 
    array (
      'dbtype' => 'smallint',
      'precision' => '5',
      'phptype' => 'integer',
      'null' => true,
      'attributes' => 'unsigned',
    ),
    'rating_minus' => 
    array (
      'dbtype' => 'smallint',
      'precision' => '5',
      'phptype' => 'integer',
      'null' => true,
      'attributes' => 'unsigned',
    ),
  ),
  'indexes' => 
  array (
    'PRIMARY' => 
    array (
      'alias' => 'PRIMARY',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'class' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'owner' => 
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
  'aggregates' => 
  array (
    'User' => 
    array (
      'class' => 'modUser',
      'local' => 'uid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Section' => 
    array (
      'class' => 'comSection',
      'local' => 'id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
      'criteria' => 
      array (
        'local' => 
        array (
          'class' => 'comSection',
        ),
      ),
    ),
    'Topic' => 
    array (
      'class' => 'comTopic',
      'local' => 'id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
      'criteria' => 
      array (
        'local' => 
        array (
          'class' => 'comTopic',
        ),
      ),
    ),
    'Comment' => 
    array (
      'class' => 'comComment',
      'local' => 'id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
      'criteria' => 
      array (
        'local' => 
        array (
          'class' => 'comComment',
        ),
      ),
    ),
    'Thread' => 
    array (
      'class' => 'comThread',
      'local' => 'id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
      'criteria' => 
      array (
        'local' => 
        array (
          'class' => 'comThread',
        ),
      ),
    ),
  ),
);
