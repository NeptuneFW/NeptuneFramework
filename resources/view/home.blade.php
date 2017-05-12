<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Neptune Framework</title>

  <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&amp;subset=latin-ext" rel="stylesheet">
  <style media="screen">
    * {
      margin: 0;
      padding: 0;
      list-style: none;
      text-decoration: none;
  }
  body {
      background-color: #FFFFFF;
      overflow: hidden;

  }
  .general {
      position: absolute;
      width: 101%;
      height: 100%;
      margin-left:0%;
      overflow-y: scroll;

  }
  .general .neptune {
    font-family: 'Roboto', sans-serif;
    color: #999999;
    margin-top:15vh;
    font-size: 65px;
    font-weight: lighter;
    text-align: center;
  }
  .general .framework {
    font-family: 'Roboto', sans-serif;
    color: #999999;
    font-size: 150px;
    font-weight: lighter;
    text-align: center;
  }
  .general .version {
    font-family: 'Roboto', sans-serif;
    color: #999999;
    margin-top:5px;
    font-size: 75px;
    font-weight: lighter;
    text-align: center;
  }
  .general .copyright {
    font-family: 'Roboto', sans-serif;
    color: #999999;
    margin-top:35px;
    font-size: 45px;
    font-weight: lighter;
    text-align: center;
  }
    .general .slogan {
      font-family: 'Roboto', sans-serif;
      color: #999999;
      margin-top:35px;
      font-size: 35px;
      font-weight: lighter;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="general">
    <div class="neptune">
      {{ \Libs\Languages\Languages::temporarilySet(['en_US' => 'Hello Astronaut! Are you ready to fly to Neptune?', 'tr_TR' => "Merhaba Astronot! Neptune'e uçmaya hazır mısınız?"]) }}
    </div>
    <div class="framework">
      Neptune Framework
    </div>
    <div class="version">
      Beta V1
    </div>
    <div class="copyright">
      &copy; 2016-2017 Emirhan Engin, Mehmet Ali Peker
    </div>
    <div class="slogan">
      {{ \Libs\Languages\Languages::temporarilySet(['en_US' => 'The Neptune Framework is an approach that comes from the depths of space!', 'tr_TR' => "Neptune Framework uzayın derinliklerinden gelen bir yaklaşım!"]) }}
    </div>
  </div>
</body>
</html>
