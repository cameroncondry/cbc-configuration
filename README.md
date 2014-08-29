#Configuration

A simple configuration object for your every configuration need.

##Getting Started

@TODO: Complete getting started

Example:

```php
use CBC\Utility\Configuration;

$config = [
    'database' => [
        'username' => 'db_username',
        'password' => 'db_password'
    ]
];

$config['database.name'] = 'db_name';

$configuration = new Configuration($config);

var_dump($configuration->get('database.username'));
// outputs:
// string (11) 'db_username'

var_dump($configuration->get('database'));
// outputs:
// array(3) [
//      'username' => 'db_username',
//      'password' => 'db_password',
//      'name'     => 'db_name'
// ]
```
