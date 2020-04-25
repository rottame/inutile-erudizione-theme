<?php

function filter_blog_name($output) {
  $out = explode(' ', $output);
  $last = array_pop($out);
  $out = join(' ', $out);
  echo "${out}<span>${last}</span>";
}

function filter_description($desc){
  echo preg_replace('/(Porn)$/', '<span>\1</span>', $desc);
}

function filter_category($desc){
  return preg_replace('/(buffe|erudizione|bermuda|proci|di carta stampata)$/i', '<span>\1</span>', $desc);
}

function pill_number($title) {
  if(preg_match('/[0-9]/', $title)) {
    return preg_replace('/^.*[^0-9](\d+)$/', '\1', $title);
  } else {
    return '';
  }
}

function palate_excerpt($text, $raw_excerpt) {
    if( ! $raw_excerpt ) {
        $content = apply_filters( 'the_content', get_the_content() );
        $text = substr( $content, 0, strpos( $content, '</p>' ) + 4 );
    }
    $text = preg_replace("/<img[^>]+\>/i", "", $text);

    return $text;
}
#add_filter( 'wp_trim_excerpt', 'palate_excerpt', 10, 2 );

function palate_excerpt_length($length) {
	return 40;
}
add_filter('excerpt_length', 'palate_excerpt_length', 100);

function palate_continue_reading() {
	return '&hellip;';
}
add_filter('excerpt_more', 'palate_continue_reading', 100);

function closetags($html) {
  preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
  $openedtags = $result[1];
  $openedtags = array_filter($openedtags, function($t){ return $t != 'br';});
  preg_match_all('#</([a-z]+)>#iU', $html, $result);
  $closedtags = $result[1];
  $len_opened = count($openedtags);
  if (count($closedtags) == $len_opened) {
    return $html;
  }
  $openedtags = array_reverse($openedtags);
  for ($i=0; $i < $len_opened; $i++) {
    if (!in_array($openedtags[$i], $closedtags)) {
      $html .= '</'.$openedtags[$i].'>';
    } else {
      unset($closedtags[array_search($openedtags[$i], $closedtags)]);
    }
  }
  return $html;
}

function presentation_excerpt() {
  wordcount_excerpt(150);
}

function pill_excerpt($wordcount = 100) {
  wordcount_excerpt(100);
}

function wordcount_excerpt($wordcount = 100) {
  $content = apply_filters( 'the_content', get_the_content() );
  // remove <img>
  $content = preg_replace("/<img[^>]+\>/i", "", $content);
  $content = preg_replace("/<figure[^>]+\>.*?<\/figure>/i", "", $content);
  // false spaces <br /> -> <br>
  $content = preg_replace("/<br \/>/i", "<br>", $content);
  $content = preg_replace("/\s+/", " ", $content);
  $broken = preg_split("/(\<\/p\>|\<br\>)/", $content, NULL, PREG_SPLIT_DELIM_CAPTURE);

  // excerpt words count
  // $wordcount = 100;

  $nwords = 0;
  $pos = 0;
  $excerpt;
  while(true) {
    $txt = $broken[$pos++];
    $tag = $broken[$pos++];
    $cnt = str_word_count($txt);

    $delta = abs($wordcount - $nwords);
    $ndelta = abs($wordcount - ($nwords+$cnt));

    // excerp + next paragraph word count is the nearest to $wordcount
    if($delta > $ndelta) {
      $excerpt .= " {$txt}{$tag}";
      $nwords += $cnt;
    } else {
      // whe have found the words count nearest nearest to $wordcount. bye
      break;
    }
  }

  echo $excerpt;
}
