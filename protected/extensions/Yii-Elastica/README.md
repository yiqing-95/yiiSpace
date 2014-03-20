Yii-Elastica
============

a Yii application component, Dataprovider and autoloader, to use Elastica PHP library inside Yii framework.
the aim of this componenets is to create a dataprovider that can be used  with all other Yii extesions or widgets (ex: CGridView).

### Main connfigrations

 ```php

'preload' => array('importelastica'),

'components' => array(
  ...
  'importelastica'=>array(
    'class' => 'application.extensions.ElasticaLoader',
    'libPath' => 'application.lib', //assume you installed Elastica to /lib/
  ),
  'elastica' => array(
    'class' => 'application.components.Elastica',
    'host' => 'localhost',
    'port' => '9200',
    'debug' => true
  ),
  ...
)
```


### Usage example

```php
    
    $elastica_query = new Elastica\Query();
    $term_filter = new \Elastica\Filter\Term();
    $term_filter->setTerm('name', 'Elastica_test');
    $elastica_query->setFilter($term_filter);
    
    $dataprovider =  ElasticaDataProvider('indexname', $elastica_query, array(
      'sort' => array(
        'attributes' => array('attribute.desc',),
      ),
      'pagination' => array(
        'pageSize' => 30,
      ),
    ), 'type_name_optional');
      
    $data = $dataprovider->getData();
      
```
type name will be used ad the model name by default, if type name was not provided, the data provider will try to use the index name as a model name.


for more info about using elastica PHP library please read the documentation here:
[ ruflin/Elastica](https://github.com/ruflin/Elastica)
