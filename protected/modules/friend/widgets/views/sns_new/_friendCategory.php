

<?php YsPageBox::beginPanelWithHeader('好友分类') ?>
<div class="cell" id="<?php echo $this->getId(); ?>">
    <span class="icon spin icon-64 icon-spinner"></span>
    加载中....

    <script type="text/javascript">
        $(function(){
            setTimeout(function(){
               var url = '<?php echo $this->ajaxUrl; ?>';
               var params = {
               };
              $("#<?PHP echo $this->getId() ?>").load(url);
           },50) ;
        });
    </script>

</div>
<?php YsPageBox::endPanel()?>