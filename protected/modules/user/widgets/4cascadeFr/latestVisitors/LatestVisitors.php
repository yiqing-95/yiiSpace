<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yiqing
 * Date: 12-5-5
 * Time: 上午1:07
 *------------------------------------------------------------
 *                 _            _
 *                (_)          (_)
 *        _   __  __   .--. _  __   _ .--.   .--./)
 *       [ \ [  ][  |/ /'`\' ][  | [ `.-. | / /'`\;
 *        \ '/ /  | || \__/ |  | |  | | | | \ \._//
 *      [\_:  /  [___]\__.; | [___][___||__].',__`
 *       \__.'            |__]             ( ( __))
 *
 *--------------------------------------------------------------
 * @see http://stackoverflow.com/questions/2050955/latest-visitors
 *       SELECT  visitorid
        FROM    uservisitors ui
        WHERE   userid = ?
        NOT EXISTS
        (
        SELECT  NULL
        FROM    uservisitors uo
        WHERE   uo.userid = ui.userid
        AND uo.visitorid = ui.visitorid
        AND uo.time > ui.time
        )
        ORDER BY
        time DESC
        LIMIT 5
 *
 *
 */
class LatestVisitors extends CWidget
{
    /**
     * @var int
     * 空间Id  homeId uid
     */
    public $spaceId = 1;

    /**
     * @var int
     * 最多显示多杀
     */
    public $maxCount = 5 ;

    public function init(){
        parent::init();
    }

    /**
     * @see
     * http://explainextended.com/2010/01/12/latest-visitors/#
     */
    public function run(){
        $selectSql = "
        SELECT  u.*
                FROM    user_space_visitor usvo
                LEFT JOIN
                        user_space_visitor usvi
                ON      usvi.space_id = usvo.space_id
                        AND (usvi.vtime) > (usvo.vtime)
                        AND usvi.visitor_id = usvo.visitor_id

                INNER JOIN user u
                        on u.id = usvo.visitor_id

                WHERE   usvo.space_id = :spaceId
                        AND usvi.space_id IS NULL
                ORDER BY
                        usvo.space_id DESC, usvo.vtime DESC
                LIMIT :maxCount
        ";
        $users = User::model()->findAllBySql($selectSql,array(
           ':spaceId'=>$this->spaceId,
            ':maxCount'=>$this->maxCount
        ));
        //print_r($users);
        $this->render('userThumbs',array('users'=>$users));
    }

}
