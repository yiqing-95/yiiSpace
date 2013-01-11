# EColumnListView
This extension modified CListView to support multiple items per line.

Yii default CListView displays one _view item per line. 
With this widget you can display multiple items per line.

## Screenshot

![Screenshot](https://github.com/bekos/ColumnListView/raw/master/screenshot.png)

## Requirements 

Tested with Yii 1.1.10, but should work with previous versions too.

## Usage 

 * Checkout source code to your project, for example to ext.widgets
 * Use it, as any input widget. (Replace 'zii.widgets.CListView' with 'ext.widgets.EColumnListView')

## Example:

    $this->widget('ext.widgets.EColumnListView', array(
        'dataProvider' => $dataProvider,
        'itemView' => '_view',
        'columns' => 3
    ));
    
## Resources

[Source] (https://github.com/bekos/ColumnListView)