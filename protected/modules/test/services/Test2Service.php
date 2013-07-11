<?php
/**
 * User: yiqing
 * Date: 13-1-21
 * Time: 上午10:27
 * change template => | Settings | File Templates.
 * ------------------------------------------------
 * ------------------------------------------------
 */
class Test2Service implements ITestService
{

    /**
     * @param string $param
     * @return string
     */
    public function helloTo($param = '')
    {
        throw new CException('yyy');
      // return 'yes '.$param;
    }
}
