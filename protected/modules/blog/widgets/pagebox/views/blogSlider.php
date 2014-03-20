<h3>系统推荐</h3>
<ul>
    <?php foreach ($posts as $post) : ?>
        <li>

           <a href="<?php echo BlogHelper::createBlogUrl($post);?>"><?php echo $post->title; ?></a>
        </li>

    <?php endforeach; ?>
</ul>