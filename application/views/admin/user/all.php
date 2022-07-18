<?php $this->load->view('admin/user/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
    <!-- BEGIN PlACE PAGE CONTENT HERE -->

    <div class="row">
        <div class="col-md-12">

            <!-- START card -->
            <div class="card card-transparent">
                <div class="card-body">

                    <?php if (strtolower($this->uri->segment(2)) == 'search') { ?>
                        <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Search Result :</h4>
                    <?php } ?>

                    <?= $this->session->flashdata('message'); ?>

                    <!-- Start Search Bar -->
                    <?php $this->load->view('admin/user/search'); ?>
                    <!-- End Search Bar -->

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="text-nowrap" width="5%"><b>No</b></th>
                                <th class="text-nowrap" width="10%"><b>Action</b></th>
                                <th class="text-nowrap"><b>Name</b></th>
                                <th class="text-nowrap"><b>Email</b></th>
                                <th class="text-nowrap"><b>Article</b></th>
                                <th class="text-nowrap"><b>Hypephoto</b></th>
                                <th class="text-nowrap"><b>Point</b></th>
                                <th class="text-nowrap"><b>Status</b></th>
                                <th class="text-nowrap"><b>Internal</b></th>
                                <th class="text-nowrap"><b>Verified</b></th>
                                <th class="text-nowrap"><b>Confirm Email</b></th>
                                <th class="text-nowrap"><b>Register date</b></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $x = ($this->uri->segment(3) > 0 ? $this->uri->segment(3) : 0); ?>
                            <?php foreach ($user as $item) { ?>
                                <tr>
                                    <td class="text-nowrap align-middle"><?= ($x + 1); ?></td>
                                    <td class="text-nowrap align-middle">
                                        <a href="<?= base_url(); ?>admin_user/edit/<?= $item->id_user; ?>"
                                            title="Edit User" class="btn btn-xs btn-info"><span
                                                class="fa fa-pencil"></span></a>
                                        <a href="<?= base_url(); ?>admin_user/delete/<?= $item->id_user; ?>"
                                            title="Delete User" class="btn btn-xs btn-info btn-need-confirmation"
                                            data-message="Are you sure want to delete this data?"><span
                                                class="fa fa-times"></span></a>
                                    </td>
                                    <td class="align-middle" title="Member internal hypeabis.id">
                                        <?= $item->name; ?>
                                    </td>
                                    <td class="align-middle"><?= $item->email; ?></td>
                                    <td class="align-middle"><?= $item->content_count; ?></td>
                                    <td class="align-middle"><?= $item->photo_count; ?></td>
                                    <td class="align-middle"><?= $item->point; ?></td>
                                    <td class="align-middle text-nowrap">
                                        <?php if ($item->status == '1') { ?>
                                            <a href="<?= base_url(); ?>admin_user/ban/<?= $item->id_user; ?>"
                                                class="btn-need-confirmation"
                                                data-message="Are you sure want to ban this account?"
                                                title="Click to ban this account">
                                                <span class="fa fa-check-square"></span> Active
                                            </a>
                                        <?php } else { ?>
                                            <a href="<?= base_url(); ?>admin_user/activate/<?= $item->id_user; ?>"
                                                class="btn-need-confirmation"
                                                data-message="Are you sure want to activate this account?"
                                                title="Click to activate this account">
                                                <span class="fa fa-minus-square"></span> Banned
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td class="align-middle text-center">
                                        <?php if ($item->is_internal == 1) { ?>
                                            <i class="fa fa-check-circle text-success"></i>&nbsp;
                                        <?php } ?>
                                    </td>
                                    <td class="align-middle">
                                        <?php if ($item->verified == '1') { ?>
                                            <label class="label label-info">Verified</label>
                                        <?php } else { ?>
                                            <label class="label label-primary">Standard</label>
                                        <?php } ?>
                                    </td>
                                    <td class="align-middle">
                                        <?php if ($item->confirm_email == '1') { ?>
                                            <label class="label label-info">Done</label>
                                        <?php } else { ?>
                                            <label class="label label-warning">Need confirmation</label>
                                        <?php } ?>
                                    </td>
                                    <td class="align-middle">
                                        <?= date('d M Y - H:i', strtotime($item->created)); ?>
                                    </td>
                                </tr>
                                <?php $x++; ?>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($x == 0) { ?>
                        <p class="m-t-40">There's no data in user database.</p>
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
