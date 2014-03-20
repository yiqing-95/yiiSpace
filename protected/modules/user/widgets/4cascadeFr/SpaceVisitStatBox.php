<?php
/**
 *
 * User: yiqing
 * Date: 13-4-26
 * Time: 下午2:04
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

class SpaceVisitStatBox extends CWidget
{

    /**
     * @var int
     */
    public $spaceOwnerId = 0;
    /**
     * @var int
     * 区间大小 前多少天的 或者表述为多少天内
     */
    public $windowSize = 7 ;

    public function init()
    {
        $target = $this->spaceOwnerId;
        $endDate = date("Y-m-d",time()); //截止日是今天就是 今天前的 N天数据

        $stats = SpaceVisitHelper::getLatestVisitStatistic($target,$endDate,$this->windowSize);
        //  print_r($stats);
        //把日期转化为星期 的那个 几
        $rowCount = count($stats);

        $stats4days = array();
        for($i=0 ; $i< $rowCount; $i++){
            $w = (int)date("w ",strtotime($stats[$i]['day']));
            //转化为时间戳 然后取出今天是星期几-英文格式的
            $newStatItem = $stats[$i];
            $newStatItem['w'] = '星期'.$w ;
            $stats4days[$w] = $newStatItem;
        }
        $latestNDayStats = array();
        $dayNames = SpaceVisitHelper::getLatestNDayNames();
        foreach($dayNames as $dayName){
            if(isset($stats4days[$dayName])){
                $latestNDayStats[] = $stats4days[$dayName];
            }else{
                $latestNDayStats[] = array(
                    'day'=>'',
                    'w'=>'星期'. $dayName,
                    'times'=>0
                );
            }
        }
        $latestNDayStats = array_reverse($latestNDayStats);
        // print_r($latestNDayStats);
        // print_r(SpaceVisitHelper::getLatestNDayNames());
       $this->render('_spaceVisitStat',array('data'=>$latestNDayStats));
        /*
        //总访问量：
        $totalClick ;
        $pdo = DBUtil::getPdo();
        $rsltList = AccessStatDao::getLatestVisitStatistic($pdo, $type, $target, $endDate, $days);
        //最大访问量
        $maxQuantity = AccessStatDao::getMax4LatestVisit($pdo, $type, $target, $endDate, $days);
        if(strtolower($type) =='space'){
            require_once  'lib/logic/UserService.php';
            $userInfor= UserService::getUserInfor($pdo, $target);
            $totalClick = $userInfor['clickAmount'];
        }

        $pdo = null;
        //把日期转化为星期 的那个 几
        $rowCount = count($rsltList);
        for($i=0 ; $i< $rowCount; $i++){
            //转化为时间戳 然后取出今天是星期几-英文格式的
            $rsltList[$i]['whichDay'] =(int)date("w ",strtotime($rsltList[$i]['whichDay']));
        }
        // print_r($rsltList);
//  //组装最后要发送给客户端的数据：
//  $rsltArr = array(
//      'total'=> $totalClick,
//      'max' => $maxQuantity,
//      'latestStat' => $rsltList
//  );
        //print_r($rsltArr);
        //echo json_encode($rsltArr);

        //获取最近7天的星期名 西文
        $latestNDayNames = json_encode(getLatestNDayNames(7));
        $weekArr=array( "天 ", "一 ", "二 ", "三 ", "四 ", "五 ", "六 ");
        $smarty->assign('weekArr',$weekArr);
        $smarty->assign("latestNDayNames",$latestNDayNames);
        $smarty->assign("total",$totalClick);
        $smarty->assign("max",$maxQuantity);
        $smarty->assign("latestStat",$rsltList);
        $smarty->display("AccessStat/spaceAccessStat.html");

        SpaceVisitHelper::getLatestVisitStatistic()
        */
    }


}
