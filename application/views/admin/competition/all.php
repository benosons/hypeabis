<?php $this->load->view('admin/competition/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
    <!-- BEGIN PlACE PAGE CONTENT HERE -->

    <div class="row">
        <div class="col-md-12">

            <!-- START card -->
            <div class="card card-transparent">
                <div class="card-body">
                    <?php if (strtolower($this->uri->segment(2)) == 'search') : ?>
                        <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Hasil pencarian :</h4>
                    <?php endif; ?>

                    <?php echo $this->session->flashdata('message'); ?>

                    <!-- Start Search Bar -->
                    <?php # $this->load->view('admin/competition/search');
                    ?>
                    <!-- End Search Bar -->

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr class="">
                                <th class="text-nowrap"><b>No</b></th>
                                <th class="text-nowrap"><b>Action</b></th>
                                <th class="text-nowrap"><b>Judul</b></th>
                                <th class="text-nowrap"><b>Tipe</b></th>
                                <th class="text-nowrap"><b>Tanggal</b></th>
                                <th class="text-nowrap"><b>Maksimal Submit</b></th>
                                <th class="text-nowrap"><b>Status</b></th>
                                <th class="text-nowrap"><b>Cover Status</b></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($items as $index => $item) : ?>
                                <?php $nowTime = strtotime(date('Y-m-d H:i:s')) ?>
                                <?php $startDateTime = strtotime($item->start_date . ' 00:00:00'); ?>
                                <?php $finishDateTime = strtotime($item->finish_date . ' 23:59:59'); ?>

                                <tr>
                                    <td class="text-nowrap"><?php echo($offset + $index + 1); ?></td>
                                    <td class="text-nowrap">
                                        <?php if (!($nowTime >= $startDateTime && $nowTime <= $finishDateTime) || 1) { ?>
                                            <a href="<?php echo $base_url ?>/edit/<?php echo $item->id_competition; ?>"
                                                title="Edit" class="btn btn-xs btn-info"><span
                                                    class="fa fa-pencil"></span></a>
                                            <a href="<?php echo $base_url ?>/delete/<?php echo $item->id_competition; ?>"
                                                title="Delete" class="btn btn-xs btn-info btn-need-confirmation"
                                                data-message="Are you sure want to delete this competition?"><span
                                                    class="fa fa-times"></span></a>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <b><?php echo $item->title; ?></b><br/>
                                        <a href="<?= base_url(); ?>kompetisi/<?= $item->id_competition; ?>/<?= strtolower(url_title($item->title)); ?>"
                                            target="_blank">
                                            <?= base_url(); ?>kompetisi/<?= $item->id_competition; ?>/<?= strtolower(url_title($item->title)); ?>
                                        </a>
                                    </td>
                                    <td>
                                        <?= ($item->competition_type == 'photo' ? 'Photo' : 'Article'); ?>
                                    </td>
                                    <td class="text-nowrap">
                                        <span class="text-nowrap"><?php echo date('d M Y', $startDateTime) ?></span> -
                                        <span class="text-nowrap"><?php echo date('d M Y', $finishDateTime) ?></span>
                                    </td>
                                    <td class="text-nowrap"><?php echo $item->max_content; ?></td>
                                    <td class="align-middle">
                                        <?php if ($nowTime >= $startDateTime && $nowTime <= $finishDateTime) { ?>
                                            <label class="label label-warning">Sedang Berlangsung</label>
                                        <?php } elseif ($nowTime < $startDateTime) { ?>
                                            <label class="label label-primary">Belum Mulai</label>
                                        <?php } else { ?>
                                            <label class="label label-default">Berakhir</label>
                                        <?php } ?>
                                    </td>
                                    <td class="align-middle">
                                        <?php if ($item->cover_status) { ?>
                                            <label class="label label-info">Active</label>
                                        <?php } else { ?>
                                            <label class="label label-default">Non-Active</label>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php echo $this->pagination->create_links(); ?>
            </div>
            <!-- END CARD -->
        </div>
    </div>

    <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->
