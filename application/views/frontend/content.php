<?php if ($content->type !== '7' && !($content->type === '6' && $content->use_content_pic)) { // Not shoppable or photo  
    ?>
    <section class="single-header">
        <?php if ($page_no === 1) { ?>
            <div class="image-wrapper w-100">
                <img src="<?= "{$assets_url}/{$content->content_pic}" ?>" class="full-width">
                <?php if ($content->type === '1') { // Article
                    ?>
                    <ul class="post-categories detail-categories-wrapper">
                        <li>
                            <a href="<?= base_url(); ?>category/<?= $content->id_category; ?>/<?= strtolower(url_title($content->category_name)); ?>">
                                <?= $content->category_name ?>
                            </a>
                        </li>
                    </ul>
                <?php } ?>
            </div>
        <?php } ?>
    </section>
    <div class="pic-caption text-grey">
        <center>
            <p><?php echo $content->pic_caption ?></p>
        </center>
    </div>
<?php } ?>

<!-- Article Detail -->
<section class="content-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="blog-post california">
                    <div class="row">
                        <div class="col">
                            <div class="post-content detail-content">
                                <center>
                                    <h3 class="post-title"><?= $content->title ?></h3>

                                    <div class="row justify-content-center">
                                        <div class="col-md-6 col-xl-5">
                                            <?php if (isset($content->id_user) && $content->id_user > 0) { ?>
                                                <div class="author-info-box">
                                                    <figure class="mb-0">
                                                        <a href="<?php echo base_url(); ?>author/<?php echo $content->id_user; ?>/<?php echo strtolower(url_title($content->name)); ?>">
                                                            <img
                                                                src="<?= $this->frontend_lib->getUserPictureURL($content->picture, $content->picture_from); ?>"
                                                                alt="Image">
                                                        </a>
                                                    </figure>
                                                    <div class="content">
                                                        <a href="<?php echo base_url(); ?>author/<?php echo $content->id_user; ?>/<?php echo strtolower(url_title($content->name)); ?>">
                                                            <h5><?= $content->name; ?></h5>
                                                        </a>
                                                        <small><?php echo $content->profile_desc ?></small>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <hr class="mt-20 mb-20">

                                    <?php if ($content->type !== '2') { ?>
                                        <div class="row align-items-center mb-4">
                                            <div class="col-sm-7">
                                                <div class="sharethis-inline-share-buttons text-sm-left"></div>
                                            </div>
                                            <div class="col-sm-5 text-sm-right mt-3 mt-sm-0">
                                                <? if ($this->session->userdata('user_logged_in') !== true) { ?>
                                                <? $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())), "="); ?>
                                                <? if ($content->like_count > 0 && false) { ?>
                                                    <div class="like_counter_wrapper text-center mr-1">
                                                        <span
                                                            id="like_counter"><?= number_format(ceil($content->like_count), 0, ',', '.'); ?></span><br>
                                                        <span><?= ($content->like_count > 1 ? 'Likes' : 'Like'); ?></span><br>
                                                    </div>
                                                <? } ?>
                                                <?php if ($is_preview) : ?>
                                                <a class="btn btn-outline-dark" href="" onclick="return false;">
                                                    <?php else : ?>
                                                    <a class="btn btn-outline-dark"
                                                        href="<?= base_url(); ?>page/login/<?= $redirect_url; ?>">
                                                        <?php endif; ?>
                                                        <i class="fa fa-thumbs-up"></i> Like
                                                    </a>

                                                    <? } else { ?>

                                                        <!-- Like counter & text -->
                                                        <div
                                                            class="like_counter_wrapper text-center mr-1" <?= ($content->like_count > 0 ? '' : 'style="display:none;"'); ?>>
                                                            <span
                                                                id="like_counter"><?= number_format(ceil($content->like_count), 0, ',', '.'); ?></span><br>
                                                            <span><?= ($content->like_count > 1 ? 'Likes' : 'Like'); ?></span>
                                                        </div>

                                                        <a id="unlike-button" class="btn btn-outline-dark"
                                                            href="javascript:;" <?php echo(!$is_preview ? 'onclick="likeArticle();"' : '') ?>
                                                            style="<?= ($liked ? '' : 'display:none;'); ?>">
                                                            <i class="fa fa-thumbs-up"></i> Liked
                                                        </a>
                                                        <a id="like-button" class="btn btn-outline-dark"
                                                            href="javascript:;" <?php echo(!$is_preview ? 'onclick="likeArticle();"' : '') ?>
                                                            style="<?= (!$liked ? '' : 'display:none;'); ?>">
                                                            <i class="fa fa-thumbs-up"></i> Like
                                                        </a>

                                                    <? } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </center>

                                <?php if (!empty($content->content_pic) && $content->type === '6') { ?>
                                    <div class="shoppable-image-map pb-4">
                                        <img class="shoppable-image-image w-100"
                                            src="<?php echo base_url(); ?>assets/shoppable/<?php echo $content->use_content_pic ? $content->content_pic : $content->shoppable_picture ?>"
                                            title="<?php echo $content->content_pic ?>" draggable="false">
                                        <?php foreach ($items as $item) : ?>
                                            <div class="shoppable-image-pin" data-id="<?php echo $item->id ?>"
                                                style="top: <?php echo $item->top_percentage ?>%; left: <?php echo $item->left_percentage ?>%;">
                                                <div
                                                    class="shoppable-image-pin-icon"><?php echo $item->order_no ?></div>
                                                <div class="shoppable-image-pin-body">
                                                </div>
                                                <div class="card border-0">
                                                    <?php if ($item->picture) : ?>
                                                        <img class="card-img-top"
                                                            src="<?php echo base_url() ?>assets/shoppable/item/<?php echo $item->picture ?>"
                                                            alt="Shoppable Item Picture">
                                                    <?php endif; ?>
                                                    <div
                                                        class="card-body text-center <?php echo(!$item->picture ? 'pt-3' : '') ?>">
                                                        <h4 class="card-title"><?php echo $item->name ?></h4>
                                                        <span
                                                            class="card-subtitle">Rp. <?php echo number_format($item->price, 0, ',', '.') ?></span>
                                                        <a href="<?php echo((substr($item->url, 0, 7) !== "http://" && substr($item->url, 0, 8) !== "https://") ? "http://{$item->url}" : $item->url) ?>"
                                                            class="btn btn-default" target="_blank">Beli
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php } ?>

                                <!-- end metas -->
                                <?php $content_file_path = base_url() . "assets/content/"; ?>
                                <?php if ($page_no === 1) : ?>
                                    <?= str_replace("##BASE_URL##", $content_file_path, html_entity_decode($content->content)); ?>
                                <?php elseif ($page && $page->content) : ?>
                                    <?= str_replace("##BASE_URL##", $content_file_path, html_entity_decode($page->content)); ?>
                                <?php endif; ?>

                                <?php if ($content->type === '7') { ?>
                                    <?php $this->load->view('frontend/photo') ?>
                                <?php } ?>
                            </div>
                            <!-- end post-content -->
                        </div>
                    </div>

                    <div class="row w-100 pt-2">
                        <div class="col">
                            <ul class="post-categories <?php echo $content->type === '7' ? 'justify-content-center' : '' ?>">
                                <?php foreach ($tags as $tag) { ?>
                                    <li class="my-2">
                                        <?php $tag_slug = strtolower(url_title($tag->tag_name)) ?>
                                        <a href='<?php echo base_url("content/tag/{$tag->id_content_tag}/{$tag_slug}") ?>'
                                            class="font-weight-bold"
                                            style="font-size: 14px; padding: 2.5px 10px; height: auto;">
                                            <?php echo $tag->tag_name ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>

                    <?php if ($content->type !== '7') { ?>
                        <?php if (!(in_array($content->type, ['3', '4', '5', '7']) && $content->paginated === '1' && $page_no > 1)) { ?>
                            <hr class="mt-0 mb-40">
                        <?php } ?>
                    <?php } ?>

                </div>
                <!-- end blog-post -->

                <?php if (in_array($content->type, ['3', '4'])) : ?>
                    <?php $this->load->view('frontend/poll') ?>
                <?php elseif ($content->type === '5') : ?>
                    <?php $this->load->view('frontend/quiz') ?>
                <?php endif; ?>

                <!-- end author-info-box -->
                <?php if ($content->paginated === '1' && intval($max_page_no) > 1) : ?>
                    <?php $article_url = base_url() . $this->uri->segment(1) . "/{$content->id_content}" . '/' . strtolower(url_title($content->title)) ?>
                    <?php $hash_url = (in_array($content->type, ['3', '4']) ? '#poll' : ($content->type === '5' ? '#quiz' : '')) ?>
                    <nav class="clearfix row my-3 align-items-center">
                        <div class="col-md-4">
                            <?php if ($page_no > 1) : ?>
                                <?php $page_url = $page_no !== 2 ? '/' . ($page_no - 1) : '' ?>
                                <a href="<?php echo $article_url . $page_url . $hash_url ?>" class="btn btn-default">
                                    SEBELUMNYA
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4 text-center align-middle" style="line-height: 3;">
                            <span>Halaman <?php echo $page_no ?> dari <?php echo $max_page_no ?></span></div>
                        <div class="col-md-4 text-right">
                            <?php if ($page_no < $max_page_no) : ?>
                                <a href="<?php echo $article_url ?>/<?php echo ($page_no + 1) . $hash_url ?>"
                                    class="btn btn-default">
                                    SELANJUTNYA
                                </a>
                            <?php endif; ?>
                        </div>
                    </nav>
                <?php endif; ?>

                <!-- end post-navigation -->

            </div>
        </div>
    </div>
</section>
<!-- end content-section -->

<!-- Adv -->
<?
if ((isset($ads['sup_leaderboard_ar']['builtin'][0]['id_ads']) && $ads['sup_leaderboard_ar']['builtin'][0]['id_ads'] > 0) ||
    (isset($ads['sup_leaderboard_ar']['googleads'][0]['id_ads']) && $ads['sup_leaderboard_ar']['googleads'][0]['id_ads'] > 0)
) {
    ?>
    <div class="container m-t-20">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <center>
                    <div class="adv-home-super-leaderboard mt-4 m-b-0">
                        <? if (isset($ads['sup_leaderboard_ar']['builtin'][0]['id_ads']) && $ads['sup_leaderboard_ar']['builtin'][0]['id_ads'] > 0) { ?>
                            <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['sup_leaderboard_ar']['builtin'][0]['id_ads']; ?>"
                                target="_blank">
                                <img
                                    src="<?= base_url(); ?>assets/adv/<?= $ads['sup_leaderboard_ar']['builtin'][0]['ads_pic']; ?>"
                                    alt="Bisnis Muda" class="img img-fluid"/>
                            </a>
                        <? } else {
                            if (isset($ads['sup_leaderboard_ar']['googleads'][0]['id_ads']) && $ads['sup_leaderboard_ar']['googleads'][0]['id_ads'] > 0) { ?>
                                <?= html_entity_decode($ads['sup_leaderboard_ar']['googleads'][0]['googleads_code']); ?>
                            <? }
                            else {
                            }
                        } ?>
                    </div>
                </center>
            </div>
        </div>
    </div>
<? } ?>
<!-- end Adv -->

<div class="comments container pb-5">
    <div class="row justify-content-center mt-5 pt-5">
        <div class="col-lg-10">
            <h2 class="section-title">Komentar</h2>
            <div class="comments-list">
                <?php foreach ($comments as $comment) { ?>
                    <div class="blog-post kentucky">
                        <figure class="post-image align-self-start">
                            <a href="<?php echo base_url(); ?>author/<?php echo $comment->id_user; ?>/<?php echo strtolower(url_title($comment->name)); ?>">
                                <img
                                    src="<?php echo $this->frontend_lib->getUserPictureURL($comment->picture, $comment->picture_from) ?>"
                                    alt="<?php echo $comment->name ?>">
                            </a>
                        </figure>
                        <div class="post-content">
                            <div class="metas">
                                <h3 class="post-title mb-0"><?php echo $comment->name ?></h3>
                                <span
                                    class="date"><?php echo date('d M Y - H:i', strtotime($comment->comment_date)) ?></span>
                            </div>
                            <p><?php echo nl2br($comment->comment) ?></p>
                        </div>
                        <!-- end post-content -->
                    </div>
                    <!-- end blog-post -->
                <?php } ?>
            </div>

            <div class="text-center">
                <input type="hidden" class="id_content" value="<?= $content->id_content; ?>"/>
                <input type="hidden" class="comment_offset" value="<?= $this->per_page_comment; ?>"/>

                <div class="comment_loader"
                    style="background:rgba(255, 255, 255, 0.75);width:100%;height:100%;position:absolute;z-index:20;display:none;">
                    <center>
                        <img src="<?= base_url(); ?>files/backend/img/ajax-loader-big.gif" class="lazy"/><br/>
                        <p class="small hint-text text-black">Loading...</p>
                    </center>
                </div>

                <? if ($total_comments > $this->per_page_comment) { ?>
                    <button class="btn btn-sm btn-default mb-4 mt-4 load_comment_btn"
                        onclick="loadComments()">Lihat komentar sebelumnya
                    </button>
                <? } ?>
            </div>

            <hr/>

            <div class="add-comment blog-post kentucky">
                <? if ($this->session->userdata('user_logged_in') !== true) { ?>
                    <? $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string() . '#add-comment')), "="); ?>
                    <p class="text-center py-2 w-100" style="font-size: 24px;">
                        Silahkan
                        <a href="<?= base_url(); ?>page/login/<?= $redirect_url; ?>"><b><u>Login</u></b></a>
                        terlebih dahulu untuk meninggalkan komentar.
                    </p>
                <? } else { ?>
                    <div class="addcomment_wrapper w-100">
                        <div class="add_comment_loader"
                            style="background:rgba(255, 255, 255, 0.75);width:100%;height:100%;position:absolute;z-index:20;display:none;">
                            <center>
                                <img src="<?= base_url(); ?>files/backend/img/ajax-loader-big.gif" class="lazy"/><br/>
                                <p class="small hint-text text-black">Loading...</p>
                            </center>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="comment_message"></div>
                                <form>
                                    <div class="form-group">
                                        <textarea class="form-control input-msg required-field comment_content"
                                            placeholder="Komentar Anda" rows="5" required="yes"></textarea>
                                    </div>
                                </form>
                            </div><!-- Col end -->
                        </div><!-- Form row end -->

                        <?= $this->recaptcha->render(); ?>

                        <button class="mt-3 btn btn-default"
                            type="button" <?php echo(!$is_preview ? 'onclick="submitComment(event)"' : '') ?>>Submit komentar
                        </button>
                    </div>

                <? } ?>

            </div>
        </div>
    </div>
</div>


<input type="hidden" id="id_content" value="<?= $content->id_content; ?>"/>

<?php
if (in_array($content->type, ['3', '4'])) {
    $type = 'Polling';
}
elseif ($content->type == '5') {
    $type = 'Quiz';
}
elseif ($content->type == '6') {
    $type = 'Hypeshop';
}
elseif ($content->type == '7') {
    $type = 'Hypephoto';
}
else {
    $type = 'Artikel';
}
?>

<script>
    var type = '<?php echo $type ?>';

    function likeArticle() {
        var post_data = {};
        post_data['id_content'] = $('#id_content').val();

        $.ajax({
            'url': '<?= base_url(); ?>' + 'content2/likeContent',
            'type': 'POST', //the way you want to send data to your URL
            'data': getCSRFToken(post_data),
            'success': function (data) { //probably this request will return anything, it'll be put in var "data"
                //if the request success..
                var obj = JSON.parse(data); // parse data from json to object..

                //if status not success, show message..
                // if (obj.status == 'success') {
                //   Swal.fire('Terima kasih', 'telah menyukai ' + type + ' ini.', 'success');
                //   $('#like_counter').html(obj.like_count);
                //   $('.like_counter_wrapper').show();
                //   $('#like-button').html('<i class="fa fa-thumbs-up"></i> Liked').prop('onclick', null).off('click').addClass('disabled');
                // } else if (obj.status == 'nologin') {
                //   Swal.fire('Maaf', 'anda harus login terlebih dahulu untuk menyukai artikel ini.', 'error');
                // } else if (obj.status == 'already_liked') {
                //   Swal.fire('Terima kasih', 'anda sudah meyukai artikel ini.', 'warning');
                // } else {
                //   Swal.fire('Maaf', obj.message, 'error');
                // }

                //if status not success, show message..
                if (obj.status == 'success') {
                    $('#like_counter').html(obj.like_count);
                    $('.like_counter_wrapper').show();
                    Swal.fire('Terima kasih', obj.message, 'success');
                } else if (obj.status == 'nologin') {
                    Swal.fire('Maaf', 'anda harus login terlebih dahulu.', 'error');
                } else if (obj.status == 'already_liked') {
                    Swal.fire('Terima kasih', obj.message, 'warning');
                } else {
                    Swal.fire('Maaf', obj.message, 'error');
                }
                updateLikeButton(obj.action);
                refreshCSRFToken(obj.csrf_token_name, obj.csrf_token_hash);
            },
            'complete': function () {
            }
        });
    }

    function updateLikeButton(action) {
        if (action == 'unlike') {
            $('#like-button').show();
            $('#unlike-button').hide();
        } else {
            $('#like-button').hide();
            $('#unlike-button').show();
        }
    }

    <?php if (in_array($content->type, ['3', '4'])) : ?>
    $(document).ready(function () {
        var $form = $('#polls-form');

        $('.poll-answer input').on('change', function (ev) {
            <?php if (!$this->session->userdata('user_logged_in')) : ?>
            window.location.href = '<?php echo base_url() ?>page/login/<?php echo rtrim(base64_encode(urlencode($this->uri->uri_string())), "=") ?>'
            <?php elseif ($content->paginated === '1') : ?>
            $.ajax({
                url: '<?php echo base_url() ?>/content2/add_vote_session/<?php echo $content->id_content ?>',
                type: 'POST',
                data: $form.serializeArray(),
                success: function (response) {
                    response = JSON.parse(response);

                    if (response.status === 'failed') {
                        $(ev.target).prop('checked', false);
                        Swal.fire('Maaf', response.message, 'error');
                    }
                }
            })
            <?php endif; ?>
        });

        $('.poll-answer:not(.poll-answer-disabled)').on('click', function (ev) {
            ev.stopPropagation();

            var $input = $(this).find('input');

            $input.prop('checked', true).trigger('change');
            $('#poll-checkbox-vote-' + $input.val()).prop('checked', true);
        });

        $form.on('submit', function (ev) {
            ev.preventDefault();

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: $form.serializeArray(),
                success: function (response) {
                    response = JSON.parse(response);

                    if (response.status === 'success') {
                        $('#vote-button').remove();

                        $.each(response.votes_count, function (index, votes) {
                            votes.forEach(function (vote) {
                                $('#poll-answer-percentage-' + vote.id).text(vote.percentage);
                                $('#poll-answer-count-' + vote.id).text(vote.counts);
                                $('#poll-answer-progress-' + vote.id).css('width', vote.percentage.replace(',', '.') + '%');
                            });

                            <?php if ($content->type === '4') : ?>
                            $('#poll-answer-count-' + votes[0].id).parent().parent().removeClass('bg-success bg-danger').addClass(votes[0].counts < votes[1].counts ? 'bg-danger' : 'bg-success');
                            $('#poll-answer-count-' + votes[1].id).parent().parent().removeClass('bg-success bg-danger').addClass(votes[1].counts < votes[0].counts ? 'bg-danger' : 'bg-success');
                            <?php endif; ?>
                        });

                        $('.poll-answer:not(.poll-answer-disabled)').fadeOut();
                        $('.poll-answer.poll-answer-disabled').fadeIn();

                        Swal.fire(
                            'Terima kasih',
                            'telah memberikan vote anda pada polling ini.',
                            'success'
                        ).then(function () {
                            $('body,html').animate({
                                scrollTop: $('.poll:first').position().top + 150
                            }, 800);
                        });
                    } else if (response.status === 'failed') {
                        Swal.fire('Maaf', response.message, 'error');
                    }
                }
            })
        });
    });
    <?php elseif ($content->type === '5') : ?>
    $(document).ready(function () {
        var $form = $('#quiz-form');

        $('.quiz-choice input').on('change', function (ev) {
            <?php if (!$this->session->userdata('user_logged_in')) : ?>
            window.location.href = '<?php echo base_url() ?>page/login/<?php echo rtrim(base64_encode(urlencode($this->uri->uri_string())), "=") ?>'
            <?php elseif ($content->paginated === '1') : ?>
            $.ajax({
                url: '<?php echo base_url() ?>/content2/add_answer_session/<?php echo $content->id_content ?>',
                type: 'POST',
                data: $form.serializeArray(),
                success: function (response) {
                    response = JSON.parse(response);

                    if (response.status === 'failed') {
                        $(ev.target).prop('checked', false);
                        Swal.fire('Maaf', response.message, 'error');
                    }
                }
            })
            <?php endif; ?>
        });

        $('.quiz-choice:not(.quiz-choice-disabled)').on('click', function (ev) {
            ev.stopPropagation();

            var $input = $(this).find('input');

            $input.prop('checked', true).trigger('change');
        });

        $form.on('submit', function (ev) {
            ev.preventDefault();

            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: $form.serializeArray(),
                success: function (response) {
                    response = JSON.parse(response);
                    console.log(response)

                    if (response.status === 'success') {
                        $('#answer-button').remove();
                        $('#reset-button, #answer-info').removeClass('d-none');
                        $('.quiz-choice').addClass('quiz-choice-disabled').off('click');
                        $('.quiz-choice input').prop('disabled', true);
                        $('#total-correct-answers').text(response.total_correct_answers);

                        $.each(response.correct_answers, function (index, correct_answer) {
                            var $checkbox = $('#quiz-checkbox-' + correct_answer.id_content_quiz_choice)

                            $checkbox.closest('h4').addClass('bg-success');

                            if (!$checkbox.is(':checked')) {
                                $checkbox.closest('.quiz').find('input:checked').closest('h4').addClass('bg-danger');
                            }
                        });

                        Swal.fire(
                            'Terima kasih',
                            'telah menjawab quiz ini.',
                            'success'
                        ).then(function () {
                            $('body,html').animate({
                                scrollTop: $('#answer-info').offset().top - 100
                            }, 800);
                        });
                    } else if (response.status === 'failed') {
                        Swal.fire('Maaf', response.message, 'error');
                    }
                }
            })
        });
    });
    <?php endif; ?>
</script>
