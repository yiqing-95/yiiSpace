<?php
class AccordianWidget extends CWidget
{
	public $title;
	public $data=array();
	public $labels=array();
	public $class="";
	public function run()
	{
		
		?>
        	<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
            <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
            <script>
				  $(document).ready(function() {
					$("#accordion").accordion();
				  });
	       </script>
           <div id="accordion">
			   <?php
			   		foreach($this->data as $key=>$val)
					{
						?>
                        	<h3><a href="#"><?=$key?></a></h3>
                                <div>
                                    <ul class="<?=$this->class?>">
                                    	<?php
											foreach($val as $v=>$k)
											{
												?>
                                                	<li><? echo CHtml::link($v,array($k))?></li>
                                                <?
											}
											?>
                                    </ul>
                                </div>
                        <?	
					}
               ?>
                    
                    <!--<h3><a href="#">Section 2</a></h3>
                    <div>
                        <p>
                        Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet
                        purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor
                        velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In
                        suscipit faucibus urna.
                        </p>
                    </div>
                    <h3><a href="#">Section 3</a></h3>
                    <div>
                        <p>
                        Nam enim risus, molestie et, porta ac, aliquam ac, risus. Quisque lobortis.
                        Phasellus pellentesque purus in massa. Aenean in pede. Phasellus ac libero
                        ac tellus pellentesque semper. Sed ac felis. Sed commodo, magna quis
                        lacinia ornare, quam ante aliquam nisi, eu iaculis leo purus venenatis dui.
                        </p>
                        <ul>
                            <li>List item one</li>
                            <li>List item two</li>
                            <li>List item three</li>
                        </ul>
                    </div>
                    <h3><a href="#">Section 4</a></h3>
                    <div>
                        <p>
                        Cras dictum. Pellentesque habitant morbi tristique senectus et netus
                        et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in
                        faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia
                        mauris vel est.
                        </p>
                        <p>
                        Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus.
                        Class aptent taciti sociosqu ad litora torquent per conubia nostra, per
                        inceptos himenaeos.
                        </p>
                    </div>-->
                </div>

        <?
		
	}
}
?>