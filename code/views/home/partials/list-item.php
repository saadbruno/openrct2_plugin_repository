<div class="row list-item">

    <div class="col content">
        <div class="row h-100">
            <div class="col-12">
                <a href="/plugin/<?= $plugin['id'] ?>/<?= urlencode($plugin['name']) ?>">
                    <h4><?= $plugin['name'] ?></h4>
                </a>
                <p class="description"><?= $plugin['description'] ?></p>

            </div>
            <div class="footer col-12 align-self-end">
                <span>
                    <a href="/user/<?= $plugin['owner'] ?>/<?= urlencode($plugin['username']) ?>">
                        <?php if (!empty($plugin['avatarUrl'])) { ?>
                            <img src="<?= $plugin['avatarUrl'] ?>&s=20" class="avatar" />
                        <?php } else { ?>
                            <i class="fas fa-user"></i>
                        <?php } ?>
                        <?= $plugin['username'] ?>
                    </a>
                </span>
                <span data-toggle="tooltip" data-placement="bottom" title="Stars on GitHub"><i class="fas fa-star"></i> <?= $plugin['stargazers'] ?></span>
                <span data-toggle="tooltip" data-placement="bottom" title="Submitted: <?= $plugin['submittedAtRel'] ?>"><i class="fas fa-cloud-upload-alt"></i> <?= $plugin['submittedAtRelShort'] ?></span>
                <span data-toggle="tooltip" data-placement="bottom" title="Last updated: <?= $plugin['updatedAtRel'] ?>"><i class="fas fa-redo"></i> <?= $plugin['updatedAtRelShort'] ?></span>

                <?php if ($plugin['licenseName']) { ?>
                    <span data-toggle="tooltip" data-placement="bottom" title="License">
                        <i class="fas fa-balance-scale"></i>
                        <?= $plugin['licenseName'] ?>
                    </span>
                <?php
                }
                if ($plugin['tags']) {
                ?>
                    <span class="tags">
                        <ul>
                            <?php
                            foreach ($plugin['tags'] as $tag) {
                            ?>
                                <li><a href="/list/?search=<?= urlencode($tag['tag']) ?>"><?= $tag['tag'] ?></a></li>
                            <?php
                            }
                            ?>
                        </ul>
                    </span>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php if ($plugin['usesCustomOpenGraphImage'] == 1) { ?>
        <div class="col-4 col-md-2 thumbnail">
            <a href="/plugin/<?= $plugin['id'] ?>/<?= urlencode($plugin['name']) ?>">
                <img class="img-fluid" src="<?= $plugin['thumbnail'] ?>" />
            </a>
        </div>
    <?php } ?>
</div>