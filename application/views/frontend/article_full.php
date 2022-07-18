<section class="block pt-0 pb-0">
    <?php if ($page_no === '1'): ?>
        <?php if (isset($article[0]->content_pic) && strlen(trim($article[0]->content_pic)) > 0) { ?>
            <img src="<?= base_url(); ?>assets/content/<?= $article[0]->content_pic; ?>"
                class="img-fluid full-width lazy"
                title="<?= (isset($article[0]->pic_caption) && strlen(trim($article[0]->pic_caption)) > 0 ? $article[0]->pic_caption : "Bisnis Muda"); ?>"
                alt="<?= (isset($article[0]->pic_caption) && strlen(trim($article[0]->pic_caption)) > 0 ? $article[0]->pic_caption : "Bisnis Muda"); ?>">
        <?php } ?>
    <?php endif; ?>
</section>

<?php
if ((isset($ads['sup_leaderboard_ar']['builtin'][0]['id_ads']) && $ads['sup_leaderboard_ar']['builtin'][0]['id_ads'] > 0) ||
    (isset($ads['sup_leaderboard_ar']['googleads'][0]['id_ads']) && $ads['sup_leaderboard_ar']['googleads'][0]['id_ads'] > 0)) {
    ?>
    <center>
        <div class="adv-home-super-leaderboard m-t-25 m-b-0">
            <?php if (isset($ads['sup_leaderboard_ar']['builtin'][0]['id_ads']) && $ads['sup_leaderboard_ar']['builtin'][0]['id_ads'] > 0) { ?>
                <a href="<?= base_url(); ?>page/redirectAds/<?= $ads['sup_leaderboard_ar']['builtin'][0]['id_ads']; ?>"
                    target="_blank">
                    <img src="<?= base_url(); ?>assets/adv/<?= $ads['sup_leaderboard_ar']['builtin'][0]['ads_pic']; ?>"
                        alt="Bisnis Muda" class="img img-fluid"/>
                </a>
            <?php } else {
                if (isset($ads['sup_leaderboard_ar']['googleads'][0]['id_ads']) && $ads['sup_leaderboard_ar']['googleads'][0]['id_ads'] > 0) { ?>
                    <?= $ads['sup_leaderboard_ar']['googleads'][0]['googleads_code']; ?>
                <?php } else {
                }
            } ?>
        </div>
    </center>
<?php } ?>

<!-- Section category article -->
<section class="block">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="single-post">
                    <?php if ($is_preview): ?>
                        <div class="alert alert-info" role="alert">
                            <i class="fa fa-info-circle"></i>
                            <strong>Preview Artikel.</strong> <?php echo $this->session->flashdata('message') ?>
                            <a href="<?php echo base_url() . ($this->session->is_admin_dashboard ? 'admin' : 'user') . '_content/edit/' . $article[0]->id_content ?>"
                                class="btn btn-like btn-primary pull-right" style="margin-top: -3px">Edit artikel
                            </a>
                        </div>
                    <?php endif; ?>
                    <div class="post-header-area">
                        <h1 class="post-title title-lg"><?= $article[0]->title; ?></h1>
                    </div><!-- post-header-area end -->
                    <div class="post-content-area m-t-20">
                        <ul class="post-meta mb-5">
                            <?php if (isset($article[0]->id_user) && $article[0]->id_user > 0) { ?>
                                <li>
                                    <a href="<?= base_url(); ?>author/<?= $article[0]->id_user; ?>/<?= strtolower(url_title($article[0]->name)); ?>">
                                        <img
                                            src="<?= $this->frontend_lib->getUserPictureURL($article[0]->picture, $article[0]->picture_from); ?>"
                                            alt="Avatar" class="avatar lazy" align="left">
                                        <?= $article[0]->name; ?>
                                        <?php if ($article[0]->verified == 1) { ?>
                                            &nbsp;<img src="<?= base_url(); ?>files/frontend/images/verified.png"
                                                class="lazy" alt="Verified member" title="Verified member" height="15px"
                                                style="margin-top:-2px;"/>
                                        <?php } ?>
                                    </a>
                                </li>
                            <?php } ?>
                            <li>
                                <a href="javascript:;"><i
                                        class="fa fa-clock-o"></i> <?= $article[0]->publish_date ? date('d F Y', strtotime($article[0]->publish_date)) : ''; ?>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;"><i
                                        class="fa fa-eye"></i> <?= number_format(ceil($article[0]->read_count), 0, ',', '.'); ?>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:;"><i
                                        class="fa fa-comment"></i> <?= number_format($article[0]->comment_count, 0, ',', '.'); ?>
                                </a>
                            </li>
                        </ul>

                        <!-- share & like -->
                        <div class="row m-b-20">
                            <div class="col-7 col-sm-7">
                                <div class="sharethis-inline-share-buttons"></div>
                            </div>
                            <div class="col-5 col-sm-5">
                                <?php if ($this->session->userdata('user_logged_in') !== true) { ?>

                                    <?php $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())), "="); ?>
                                    <?php if ($article[0]->like_count > 0) { ?>
                                        <div class="like_counter pull-right">
                                            <center>
                                                <?= number_format(ceil($article[0]->like_count), 0, ',', '.'); ?><br/>
                                                <span><?= ($article[0]->like_count > 1 ? 'Likes' : 'Like'); ?></span>
                                            </center>
                                        </div>
                                    <?php } ?>
                                    <a class="btn btn-like btn-warning pull-right"
                                        href="<?= base_url(); ?>page/login/<?= $redirect_url; ?>">
                                        <i class="fa fa-thumbs-up"></i> Like
                                    </a>

                                <?php } else { ?>

                                    <!-- Like counter & text -->
                                    <div
                                        class="like_counter_wrapper pull-right" <?= ($article[0]->like_count > 0 ? '' : 'style="display:none;"'); ?>>
                                        <center>
                                            <span
                                                id="like_counter"><?= number_format(ceil($article[0]->like_count), 0, ',', '.'); ?></span><br/>
                                            <span><?= ($article[0]->like_count > 1 ? 'Likes' : 'Like'); ?></span>
                                        </center>
                                    </div>
                                    <!-- Button like -->
                                    <?php if ($liked) { ?>
                                        <a class="btn btn-like btn-warning pull-right" href="javascript:;">
                                            <i class="fa fa-thumbs-up"></i> Liked
                                        </a>
                                    <?php } else { ?>
                                        <a class="btn btn-like btn-warning pull-right"
                                            href="javascript:;" <?php echo(!$is_preview ? 'onclick="likeArticle();"' : '') ?>>
                                            <i class="fa fa-thumbs-up"></i> Like
                                        </a>
                                    <?php } ?>

                                <?php } ?>
                            </div>
                        </div>
                        <!-- share & like -->

                        <?php $content_file_path = base_url() . "assets/content/"; ?>
                        <div class="texteditor_content"
                            style="overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;">
                            <?= str_replace("##BASE_URL##", $content_file_path, html_entity_decode($page_no === '1' ? $article[0]->content : $page->content)); ?>
                        </div>
                    </div><!-- post-content-area end -->

                    <div class="post-footer mt-20">
                        <?php if (isset($tags) && is_array($tags) && count($tags) > 0) { ?>
                            <div class="tag-lists">
                                <span>Tags: </span>
                                <?php foreach ($tags as $tag) { ?>
                                    <a href="<?= base_url(); ?>content/tag/<?= $tag->id_content_tag; ?>/<?= strtolower(url_title($tag->id_content_tag)); ?>">
                                        <?= $tag->tag_name; ?>
                                    </a>
                                <?php } ?>
                            </div><!-- tag lists -->
                        <?php } ?>

                        <?php if ($article[0]->paginated === '1' && intval($max_page_no) > 1): ?>
                            <?php $article_url = base_url() . "read/{$article[0]->id_content}" . '/' . strtolower(url_title($article[0]->title)) ?>
                            <nav class="clearfix row m-b-30">
                                <div class="col-md-4">
                                    <?php if ($page_no > '1'): ?>
                                        <?php $page_url = $page_no !== '2' ? '/' . ($page_no - 1) : '' ?>
                                        <a href="<?php echo $article_url . $page_url ?>"
                                            class="btn btn-sm btn-block btn-primary">
                                            SEBELUMNYA
                                        </a>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4 text-center align-middle" style="line-height: 3;">
                                    <span>Halaman <?php echo $page_no ?> dari <?php echo $max_page_no ?></span></div>
                                <div class="col-md-4 text-right">
                                    <?php if ($page_no < $max_page_no): ?>
                                        <a href="<?php echo $article_url ?>/<?php echo($page_no + 1) ?>"
                                            class="btn btn-sm btn-block btn-primary">
                                            SELANJUTNYA
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </nav>
                        <?php endif; ?>

                        <!-- reaction start-->
                        <div class="clearfix mt-20">
                            <?php if (!$reacted) { ?>
                                <strong>Berikan reaksi anda:</strong>
                            <?php } ?>
                            <div class="row">
                                <?php foreach ($reaction as $x => $react) { ?>
                                    <?php
                                    $percent = 0;
                                    $total = 0;
                                    $pembagi = 0;
                                    foreach ($content_reaction as $cr) {
                                        $pembagi += $cr->reaction_count;
                                        if ($cr->id_reaction == $react->id_reaction) {
                                            $total = $cr->reaction_count;
                                        }
                                    }
                                    if ($total > 0 && $pembagi > 0) {
                                        $percent = ($total / $pembagi) * 100;
                                    }
                                    ?>
                                    <div class="col-4 col-lg-2">
                                        <?php if ($reacted){ ?>
                                        <a href="javascript:;" class="article_reaction">
                                            <?php } else { ?>
                                            <?php if ($this->session->userdata('user_logged_in') !== true){ ?>
                                            <?php $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())), "="); ?>
                                            <a href="<?= base_url(); ?>page/login/<?= $redirect_url; ?>"
                                                class="article_reaction">
                                                <?php } else { ?>
                                                <a href="javascript:;"
                                                    class="article_reaction" <?php echo(!$is_preview ? "onclick=\"reactArticle('{$react->id_reaction}')\"" : '') ?>>
                                                    <?php } ?>
                                                    <?php } ?>
                                                    <p align="center">
                                                    <center>
                                                        <img
                                                            src="<?= base_url(); ?>files/frontend/images/emoticon/<?= $react->reaction_pic; ?>"
                                                            class="img img-fluid" alt="<?= $react->reaction_name; ?>"
                                                            style="max-width:80px;"/>
                                                        <br/><small
                                                            class="text-black"><b><?= $react->reaction_name; ?></b></small>
                                                        <?php if ($percent > 0) { ?>
                                                            <br/><small
                                                                id="reaction_<?= $react->id_reaction; ?>"><?= rtrim(rtrim(number_format($percent, 1, ',', '.'), '0'), ','); ?>%</small>
                                                        <?php } else { ?>
                                                            <br/><small
                                                                id="reaction_<?= $react->id_reaction; ?>">0%</small>
                                                        <?php } ?>
                                                    </center>
                                                    </p>
                                                </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <!-- reaction end-->

                        <div class="post-navigation clearfix mt-20">
                            <div class="post-previous float-left">
                                <?php if (isset($article_prev[0]->id_content) && $article_prev[0]->id_content > 0) { ?>
                                    <a href="<?= base_url(); ?>read/<?= $article_prev[0]->id_content; ?>/<?= strtolower(url_title($article_prev[0]->title)); ?>">
                                        <?php if (isset($article_prev[0]->content_pic_thumb) && strlen(trim($article_prev[0]->content_pic_thumb)) > 0) { ?>
                                            <img
                                                src="<?= base_url(); ?>assets/content/thumb/<?= $article_prev[0]->content_pic_thumb; ?>"
                                                class="lazy">
                                        <?php } ?>
                                        <span class="mb-5"><i
                                                class="fa fa-chevron-circle-left"></i> &nbsp;Sebelumnya</span>
                                        <p>
                                            <?= $article_prev[0]->title; ?>
                                        </p>
                                    </a>
                                <?php } ?>
                            </div>
                            <div class="post-next float-right">
                                <?php if (isset($article_next[0]->id_content) && $article_next[0]->id_content > 0) { ?>
                                    <a href="<?= base_url(); ?>read/<?= $article_next[0]->id_content; ?>/<?= strtolower(url_title($article_next[0]->title)); ?>">
                                        <?php if (isset($article_next[0]->content_pic_thumb) && strlen(trim($article_next[0]->content_pic_thumb)) > 0) { ?>
                                            <img
                                                src="<?= base_url(); ?>assets/content/thumb/<?= $article_next[0]->content_pic_thumb; ?>"
                                                class="lazy">
                                        <?php } ?>
                                        <span class="mb-5">Selanjutnya &nbsp;<i class="fa fa-chevron-circle-right"></i></span>
                                        <p>
                                            <?= $article_next[0]->title; ?>
                                        </p>
                                    </a>
                                <?php } ?>
                            </div>
                        </div><!-- post navigation -->

                        <div class="gap-30"></div>

                        <?php if (isset($related_article) && is_array($related_article) && count($related_article) > 0) { ?>
                            <!-- realted post start -->
                            <div class="related-post">
                                <h2 class="block-title-small heading-black">
                                    <span>Artikel terkait</span>
                                </h2>
                                <div class="row mt-20">
                                    <?php foreach ($related_article as $related) { ?>
                                        <div class="col-md-4">
                                            <div class="post-block-style">
                                                <?php if (isset($related->content_pic_thumb) && strlen(trim($related->content_pic_thumb)) > 0) { ?>
                                                    <div class="post-thumb">
                                                        <a href="<?= base_url(); ?>read/<?= $related->id_content; ?>/<?= strtolower(url_title($related->title)); ?>">
                                                            <img class="img-fluid lazy"
                                                                src="<?= base_url(); ?>assets/content/thumb/<?= $related->content_pic_thumb; ?>"
                                                                alt="Bisnis Muda">
                                                        </a>
                                                    </div>
                                                <?php } ?>
                                                <div class="post-content">
                                                    <h2 class="post-title">
                                                        <a href="<?= base_url(); ?>read/<?= $related->id_content; ?>/<?= strtolower(url_title($related->title)); ?>">
                                                            <?= $related->title; ?>
                                                        </a>
                                                    </h2>
                                                    <div class="post-meta mb-7 p-0">
                                                        <span class="post-date"><i
                                                                class="fa fa-clock-o"></i> <?= $related->submit_date ? date('d F Y', strtotime($related->submit_date)) : ; ?></span>
                                                    </div>
                                                </div><!-- Post content end -->
                                            </div>
                                        </div><!-- col end -->
                                    <?php } ?>
                                </div><!-- row end -->
                            </div>
                            <!-- realted post end -->
                        <?php } ?>

                        <div class="gap-30"></div>
                        <input type="hidden" id="id_content" value="<?= $article[0]->id_content; ?>"/>

                        <?php if (isset($comments) && is_array($comments) && count($comments) > 0) { ?>
                            <!-- comments start -->
                            <div class="comments-area">
                                <h3 class="block-title-small heading-black">
                                    <span>Komentar</span>
                                </h3>
                                <ul class="comments-list mt-20 pl-0" id="comments-list">
                                    <?php foreach ($comments as $comment) { ?>
                                        <li>
                                            <div class="comment">
                                                <a href="<?= base_url(); ?>author/<?= $comment->id_user; ?>/<?= strtolower(url_title($comment->name)); ?>">
                                                    <img
                                                        src="<?= $this->frontend_lib->getUserPictureURL($comment->picture, $comment->picture_from); ?>"
                                                        alt="Avatar" class="comment-avatar pull-left lazy" align="left">
                                                </a>
                                                <div class="comment-body">
                                                    <div class="meta-data">
                                                        <span class="comment-author"><?= $comment->name; ?></span>
                                                        <p class="m-b-0">
                                                            <small><?= date('d M Y - H:i', strtotime($comment->comment_date)); ?></small>
                                                        </p>
                                                    </div>
                                                    <div class="comment-content">
                                                        <p><?= nl2br($comment->comment); ?></p></div>
                                                </div>
                                            </div><!-- Comments end -->
                                        </li><!-- Comments-list li end -->
                                    <?php } ?>
                                </ul><!-- Comments-list ul end -->

                                <input type="hidden" id="comment_offset" value="<?= $this->per_page_comment; ?>"/>
                                <?php if ($comments_number > $this->per_page_comment) { ?>
                                    <button class="btn btn-sm btn-primary" id="load_comment_btn"
                                        onclick="loadComments()">Lihat komentar sebelumnya
                                    </button>
                                <?php } ?>
                            </div><!-- comment end -->
                        <?php } ?>

                        <hr class="mt-20 mb-20">

                        <!-- comment form -->
                        <div class="comments-form m-t-45">
                            <h2 class="block-title-small heading-black m-b-30">
                                <span>Tulis Komentar</span>
                            </h2>
                            <?php if ($this->session->userdata('user_logged_in') !== true) { ?>

                                <?php $redirect_url = rtrim(base64_encode(urlencode($this->uri->uri_string())), "="); ?>
                                <p>
                                    Anda harus
                                    <a href="<?= base_url(); ?>page/login/<?= $redirect_url; ?>"><b><u>Login</u></b></a>
                                    terlebih dahulu untuk meninggalkan komentar.
                                </p>

                            <?php } else { ?>

                                <div class="addcomment_wrapper">
                                    <div id="comment_loader"
                                        style="background:rgba(255, 255, 255, 0.75);width:100%;height:100%;position:absolute;z-index:20;display:none;">
                                        <center>
                                            <img src="<?= base_url(); ?>files/backend/img/ajax-loader-big.gif"
                                                class="lazy"/><br/>
                                            <p class="small hint-text text-black">Loading...</p>
                                        </center>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="comment_message"></div>
                                            <div class="form-group">
                                                <textarea class="form-control input-msg required-field"
                                                    id="comment_content" placeholder="Your Comment" rows="10"
                                                    required=""></textarea>
                                            </div>
                                        </div><!-- Col end -->
                                    </div><!-- Form row end -->

                                    <?= $this->recaptcha->render(); ?>

                                    <div class="clearfix m-t-20">
                                        <button class="comments-btn btn btn-primary"
                                            type="button" <?php echo(!$is_preview ? 'onclick="submitComment()"' : '') ?>>Submit komentar
                                        </button>
                                    </div>
                                </div>

                            <?php } ?>
                        </div><!-- comment form end -->
                    </div>
                </div><!-- single-post end -->

            </div>
        </div>
    </div>
</section>
<!-- section end -->

<script type="text/javascript">
    function likeArticle() {
        var post_data = {};
        post_data['id_content'] = $('#id_content').val();

        $.ajax({
            'url': '<?= base_url(); ?>' + 'content/likeContent',
            'type': 'POST', //the way you want to send data to your URL
            'data': getCSRFToken(post_data),
            'success': function (data) { //probably this request will return anything, it'll be put in var "data"
                //if the request success..
                var obj = JSON.parse(data); // parse data from json to object..

                //if status not success, show message..
                if(obj.status == 'success'){
                    $('#like_counter').html(obj.like_count);
                    $('.like_counter_wrapper').show();
                    Swal.fire('Terima kasih', obj.message, 'success');
                }
                else if(obj.status == 'nologin'){
                    Swal.fire('Maaf', 'anda harus login terlebih dahulu.', 'error');
                }
                else if(obj.status == 'already_liked'){
                    Swal.fire('Terima kasih', obj.message, 'warning');
                }
                else{
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

    function submitComment() {
        $("#comment_loader").show();
        $("#comment_message").empty();

        var post_data = {};
        post_data['id_content'] = $('#id_content').val();
        post_data['comment_content'] = $('#comment_content').val();
        post_data['g-recaptcha-response'] = grecaptcha.getResponse();

        $.ajax({
            'url': '<?= base_url(); ?>' + 'content/submitComment',
            'type': 'POST', //the way you want to send data to your URL
            'data': getCSRFToken(post_data),
            'success': function (data) { //probably this request will return anything, it'll be put in var "data"
                //if the request success..
                var obj = JSON.parse(data); // parse data from json to object..

                //if status not success, show message..
                if (obj.status == 'success') {
                    resetCommentForm();
                    refreshCSRFToken(obj.csrf_token_name, obj.csrf_token_hash);
                    Swal.fire(
                        'Terima kasih',
                        'Komentar anda akan ditampilkan di website setelah proses moderasi tim Bisnis Muda.',
                        'success'
                    );
                } else {
                    $("#comment_message").html(obj.message);
                    refreshCSRFToken(obj.csrf_token_name, obj.csrf_token_hash);
                }

                $("#comment_loader").hide();
            },
            'complete': function () {
                $('#comment_loader').hide();
                $("#comment_btn").prop('disabled', false);
            }
        });
    }

    function loadComments() {
        $("#comment_loader").show();
        $("#comment_message").empty();

        var post_data = {};
        post_data['id_content'] = $('#id_content').val();
        post_data['comment_offset'] = $('#comment_offset').val();

        $.ajax({
            'url': '<?= base_url(); ?>' + 'content/loadComment',
            'type': 'POST', //the way you want to send data to your URL
            'data': getCSRFToken(post_data),
            'success': function (data) { //probably this request will return anything, it'll be put in var "data"
                //if the request success..
                var obj = JSON.parse(data); // parse data from json to object..

                //if status not success, show message..
                if (obj.status == 'success') {
                    loadCommentData(obj.comments);
                    $("#comment_offset").val(obj.next_offset);
                    if (obj.show_loadmore != '1') {
                        $("#load_comment_btn").hide();
                    }
                } else {
                    refreshCSRFToken(obj.csrf_token_name, obj.csrf_token_hash);
                }

                $("#comment_loader").hide();
            },
            'complete': function () {
                $('#comment_loader').hide();
            }
        });
    }

    function loadCommentData(data) {
        $.each(data, function (key, comment) {
            var comment_str = "";
            comment_str += "<li>";
            comment_str += "<div class='comment'>";
            comment_str += "<a href='<?= base_url(); ?>author/" + comment.id_user + "/" + comment.name_url + "'>";
            comment_str += "<img src='" + comment.picture_src + "' alt='Avatar' class='comment-avatar pull-left lazy' align='left'> ";
            comment_str += "</a>";
            comment_str += "<div class='comment-body'>";
            comment_str += "<div class='meta-data'>";
            comment_str += "<span class='comment-author'>" + comment.name + "</span>";
            comment_str += "<p class='m-b-0'><small>" + comment.date_str + "</small></p>";
            comment_str += "</div>";
            comment_str += "<div class='comment-content'>";
            comment_str += "<p>" + comment.content_str + "</p></div>";
            comment_str += "</div>";
            comment_str += "</div>";
            comment_str += "</li>";
            $('#comments-list').append(comment_str);
        });
    }

    function resetCommentForm() {
        $('#comment_content').val('');
        grecaptcha.reset();
    }
</script>
