<!doctype html>
<html lang="en">

<?php include_once "partials/head.php"; ?>

<body>

    <?php
    include_once "partials/nav.php";
    ?>

    <div class="header-video container-fluid notFound">
        <div class="row">
            <div class="header-content col-10 col-md-4 mx-auto">
                <h1 class="display-1 text-white">404</h1>
                <p>We couldn't find what you were looking for.</p> <h5><a class="display-5" href="/?ref=404">Take me home!</a></h5>
            </div>
            <video autoplay="" loop="" muted="">
                <source src="/public/media/video/rct2-loop.mp4" type="video/mp4">
                <source src="/public/media/video/rct2-loop.webm" type="video/webm">
            </video>
        </div>
    </div>



    <?php
    include_once "partials/footer.php";
    include_once "partials/bottom.php";
    ?>

</html>