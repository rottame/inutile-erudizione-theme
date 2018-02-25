#!/bin/bash

rm style.css
compass.ruby2.4 compile -e production -c config.rb

mv style.css _style_css

cat scss/_style_css_header _style_css > style.css
