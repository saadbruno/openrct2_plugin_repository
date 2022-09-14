<div class="col-12 col-md-3 sidebar">
    <div class="row">
        <?php if ($plugin['usesCustomOpenGraphImage'] == 1) { ?>
            <div class="col-12 thumbnail mb-4">
                <img class="img-fluid" src="<?= $plugin['thumbnail'] ?>" />
            </div>
        <?php } ?>

        <div class="col-12">

            <a class="download-plugin text-white btn btn-primary" href="<?= $plugin['url'] ?>/releases/latest" target="_blank" role="button"><i class="fab fa-github"></i> Get plugin!</a>

            <h5>Author:</h5>
            <a class="author" href="/user/<?= $plugin['owner'] ?>/<?= urlencode($plugin['username']) ?>">
                <?php if (!empty($plugin['avatarUrl'])) { ?>
                    <img src="<?= $plugin['avatarUrl'] ?>&s=60" class="avatar" />
                <?php } else { ?>
                    <i class="fas fa-user"></i>
                <?php } ?>
                <?= $plugin['username'] ?>
            </a>

            <h6>Submitted:</h6>
            <i class="fas fa-cloud-upload-alt"></i> <?= $plugin['submittedAtRel'] ?>

            <h6>Updated:</h6>
            <i class="fas fa-redo"></i> <?= $plugin['updatedAtRel'] ?>

            <h6>Stars:</h6>
            <i class="fas fa-star"></i> <?= $plugin['stargazers'] ?>

            <h6>Tags:</h6>
            <span class="tags">
                <?php if ($plugin['tags']) { ?>
                    <ul>
                        <?php
                        foreach ($plugin['tags'] as $tag) {
                        ?>
                            <li><a href="/list/?search=<?= urlencode($tag['tag']) ?>"><?= $tag['tag'] ?></a></li>
                        <?php
                        }
                        ?>
                    </ul>
                <?php } else { ?>
                    <i class="no-description">No tags found</i>
                <?php } ?>
            </span>

            <h6>License:</h6>
            <i class="fas fa-balance-scale"></i>
            <?php if ($plugin['licenseName']) { ?>
                <a href="<?= $plugin['licenseUrl'] ?>" target="_blank" rel="noopener noreferrer"> <?= $plugin['licenseName'] ?> </a>
            <?php } else { ?>
                <i class="no-description">No license found</i>
            <?php } ?>

            <h6>Links:</h6>
            <span class="links">
                <a href="<?= $plugin['url'] ?>" target="_blank"><i class="fab fa-github"></i> GitHub Repository</a>
                <a href="<?= $plugin['ownerUrl'] ?>" target="_blank"><i class="fab fa-github"></i> <?= $plugin['username'] ?> on GitHub</a>
            </span>
        </div>

    </div>
</div>