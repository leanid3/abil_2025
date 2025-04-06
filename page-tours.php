<?php get_header(); ?>

<div class="row">
    <div class="col-md-9">
        <?php echo do_shortcode('[tours_filter]'); ?>
        
        <div class="row">
            <?php while(have_posts()) : the_post(); ?>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
                        <div class="card-body">
                            <h5><?php the_title(); ?></h5>
                            <?php the_content(); ?>
                            [spoiler]<?php the_field('details'); ?>[/spoiler]
                            <button class="btn btn-primary">Выбрать</button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    
    <div class="col-md-3">
        <?php dynamic_sidebar('sidebar'); ?>
    </div>
</div>

<?php get_footer(); ?>