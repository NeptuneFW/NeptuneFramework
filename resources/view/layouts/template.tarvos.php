
<!doctype html>
<!--
  Material Design Lite
  Copyright 2015 Google Inc. All rights reserved.

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

      https://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title> {{ $title_head }}</title>

    <!-- Add to homescreen for Chrome on Android
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="images/android-desktop.png">
     -->

    <!-- Add to homescreen for Safari on iOS
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Material Design Lite">
    <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">
      -->
    <!-- Tile icon for Win8 (144x144 + tile color)
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">
 -->
    <link rel="shortcut icon" href="images/favicon.png">

    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->

    {{ $css }}
    <style>
        #view-source {
            position: fixed;
            display: block;
            right: 0;
            bottom: 0;
            margin-right: 40px;
            margin-bottom: 40px;
            z-index: 900;
        }
    </style>
</head>
<body>
<div class="demo-blog mdl-layout mdl-js-layout has-drawer is-upgraded">
    <main class="mdl-layout__content">

        @partial header;

        <div class="demo-blog__posts mdl-grid">

            @yield(content)

            <nav class="demo-nav mdl-cell mdl-cell--12-col">
                <div class="section-spacer"></div>
                <a href="entry.html" class="demo-nav__button" title="show more">
                    More
                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
                        <i class="material-icons" role="presentation">arrow_forward</i>
                    </button>
                </a>
            </nav>
        </div>

        @partial footer;

    </main>
    <div class="mdl-layout__obfuscator"></div>
</div>
{{ $js }}
</body>
<script>
    Array.prototype.forEach.call(document.querySelectorAll('.mdl-card__media'), function(el) {
        var link = el.querySelector('a');
        if(!link) {
            return;
        }
        var target = link.getAttribute('href');
        if(!target) {
            return;
        }
        el.addEventListener('click', function() {
            location.href = target;
        });
    });
</script>
</html>
