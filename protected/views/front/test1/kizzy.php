<?php
/**
 *  
 * User: yiqing
 * Date: 13-7-11
 * Time: 下午9:16
 * To change this template use File | Settings | File Templates.
 * -------------------------------------------------------
 * -------------------------------------------------------
 */

$this->widget('my.widgets.kizzy.Kizzy');
?>
<script type="text/javascript">
    var cache = kizzy('users')
    var agent = cache.get('Agent')
    if (agent) {
        alert('Welcome back ' + agent.name)
    } else {
        cache.set('Agent', {
            name: 'Agent Diaz'
        })
    }

    var cache = kizzy('users')

    var agent = cache.get('Agent') || cache.set('Agent', {
        name: 'Agent Diaz'
    }, 5000) // time to live set for 5 seconds


    // wait 3 seconds...
    setTimeout(function() {
        alert('Still there ' + cache.get('Agent').name)
    }, 3000)

    // 6 seconds later...
    setTimeout(function() {
        cache.get('Agent').name // => expired
    }, 6000)
</script>