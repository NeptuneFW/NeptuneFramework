<div class="sidebar" data-color="purple" data-image="assets/img/sidebar-5.jpg">

    <!--

        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->

    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="http://www.neptunefw.org" target="_blank" class="simple-text">
                Neptune Framework
            </a>
        </div>

        <ul class="nav">

            @if PATH == '/admin/' OR PATH == '/admin';
            <li class="active">
            @else
            <li>
            @endif
                <a href=" { $route->route('admin')->getRoute() }">
                    <p> { \Libs\Languages::show("Dashboard") } </p>
                </a>
            </li>
            @if preg_match('/\/category/', PATH);
            <li class="active">
            @else
            <li>
            @endif
                <a href=" { $route->route('category')->getRoute() }">
                    <p> { \Libs\Languages::show('Category') }</p>
                </a>
            </li>
            @if preg_match('/\/article/', PATH);
            <li class="active">
                @else
            <li>
                @endif
                <a href=" { $route->route('article')->getRoute() }">
                    <p> { \Libs\Languages::show('Article') }</p>
                </a>
            </li>
            <li>
                <a href="table.html">
                    <i class="pe-7s-note2"></i>
                    <p>Table List</p>
                </a>
            </li>
            <li>
                <a href="typography.html">
                    <i class="pe-7s-news-paper"></i>
                    <p>Typography</p>
                </a>
            </li>
            <li>
                <a href="icons.html">
                    <i class="pe-7s-science"></i>
                    <p>Icons</p>
                </a>
            </li>
            <li>
                <a href="maps.html">
                    <i class="pe-7s-map-marker"></i>
                    <p>Maps</p>
                </a>
            </li>
            <li>
                <a href="notifications.html">
                    <i class="pe-7s-bell"></i>
                    <p>Notifications</p>
                </a>
            </li>
            <li class="active-pro">
                <a href="upgrade.html">
                    <i class="pe-7s-rocket"></i>
                    <p>Upgrade to PRO</p>
                </a>
            </li>
        </ul>
    </div>
</div>