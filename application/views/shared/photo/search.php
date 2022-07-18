<?php $search_param = $this->session->userdata('search_content'); ?>
<?php $id_user = $this->session->userdata('id_user'); ?>

<div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
    <?= form_open("{$base_url}/submitSearch", array('role' => 'form', 'class' => 'form-horizontal', 'autocomplete' => 'off')); ?>

    <div
        id="search_wrapper" <?= (isset($search_param['search_collapsed']) && $search_param['search_collapsed'] == '1' ? 'style="display:none"' : ''); ?>>
        <div class="form-group">
            <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left m-b-5">Tampilkan:</label>
                <div class="col-md-3 m-b-5">
                    <div class="input-group transparent">
                        <input class="form-control" type="text" name="per_page"
                            value="<?= ($search_param['per_page']); ?>">
                        <div class="input-group-append">
                            <span class="input-group-text transparent">data / halaman</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 m-b-5">
                    <div class="input-group transparent">
                        <div class="input-group-prepend">
                            <span class="input-group-text transparent">Urutkan</span>
                        </div>
                        <select class="full-width select_nosearch" name="sort_by">
                            <option
                                value="default" <?= ($search_param['sort_by'] == "default" ? "selected" : ""); ?>>Default
                            </option>
                            <option
                                value="newest" <?= ($search_param['sort_by'] == "newest" ? "selected" : ""); ?>>Terbaru
                            </option>
                            <option
                                value="oldest" <?= ($search_param['sort_by'] == "oldest" ? "selected" : ""); ?>>Terlama
                            </option>
                            <option
                                value="title_asc" <?= ($search_param['sort_by'] == "title_asc" ? "selected" : ""); ?>>Alphabetical (A-Z)
                            </option>
                            <option
                                value="title_desc" <?= ($search_param['sort_by'] == "title_desc" ? "selected" : ""); ?>>Alphabetical (Z-A)
                            </option>
                            <option
                                value="least_liked" <?= ($search_param['sort_by'] == "least_liked" ? "selected" : ""); ?>>Like Terendah
                            </option>
                            <option
                                value="most_liked" <?= ($search_param['sort_by'] == "most_liked" ? "selected" : ""); ?>>Like Tertinggi
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left m-b-5">Status : </label>
                <div class="col-md-10">
                    <div class="radio radio-complete">
                        <input type="radio" value="all" name="content_status"
                            id="content_status_all" <?= ($search_param['content_status'] != 'publish' && $search_param['content_status'] != 'unpublish' ? 'checked="checked"' : ''); ?>>
                        <label for="content_status_all">Semua</label>
                        <input type="radio" value="publish" name="content_status"
                            id="content_status_publish" <?= ($search_param['content_status'] == 'publish' ? 'checked="checked"' : ''); ?>>
                        <label for="content_status_publish">Publish</label>
                        <input type="radio" value="unpublish" name="content_status"
                            id="content_status_unpublish" <?= ($search_param['content_status'] == 'unpublish' ? 'checked="checked"' : ''); ?>>
                        <label for="content_status_unpublish">Menunggu approval</label>
                        <?php if($id_user ?? false){ ?>
                        <input type="radio" value="draft" name="content_status"
                            id="content_status_draft" <?= ($search_param['content_status'] == 'draft' ? 'checked="checked"' : ''); ?>>
                        <label for="content_status_draft">Draft</label>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left m-b-5">Tanggal publish: </label>
                <div class="col-md-3">
                    <div class="input-group tranparent">
                        <div class="input-group-prepend">
                            <span class="input-group-text transparent">Mulai</span>
                        </div>
                        <input type="text" class="form-control datepicker-component" id="datepicker-component"
                            name="start_date" value="<?= $search_param['start_date']; ?>"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group transparent">
                        <div class="input-group-prepend">
                            <span class="input-group-text transparent">Sampai</span>
                        </div>
                        <input type="text" class="form-control datepicker-component" id="datepicker-component"
                            name="finish_date" value="<?= $search_param['finish_date']; ?>"/>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group <?= ($is_admin ? '' : 'd-none') ?>">
            <div class="row">
                <label class="col-md-2 control-label text-right sm-text-left m-b-5">Tanggal like: </label>
                <div class="col-md-3">
                    <div class="input-group tranparent">
                        <div class="input-group-prepend">
                            <span class="input-group-text transparent">Mulai</span>
                        </div>
                        <input type="text" class="form-control datepicker-component" id="datepicker-component" name="like_start_date" value="<?= $search_param['like_start_date']; ?>"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group transparent">
                        <div class="input-group-prepend">
                            <span class="input-group-text transparent">Sampai</span>
                        </div>
                        <input type="text" class="form-control datepicker-component" id="datepicker-component" name="like_finish_date" value="<?= $search_param['finish_date']; ?>"/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <label class="col-md-2 control-label text-right sm-text-left m-b-5">Pencarian:</label>
            <div class="col-md-4 m-b-5">
                <select class="full-width select_nosearch" name="search_by">
                    <option value="title" <?= ($search_param['search_by'] == "title" ? "selected" : ""); ?>>Judul
                    </option>
                </select>
            </div>
            <div class="col-md-2 m-b-5">
                <select class="full-width select_nosearch" name="operator">
                    <option value="like" <?= ($search_param['operator'] == "like" ? "selected" : ""); ?>>=</option>
                    <option value="not like" <?= ($search_param['operator'] == "not like" ? "selected" : ""); ?>>!=
                    </option>
                </select>
            </div>
            <div class="col-md-4 m-b-5">
                <input class="form-control" type="text" name="keyword" placeholder="Keyword..."
                    value="<?= $search_param['keyword']; ?>">
            </div>
        </div>

        <?php if ($is_admin) { ?>
            <div class="form-group">
                <div class="row">
                    <label class="col-md-2 control-label text-right sm-text-left m-b-5">Kompetisi : </label>
                    <div class="col-md-6">
                        <select class="full-width select_withsearch" name="competition">
                            <option value="all" <?= ($search_param['competition'] == 'all' ? 'selected' : ''); ?>>Semua
                            </option>
                            <option
                                value="not" <?= ($search_param['competition'] == 'not' ? 'selected' : ''); ?>>Belum Diikutkan
                            </option>
                            <?php foreach ($competitions as $item) { ?>
                                <option
                                    value="<?= $item->id_competition; ?>" <?= ($search_param['competition'] == $item->id_competition ? "selected" : ""); ?>><?= $item->title ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        <?php } ?>

    </div>

    <div class="form-group">
        <div class="row">
            <label class="col-md-2 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
            <div class="col-md-9">
                <button class="btn btn-complete sm-m-b-10" type="submit"><i class="fa fa-search"></i> Pencarian</button>

                <a class="text-master-darker text-nowrap link sm-m-t-10 text-noselect"
                    id="search_toggle_advanced" <?= (isset($search_param['search_collapsed']) && $search_param['search_collapsed'] == '0' ? 'style="display:none"' : ''); ?>>
                    &nbsp;&nbsp; <i class="fa fa-chevron-circle-down"></i> &nbsp;Pencarian lengkap
                </a>
                <a class="text-master-darker text-nowrap link sm-m-t-10 text-noselect"
                    id="search_toggle_simple" <?= (isset($search_param['search_collapsed']) && $search_param['search_collapsed'] == '1' ? 'style="display:none"' : ''); ?>>
                    &nbsp;&nbsp; <i class="fa fa-chevron-circle-up"></i> &nbsp;Pencarian cepat
                </a>
                <input type="hidden" id="search_collapsed" name="search_collapsed"
                    value="<?= ($search_param['search_collapsed']); ?>"/>
            </div>
        </div>
    </div>
    <?= form_close(); ?>
</div>
