<?php

// Example for dynamic image
// See www.mywebmymail.com for more details

echo "<p>This is a normal html page, with the dynamically created thumbnail</p>";

$file='image.jpg';
echo "<img src=\"thumbnail.php?thumb=$file\" alt=\"thumb\" />";

?>