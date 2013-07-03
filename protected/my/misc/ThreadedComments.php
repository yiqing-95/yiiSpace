<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-8-15
 * Time: 下午11:30
 * To change this template use File | Settings | File Templates.
 * @see http://www.jongales.com/blog/2009/01/27/php-class-for-threaded-comments/
 */
class ThreadedComments
{
    public $parents = array();
    public $children = array();

    /**
     * @param array $comments
     */
    function __construct($comments)
    {
        foreach ($comments as $comment) {
            if ($comment['parent_id'] === NULL) {
                $this->parents[$comment['id']][] = $comment;
            } else {
                $this->children[$comment['parent_id']][] = $comment;
            }
        }
    }

    /**
     * @param array $comment
     * @param int $depth
     */
    private function format_comment($comment, $depth)
    {
        for ($depth; $depth > 0; $depth--) {
            echo "\t";
        }

        echo $comment['text'];
        echo "\n";
    }

    /**
     * @param array $comment
     * @param int $depth
     */
    private function print_parent($comment, $depth = 0)
    {
        foreach ($comment as $c) {
            $this->format_comment($c, $depth);

            if (isset($this->children[$c['id']])) {
                $this->print_parent($this->children[$c['id']], $depth + 1);
            }
        }
    }

    public function print_comments()
    {
        foreach ($this->parents as $c) {
            $this->print_parent($c);
        }
    }
}

/*
 *
 * $comments = array(  array('id'=>1, 'parent_id'=>NULL,   'text'=>'Parent'),
                    array('id'=>2, 'parent_id'=>1,      'text'=>'Child'),
                    array('id'=>3, 'parent_id'=>2,      'text'=>'Child Third level'),
                    array('id'=>4, 'parent_id'=>NULL,   'text'=>'Second Parent'),
                    array('id'=>5, 'parent_id'=>4,   'text'=>'Second Child')
                );

$threaded_comments = new Threaded_comments($comments);

$threaded_comments->print_comments();

 */
