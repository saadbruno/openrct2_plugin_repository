<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="<?= $meta['title'] ?>" content="<?= $meta['title'] ?>">
    <meta name="description" content="<?= $meta['description'] ?>">
    <title><?= $meta['title'] ?></title>
    <link rel="canonical" href="<?= $meta['url'] ?>" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= $meta['url'] ?>">
    <meta property="og:title" content="<?= $meta['title'] ?>">
    <meta property="og:description" content="<?= $meta['description'] ?>">
    <meta property="og:image" content="<?= $meta['image'] ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?= $meta['url'] ?>">
    <meta property="twitter:title" content="<?= $meta['title'] ?>">
    <meta property="twitter:description" content="<?= $meta['description'] ?>">
    <meta property="twitter:image" content="<?= $meta['image'] ?>">

    <!-- favicons -->
    <link rel="icon" type="image/svg+xml" href="/public/media/img/logo/orct2p-logo-i-c.svg?v=<?= $config['version'] ?>">
    <meta name="theme-color" content="#2993cf">

    <!-- Vendor CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" integrity="sha256-h20CPZ0QyXlBuAw7A+KluUYx/3pK+c7lYEpqLTlxjYQ=" crossorigin="anonymous" />

    <!-- Custom CSS  -->
    <link href="/public/styles/orct2p.css?v=<?= $config['version'] ?>" rel="stylesheet">

</head>