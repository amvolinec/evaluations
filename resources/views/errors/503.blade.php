<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Evaluations</title>
</head>
<style>
    .outer {
        position: absolute;
        text-align: center;
        height: 100%;
        width: 100%;
    }

    .inner {
        position: absolute;
        top: calc(50% - 150px);
        left: calc(50% - 150px);
    }
</style>
<body>
<div class="outer">
    <div class="inner">
        <img style="height: 300px; width: auto;" src="{{ asset('/images/settings.svg') }}" alt="Maintenance mode">
    </div>
</div>
</body>
</html>
