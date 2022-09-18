<?php flash('post_message') ?>
<div class="row mb-3">
    <div class="col-md-6">
        <h1>Posts</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo BASE_URL; ?>/posts/add" class="btn btn-primary pull-right">
            <i class="fa fa-pencil"></i> Add Post
        </a>
    </div>
</div>
<?php foreach ($data['posts'] as $post) : ?>
    <div class="card card-body mb-3">
        <h4 class="card-title"><?= $post->title ?></h4>
        <p class="card-text"><?php echo $post->body; ?></p>
        <p><a href="<?php echo BASE_URL; ?>/posts/show/<?php echo $post->postId; ?>" class="btn btn-dark">More</a></p>

        <div class="bg-light p-2 mb-3">
            Written by <?= $post->name . ' on ' . $post->post_created_at ?>
        </div>
    </div>
<?php endforeach; ?>