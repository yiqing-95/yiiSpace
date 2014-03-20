<div class="col">
    <div class="cell">
        <div class="col width-11of16">
            <?php
            $this->widget('photo.widgets.pageblock.PhotoCommendBlock');
            ?>
        </div>
        <div class="col width-fill">
          <?php     $this->widget('user.widgets.pageblock.MemberBlock'); ?>
        </div>
    </div>
</div>

<div class="col border-bottom">
    <div class="cell">
        <div class="col width-11of16">
            <?php     $this->widget('photo.widgets.pagebox.photoPageBox'); ?>
        </div>
        <div class="width-fill">
            <?php     $this->widget('news.widgets.pageblock.NewsPageBox'); ?>
        </div>
    </div>
</div>

<div class="col">
    <div class="cell">
        <div class="col width-11of16">
            <?php     $this->widget('user.widgets.4cascadeFr.UserOnlineBox'); ?>
        </div>
        <div class="">
            <?php     $this->widget('news.widgets.pageblock.NewsPageBox'); ?>
        </div>
    </div>
</div>