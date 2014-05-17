<?php

// CREATE TABLE test_table (
//    id int(11) NOT NULL AUTO_INCREMENT,
//    foo varchar(50) NOT NULL,
//    bar int(11) NOT NULL,
//    PRIMARY KEY (`id`)
// )

class DbModelStub extends \PhpBase\Db\Model
{
    public static $_table = 'test_table';

    protected static $_defaults = [
        'id' => 0,
        'foo' => 'defaultfoo',
        'bar' => 0
    ];
}
