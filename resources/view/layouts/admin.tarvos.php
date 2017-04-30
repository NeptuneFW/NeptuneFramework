<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="assets/img/favicon.ico">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <title> { $title }</title>

    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />

    {{ $css }}

    <!--     Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>

</head>
<body>

<div class="wrapper">

    @partial admin/sidebar;

    <div class="main-panel">

        @partial admin/navbar;

        <div class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-warning" data-notify="container">
                            <span data-notify=""> { \Libs\Languages::temporarilySet(['tr_TR' => 'Neden GitHub da bizi ziyaret etmiyorsun?', 'en_US' => 'Why do not you visit us at GitHub?']) } &nbsp; <a style="color: #aa5533;" href="https://github.com/NeptuneFW">Neptune Framework</a></span>
                        </div>
                    </div>
                </div>

                @yield(content)

            </div>
        </div>


        @partial admin/footer;

    </div>
</div>


</body>

{{ $js }}
<!--  Google Maps Plugin    -->
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>


<script type="text/javascript">
    $(document).ready(function(){

        demo.initChartist();

        $.notify({
            icon: 'pe-7s-gift',
            message: "Welcome to <b>Light Bootstrap Dashboard</b> - a beautiful freebie for every web developer."

        },{
            type: 'info',
            timer: 4000
        });

    });
</script>

</html>
