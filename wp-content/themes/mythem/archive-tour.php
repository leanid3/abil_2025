<?php get_header(); ?>

<div class="tour-archive">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <!-- Фильтры -->
                <div class="filters card">
                    <div class="card-header">
                        <h3>Фильтры</h3>
                    </div>
                    <div class="card-body">
                        <form id="tourFilters">
                            <!-- По группам -->
                            <div class="mb-3">
                                <label class="form-label">Группы</label>
                                <?php 
                                $groups = get_terms('tour_group');
                                foreach($groups as $group): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="group-<?= $group->term_id ?>" 
                                               value="<?= $group->term_id ?>">
                                        <label class="form-check-label" for="group-<?= $group->term_id ?>">
                                            <?= $group->name ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- По датам -->
                            <div class="mb-3">
                                <label class="form-label">Дата</label>
                                <input type="date" class="form-control" id="tourDate">
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Применить</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9">
                <div class="sorting mb-4">
                    <select class="form-select" id="tourSort">
                        <option value="date-desc">Сначала новые</option>
                        <option value="date-asc">Сначала старые</option>
                        <option value="duration">По продолжительности</option>
                    </select>
                </div>
                
                <div class="tour-list row" id="tourResults">
                    <?php while(have_posts()) : the_post(); 
                        $date = get_field('date');
                        $time = get_field('time');
                    ?>
                        <div class="col-md-6 mb-4">
                            <div class="tour-card card h-100">
                                <div class="card-body">
                                    <div class="tour-meta">
                                        <span class="date"><?= date('d.m.Y', strtotime($date)) ?></span>
                                        <span class="time"><?= $time ?></span>
                                        <span class="duration"><?= get_field('duration') ?> ч.</span>
                                    </div>
                                    
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    
                                    <div class="tour-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    
                                    <div class="tour-actions">
                                        <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-outline-primary">Подробнее</a>
                                        <button class="btn btn-sm btn-primary book-btn" 
                                                data-tour-id="<?= get_the_ID() ?>">
                                            Записаться
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <?php the_posts_pagination(); ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>