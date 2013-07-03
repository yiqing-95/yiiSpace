Documentation
=============
This yii model extension logs with every created or updated row in your database **who created** and **who updated** it. This may interest you when working in a group of more people sharing same privileges.

So you can see who changed what record and - in combination with the timestampable behavior - when ti was created.

Requirements
------------

Yii 1.0 or above

Installation 
------------

1. Extract the release file under *protected/components*, it's on you.
2. Create **two extra comlumns** for each table you want to track. 
Default column names are *created_by* and *updated_by*. Column type should be varchar(128)
3. Add this behavior to your desired model:

Usage 
-----

In your model you want to track either extend your behaiors function or just create a new one with the following content:

    :::php
    public function behaviors() {
        return array(
            'Blameable' => array(
                'class'=>'application.components.BlameableBehavior',
            ),
        );
    }

Optionally you can define own column names for *created_by* and *updated_by*:

    :::php
    public function behaviors() {
        return array(
            'Blameable' => array(
                'class'=>'application.components.BlameableBehavior',
                'createdByColumn' => *'my_own_creator_column_name'*,
                'updatedByColumn' => *'my_own_updater_column_name'*,
            ),
        );
    }

