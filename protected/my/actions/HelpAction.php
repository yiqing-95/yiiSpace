<?php
/**
 * User: yiqing
 * Date: 12-7-27
 * Time: 上午7:16
 *----------------------------------------------------------------
 * To change this template use File | Settings | File Templates.
 *----------------------------------------------------------------
 *
 *...........................................................
 * @author yiqing <yiqing_95@qq.com>
 * @link https://github.com/yiqing-95
 * @license http://www.opensource.org/licenses/mit-license.php
 * @version 0.1
 * @package *.*
 *...........................................................
 */
class HelpAction extends CAction
{


    /**
     * @Desc('use this action to list the all available actions of this controller ')
     */
    public function run()
    {
        $controller = $this->getController();
        require(Yii::getPathOfAlias('application.vendors.addendum') . DS . 'annotations.php');
        //延迟导入注解类 其他地方又不用 导入是浪费
        Yii::import('my.annotation.Desc');

        $actions = YiiUtil::getActionsOfController($controller);
        $actionCount = count($actions);
        //$colors = array('#dd9988', '#eeccff');
        $random_color1 = ' #62e00 '; // '#' . base_convert(rand(0, 16777215), 10, 16);
        $random_color2 = ' #60800'; //'#' . base_convert(rand(0, 16777215), 10, 16);
        $colors = array($random_color1, $random_color2);
        WebUtil::printCharsetMeta();
        ob_start();
        ?>
    <p>
    <h2>
        controller descriptions：
        <?php
        $rc = new ReflectionAnnotatedClass($controller);
        $descAnnotation = $rc->getAnnotation('Desc'); //如果没有就返回false
        if ($descAnnotation !== false) {
            echo $descAnnotation->value;
        } else {
            echo 'this controller have no description now!';
        }
        ?>
    </h2>
    <h3>
        you could use the following link to access the action
    </h3>
    </p>
    <table width="80%">
        <thead>
        <tr style="background-color: #009933; ">
            <th>
                action id :
            </th>
            <th>
                action access route
            </th>
            <th>
                action description
            </th>
        </tr>
        </thead>
        <tbody style="text-align: center;">
            <?php for ($i = 0; $i < $actionCount; $i++): ?>

            <?php
            /**
             * 通过controller的 actions()方法返回的动作无法用反射处理的
             */
            if (method_exists($controller, 'action' . $actions[$i])) {
                //用annotation解析 action的描述
                $ram = new ReflectionAnnotatedMethod(get_class($controller), 'action' . $actions[$i]);
                /*
                if($ram->hasAnnotation('ActionDesc')){
                    $ram->getAnnotation('ActionDesc');
                  }
                */

                if (($decsAnnotation = $ram->getAnnotation('Desc')) !== false) {
                    $actionDesc = $decsAnnotation->value;
                } else {
                    $actionDesc = 'no action description now';
                }
            } elseif($action = $controller->createAction($actions[$i])){
                if(! ($action instanceof CInlineAction)){
                    //not inline action , we think it to be a object which extends the CAction
                    $ram = new ReflectionAnnotatedMethod($action, 'run');
                    if (($decsAnnotation = $ram->getAnnotation('Desc')) !== false) {
                        $actionDesc = $decsAnnotation->value;
                    } else {
                        $actionDesc = 'no action description now';
                    }
                }
            }else{
                $actionDesc = 'no action description now';
            }
            ?>

        <tr style="background-color: <?php echo $colors[$i % 2]; ?> ">
            <td><?php echo $actions[$i]; ?></td>
            <td> try to access：
                <?php
                if (YII_DEBUG) {
                    echo CHtml::link($actions[$i], $controller->createUrl($actions[$i]));
                } else {
                    echo "this link will be display only in debug mode ! ";
                } ?>
            </td>
            <td>
                <?php
                //echo $actionDesc;
                $markdown = new CMarkdown;
                $markdown->purifyOutput = true;
                echo $markdown->transform($actionDesc);
                ?>
            </td>
        </tr>
            <?php endfor
            ; ?>
        </tbody>
    </table>

    <?php
        /*
           $controller->layout = 'clear';
           $controller->render('//public/null');
        */
        $bufferText = ob_get_clean();

        $controller->renderText($bufferText);
        /*
        $scrollToText = $controller->widget('widgets.KScrollToWidget', array(
                'label' => 'to top',
                'speed' => 'slow',
                'linkOptions'=>array(
                    'class'=>'[radius, round] [secondary, alert, success] label'
                ),
                'cssSettings' => array(
                    'background-color' => '#78901f',
                    'width' => '200px'
                )
            ), true

        );
        $controller->renderText($bufferText . $scrollToText);
        */

    }
}
