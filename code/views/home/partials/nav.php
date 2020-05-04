<nav class="navbar navbar-expand-md navbar-dark mb-4">
    <a class="navbar-brand" href="/"><img class="img-fluid logo" src="/public/media/img/logo/orct2p-logo-h-m-dark.svg" /> </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/list/new">New</a>
            </li>
            <li class="nav-item" data-toggle="tooltip" data-placement="bottom" title="Coming soon!">
                <a class="nav-link disabled" href="/list/rating">Rating</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/submit">Submit</a>
            </li>
        </ul>
        <form class="form-inline mt-2 mt-md-0">
            <div class="input-group">
                <input class="form-control input-group-prepend" type="text" placeholder="Search" aria-label="Search">
                <button class="btn btn-secondary input-group-append" type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <div class="nav-user ml-md-2 mt-2 mt-md-0">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="submitButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-upload"></i> Submit Plugin
                </button>
                <div class="dropdown-menu dropdown-menu-right submit-dropdown" aria-labelledby="submitButton">
                    <form action="/" method="post" class="needs-validation" novalidate>
                        <label for="githubUrl" class="w-100"><i class="fab fa-github"></i> GitHub URL</label>
                        <div class="input-group">
                            <input type="url" class="form-control input-group-prepend" id="githubUrl" name="githubUrl" placeholder="Ex: https://github.com/OpenRCT2/plugin-samples">
                            <button type="submit" class="btn btn-primary input-group-append"><i class="fas fa-check"></i></button>
                            <div class="invalid-feedback">
                                URL seems invalid.
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</nav>