<?php
$xpdo_meta_map['comSection']= array (
  'package' => 'modxpro',
  'version' => '1.1',
  'extends' => 'modResource',
  'tableMeta' => 
  array (
    'engine' => 'InnoDB',
  ),
  'fields' => 
  array (
  ),
  'fieldMeta' => 
  array (
  ),
  'composites' => 
  array (
    'Topics' => 
    array (
      'class' => 'comTopic',
      'local' => 'id',
      'foreign' => 'parent',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
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
          'class' => 'comSection',
        ),
      ),
    ),
  ),
);
