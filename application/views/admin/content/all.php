<?php $this->load->view('admin/content/sub_header'); ?>
<?php $tab_id = $this->uri->segment(3); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
    <!-- BEGIN PlACE PAGE CONTENT HERE -->

    <div class="row">
        <div class="col-md-12">

            <!-- START card -->
            <div class="card card-transparent">
                <div class="card-body">

                    <?php if (strtolower($this->uri->segment(2)) == 'search') { ?>
                        <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Hasil pencarian :</h4>
                    <?php } ?>

                    <?= $this->session->flashdata('message'); ?>

                    <!-- Start Search Bar -->
                    <?php $this->load->view('admin/content/search'); ?>
                    <!-- End Search Bar -->

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr class="">
                                <th class="text-nowrap" width="5%"><b>No</b></th>
                                <th class="text-nowrap" width="100px"><b>Action</b></th>
                                <th class="text-nowrap"><b>Judul</b></th>
                                <th class="text-nowrap"><b>Kategori</b></th>
                                <th class="text-nowrap"><b>Last Update</b></th>
                                <th class="text-nowrap"><b>Publish Date</b></th>
                                <th class="text-nowrap" width="175px"><b>Published</b></th>
                                <th class="text-nowrap"><b><i class="fa fa-eye"></i></b></th>
                                <th class="text-nowrap"><b><i class="fa fa-thumbs-up"></i></b></th>
                                <th class="text-nowrap"><b><i class="fa fa-comment"></i></b></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                            <?php foreach ($content as $item) { ?>
                                <tr>
                                    <td class="text-nowrap"><?= ($x + 1); ?></td>
                                    <td class="text-nowrap">
                                        <?php if (is_null($item->edit_id_admin) || $item->edit_id_admin == $this->session->userdata('id_admin')) { ?>
                                            <a href="<?= base_url(); ?>admin_content/edit/<?= $item->id_content; ?>"
                                                title="Edit Content" class="btn btn-xs btn-info"><span
                                                    class="fa fa-pencil"></span></a>
                                            <?php if ($item->deletable == '1' || $this->session->userdata('admin_level') == '1') { ?>
                                                <a href="<?= base_url(); ?>admin_content/delete/<?= $item->id_content; ?>"
                                                    title="Delete Content"
                                                    class="btn btn-xs btn-info btn-need-confirmation"
                                                    data-message="Are you sure want to delete content?"><span
                                                        class="fa fa-times"></span></a>
                                            <?php } ?>
                                            <a href="<?= base_url(); ?>read/<?= $item->id_content; ?>/<?= strtolower(url_title($item->title)); ?>"
                                                title="Preview" class="btn btn-xs btn-info" target="_blank"><span
                                                    class="fa fa-eye"></span></a>
                                        <?php } ?>

                                        <?php if (!is_null($item->edit_id_admin)) { ?>
                                            <?php if ($item->edit_id_admin == $this->session->userdata('id_admin')) { ?>
                                                <a href="<?php echo base_url("admin_content/unlock_edit/{$item->id_content}") ?>"
                                                    class="btn btn-xs btn-info" title="Unlock Editor"><span
                                                        class="fa fa-unlock"></span></a>
                                            <?php } elseif ($this->session->userdata('admin_level') == '1') { ?>
                                                <a href="<?php echo base_url("admin_content/unlock_edit/{$item->id_content}") ?>"
                                                    class="btn btn-xs btn-complete"
                                                    title="Sedang diedit oleh <?php echo $item->edit_admin_name ?>">
                                                    <span class="fa fa-unlock"></span></a>
                                            <?php } else { ?>
                                                <?php $title = ($item->edit_id_admin !== $this->session->userdata('id_admin') ? "title='Sedang diedit oleh {$item->edit_admin_name}'" : '') ?>
                                                <button class="btn btn-xs btn-danger" disabled <?php echo $title ?>>
                                                    <span class="fa fa-lock"></span></button>
                                            <?php } ?>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <b><?= $item->title; ?></b><br/>
                                        <?= base_url(); ?>read/<?= $item->id_content; ?>/<?= strtolower(url_title($item->title)); ?>
                                    </td>
                                    <td>
                                        <?php
                                            if(isset($item->category_name) && strlen(trim($item->category_name)) > 0) {
                                                echo $item->category_name;
                                            }
                                            else if (isset($item->competition_category_name) && strlen(trim($item->competition_category_name)) > 0){
                                                echo $item->competition_category_name;
                                            }
                                            else{
                                                echo '-';
                                            }
                                        ?>
                                    </td>
                                    <td><?= date('d-M-Y H:i:s', strtotime($item->submit_date)); ?></td>
                                    <td>
                                        <?php if (isset($item->publish_date) && strlen(trim($item->publish_date)) > 0) { ?>
                                            <?= date('d-M-Y H:i:s', strtotime($item->publish_date)); ?>
                                        <?php } else { ?>
                                            -
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($item->content_status == '1') { ?>
                                            <label class="label label-info">Published</label>
                                        <?php } else {
                                            if ($item->content_status == '2') { ?>
                                                <label class="label label-success">Terjadwal</label>
                                            <?php } else {
                                                if ($item->content_status == '0') { ?>
                                                    <label class="label label-warning">Menunggu approval</label>
                                                <?php } else { ?>
                                                    <label class="label label-default">Draft</label>
                                                <?php }
                                            }
                                        } ?>
                                    </td>
                                    <td><?= number_format(ceil($item->read_count), 0, ',', '.'); ?></td>
                                    <td><?= number_format(ceil($item->like_count), 0, ',', '.'); ?></td>
                                    <td><?= number_format($item->comment_count, 0, ',', '.'); ?></td>
                                </tr>
                                <?php $x++; ?>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($x == 0) { ?>
                        <p class="m-t-40">Belum ada data artikel.</p>
                    <?php } ?>

                    <?= $this->pagination->create_links(); ?>

                </div>
            </div>
            <!-- END CARD -->
        </div>
    </div>

    <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->
