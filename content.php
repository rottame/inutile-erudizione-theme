<?php
$pillole_cat = 159;
$cats = array_map(function($c){return $c->term_id;}, get_the_category());
if($cats[0] == $pillole_cat) {
  include 'content_pillole.php';
} else {
  include 'content_main.php';
}
