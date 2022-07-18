<?php $this->load->view('admin/user/sub_header'); ?>

<!-- START CONTAINER FLUID -->
<div class="container-fluid container-fixed-lg content-wrapper">
    <!-- BEGIN PlACE PAGE CONTENT HERE -->

    <div class="row">
        <div class="col-md-12">

            <!-- START card -->
            <div class="card card-transparent">
                <div class="card-body">

                    <?= $this->session->flashdata('message'); ?>

                    <div class="row">
                        <div class="col-md-8 m-b-30">
                            <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">
                                Edit User Account
                            </h4>

                            <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
                                <?= form_open_multipart("admin_user/saveEdit/" . $user[0]->id_user, array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'user_form', 'autocomplete' => 'off')); ?>
                                <div class="form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-3 control-label text-right sm-text-left">User picture</label>
                                        <div class="col-md-9 col-xs-12">
                                            <?php if (strlen(trim($user[0]->picture)) > 0) { ?>
                                                <div class="file-preview">
                                                    <a href="<?= base_url(); ?>admin_user/deletePic/<?= $user[0]->id_user; ?>"
                                                        class="close fileinput-remove text-right btn-need-confirmation"
                                                        data-message="Are you sure want to remove this picture?"
                                                        title="remove / delete"><span
                                                            class="fs-16 fa fa-times m-l-5 m-r-5"></span></a>
                                                    <div class="file-preview-thumbnails">
                                                        <div class="file-preview-frame">
                                                            <img
                                                                src="<?= $this->frontend_lib->getUserPictureURL($user[0]->picture, $user[0]->picture_from); ?>"
                                                                class="file-preview-image"
                                                                title="<?= $user[0]->picture; ?>" width="auto"
                                                                style="max-height:100px">
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="file-preview-status text-center text-success"></div>
                                                </div>
                                            <?php } ?>

                                            <input type="file" class="file" name="picture" id="picture"
                                                data-show-upload="false" data-show-close="false"
                                                data-show-preview="false"
                                                data-allowed-file-extensions='["jpg", "jpeg", "png"]'/>
                                            <p class="hint-text">
                                                <small>*(Size Recommendation: <?= $this->user_pic_width; ?>px x <?= $this->user_pic_height; ?>px. leave this field blank if you don't want to edit user picture)</small>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label text-right sm-text-left">Nama</label>
                                        <div class="col-md-9 col-xs-12">
                                            <input class="form-control" type="text" name="name"
                                                value="<?= $user[0]->name; ?>"/>
                                            <?php
                                            if ((isset($user[0]->oauth_uid_facebook) && strlen(trim($user[0]->oauth_uid_facebook)) > 0) ||
                                                (isset($user[0]->oauth_uid_google) && strlen(trim($user[0]->oauth_uid_google)) > 0)
                                            ) {
                                                ?>
                                                <p class="hint-text">
                                                    <small>
                                                        <?php $need_br = false; ?>
                                                        <?php if (isset($user[0]->oauth_uid_facebook) && strlen(trim($user[0]->oauth_uid_facebook)) > 0) { ?>
                                                            <b>Facebook ID: <?= $user[0]->oauth_uid_facebook; ?></b>
                                                            <?php $need_br = true; ?>
                                                        <?php } ?>
                                                        <?php if (isset($user[0]->oauth_uid_google) && strlen(trim($user[0]->oauth_uid_google)) > 0) { ?>
                                                            <b><?= ($need_br ? "<br/>" : ""); ?>Google ID: <?= $user[0]->oauth_uid_google; ?></b>
                                                        <?php } ?>
                                                    </small>
                                                </p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-3 control-label text-right sm-text-left">Profil singkat</label>
                                        <div class="col-md-9 col-xs-12">
                                            <input type="text" name="profile_desc" class="form-control" maxlength="100"
                                                value="<?= $user[0]->profile_desc; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label text-right sm-text-left">Gender</label>
                                        <div class="col-md-9 col-xs-12">
                                            <div class="radio radio-complete">
                                                <input type="radio" value="1" name="gender"
                                                    id="gender1" <?= ($user[0]->gender == '1' ? 'checked="checked"' : ''); ?>>
                                                <label for="gender1">Men</label>
                                                <input type="radio" value="0" name="gender"
                                                    id="gender0" <?= ($user[0]->gender == '0' ? 'checked="checked"' : ''); ?>>
                                                <label for="gender0">Women</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label text-right sm-text-left">Email</label>
                                        <div class="col-md-9 col-xs-12">
                                            <input class="form-control" type="text" name="email"
                                                value="<?= $user[0]->email; ?>"/>
                                        </div>
                                    </div>
                                </div>

                                <?php
                                $dob = isset($user[0]->dob) ? $user[0]->dob : '';
                                $is_valid_date = $this->global_lib->validateDate($dob, 'Y-m-d');
                                ?>
                                <div class="form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-3 control-label text-right sm-text-left">Tempat / Tanggal Lahir:</label>
                                        <div class="col-md-4 col-lg-4">
                                            <input type="text" class="form-control" name="tempat_lahir"
                                                id="tempat_lahir"
                                                value="<?= (isset($user[0]->tempat_lahir) ? $user[0]->tempat_lahir : ''); ?>"/>
                                        </div>
                                        <div class="col-md-5 col-lg-5">
                                            <input type="text" class="form-control datepicker-component" name="dob"
                                                id="dob"
                                                value="<?= ($is_valid_date ? date('d-m-Y', strtotime($user[0]->dob)) : ''); ?>"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-3 control-label text-right sm-text-left">Nomor kontak</label>
                                        <div class="col-md-9 col-xs-12">
                                            <input class="form-control" type="text" name="contact_number"
                                                value="<?= $user[0]->contact_number; ?>"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label text-right sm-text-left"
                                            for="address">Alamat:</label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" rows="5" name="address"
                                                required><?= (isset($user[0]->address) ? $user[0]->address : ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-3 control-label text-right sm-text-left">URL Facebook</label>
                                        <div class="col-md-9 col-xs-12">
                                            <div class="input-group tranparent">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text transparent">https://</span>
                                                </div>
                                                <input class="form-control" type="text" name="facebook"
                                                    value="<?= $user[0]->facebook; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-3 control-label text-right sm-text-left">URL Twitter</label>
                                        <div class="col-md-9 col-xs-12">
                                            <div class="input-group tranparent">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text transparent">https://</span>
                                                </div>
                                                <input class="form-control" type="text" name="twitter"
                                                    value="<?= $user[0]->twitter; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-3 control-label text-right sm-text-left">URL Instagram</label>
                                        <div class="col-md-9 col-xs-12">
                                            <div class="input-group tranparent">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text transparent">https://</span>
                                                </div>
                                                <input class="form-control" type="text" name="instagram"
                                                    value="<?= $user[0]->instagram; ?>"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label text-right sm-text-left">Pekerjaan:</label>
                                        <div class="col-md-9">
                                            <select name="id_job" class="full-width select_withsearch">
                                                <option value="">- Pilih pekerjaan -</option>
                                                <?php foreach ($job as $item) { ?>
                                                    <option
                                                        value="<?= $item->id_job; ?>" <?= ($item->id_job == $user[0]->id_job ? 'selected' : ''); ?>>
                                                        <?= $item->job_name; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label text-right sm-text-left">Bidang:</label>
                                        <div class="col-md-9">
                                            <select name="id_jobfield" class="full-width select_withsearch">
                                                <option value="">- Pilih bidang -</option>
                                                <?php foreach ($jobfield as $item) { ?>
                                                    <option
                                                        value="<?= $item->id_jobfield; ?>" <?= ($item->id_jobfield == $user[0]->id_jobfield ? 'selected' : ''); ?>>
                                                        <?= $item->job_field; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label text-right sm-text-left">Interest:</label>
                                        <div class="col-md-9">
                                            <select name="id_interest" class="full-width select_withsearch">
                                                <option value="">- Pilih interest -</option>
                                                <?php foreach ($interest as $item) { ?>
                                                    <option
                                                        value="<?= $item->id_interest; ?>" <?= ($item->id_interest == $user[0]->id_interest ? 'selected' : ''); ?>>
                                                        <?= $item->interest; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label class="col-md-3 control-label text-right sm-text-left">Status</label>
                                        <div class="col-md-9 col-xs-12">
                                            <div class="radio radio-complete">
                                                <input type="radio" value="1" name="status"
                                                    id="status1" <?= ($user[0]->status == '1' ? 'checked="checked"' : ''); ?>>
                                                <label for="status1">Active</label>
                                                <input type="radio" value="0" name="status"
                                                    id="status0" <?= ($user[0]->status == '0' ? 'checked="checked"' : ''); ?>>
                                                <label for="status0">Banned</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-3 control-label text-right sm-text-left">Member status</label>
                                        <div class="col-md-9 col-xs-12">
                                            <div class="radio radio-complete">
                                                <input type="radio" value="0" name="verified"
                                                    id="verified0" <?= ($user[0]->verified == '0' ? 'checked="checked"' : ''); ?>>
                                                <label for="verified0">Standard</label>
                                                <input type="radio" value="1" name="verified"
                                                    id="verified1" <?= ($user[0]->verified == '1' ? 'checked="checked"' : ''); ?>>
                                                <label for="verified1">Verified</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-3 control-label text-right sm-text-left">Confirm email</label>
                                        <div class="col-md-9 col-xs-12">
                                            <div class="radio radio-complete">
                                                <input type="radio" value="1" name="confirm_email"
                                                    id="confirm_email1" <?= ($user[0]->confirm_email == '1' ? 'checked="checked"' : ''); ?>>
                                                <label for="confirm_email1">Yes</label>
                                                <input type="radio" value="0" name="confirm_email"
                                                    id="confirm_email0" <?= ($user[0]->confirm_email == '0' ? 'checked="checked"' : ''); ?>>
                                                <label for="confirm_email0">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-3 control-label text-right sm-text-left">Member internal</label>
                                        <div class="col-md-9 col-xs-12">
                                            <div class="radio radio-complete">
                                                <input type="radio" value="1" name="is_internal"
                                                    id="is_internal1" <?= ($user[0]->is_internal == '1' ? 'checked="checked"' : ''); ?>>
                                                <label for="is_internal1">Yes</label>
                                                <input type="radio" value="0" name="is_internal"
                                                    id="is_internal0" <?= ($user[0]->is_internal == '0' ? 'checked="checked"' : ''); ?>>
                                                <label for="is_internal0">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-3 hidden-xs control-label text-right sm-text-left">&nbsp;</label>
                                        <div class="col-md-9">
                                            <button class="btn btn-complete sm-m-b-10" type="submit">Update account
                                            </button>
                                            <button class="btn btn-default sm-m-b-10" onclick="history.go(-1)"><i
                                                    class="fa fa-chevron-circle-left"></i> Back
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <?= form_close(); ?>
                            </div>
                        </div>

                        <div class="col-md-4 m-b-30">
                            <h4 class="m-t-0 m-b-15 fw-600 text-heading-black">Change password</h4>

                            <div class="bg-master-lighter padding-20 m-b-20 b-rad-lg">
                                <?= form_open_multipart("admin_user/updatePassword/" . $user[0]->id_user, array('class' => 'form-horizontal', 'role' => 'form', 'id' => 'password_form', 'autocomplete' => 'off')); ?>
                                <div class="form-group" style="display:none;">
                                    <div class="row">
                                        <label
                                            class="col-md-12 control-label text-left sm-text-left">Current password:</label>
                                        <div class="col-md-12">
                                            <input class="form-control" type="password" name="password"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-12 control-label text-left sm-text-left">New password:</label>
                                        <div class="col-md-12">
                                            <input class="form-control" type="password" name="new_password"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <label
                                            class="col-md-12 control-label text-left sm-text-left">Re-type password:</label>
                                        <div class="col-md-12">
                                            <input class="form-control" type="password" name="confirm_password"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 p-l-10">
                                            <button class="btn btn-complete m-b-10" type="submit">Update</button>
                                            <a href="<?= base_url(); ?>admin_user/resetPassword/<?= $user[0]->id_user; ?>"
                                                class="btn m-b-10 btn-need-confirmation"
                                                data-message="Are you sure want to reset this account password?">
                                                Reset Password
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?= form_close(); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- END CARD -->

        </div>
    </div>

    <!-- END PLACE PAGE CONTENT HERE -->
</div>
<!-- END CONTAINER FLUID -->

<script type="text/javascript">
    $(document).ready(function () {
        $('#user_form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: '',
                invalid: 'fa fa-times',
                validating: 'fa-fa-refresh'
            },
            fields: {
                name: {
                    group: '.col-md-9',
                    validators: {
                        notEmpty: {
                            message: 'Name is required and cannot be empty'
                        }
                    }
                },
                email: {
                    group: '.col-md-9',
                    validators: {
                        notEmpty: {
                            message: 'Email is required and cannot be empty'
                        },
                        emailAddress: {
                            message: 'This input is not a valid email address'
                        }
                    }
                },
                contact_number: {
                    group: '.col-md-9',
                    validators: {
                        notEmpty: {
                            message: 'Contact number is required and cannot be empty'
                        }
                    }
                }
            }
        });

        $('#password_form').bootstrapValidator({
            message: 'This value is not valid',
            feedbackIcons: {
                valid: '',
                invalid: 'fa fa-times',
                validating: 'fa-fa-refresh'
            },
            fields: {
                // password: {
                // 	group: '.col-md-12',
                // 	validators: {
                // 		notEmpty: {
                // 			message: 'Current password is required and cannot be empty'
                // 		},
                // 		stringLength: {
                // 			min: 6,
                // 			message: 'Password must be more than 6 characters long'
                // 		}
                // 	}
                // },
                new_password: {
                    group: '.col-md-12',
                    validators: {
                        notEmpty: {
                            message: 'New password is required and cannot be empty'
                        },
                        stringLength: {
                            min: 6,
                            message: 'New password must be more than 6 characters long'
                        },
                        identical: {
                            field: 'confirm_password',
                            message: 'Password and its confirm are not the same'
                        }
                    }
                },
                confirm_password: {
                    group: '.col-md-12',
                    validators: {
                        notEmpty: {
                            message: 'Re-type password for confirmation'
                        },
                        stringLength: {
                            min: 6,
                            message: 'Password confirmation must be more than 6 characters long'
                        },
                        identical: {
                            field: 'new_password',
                            message: 'password confirmation and new password are not the same'
                        }
                    }
                }
            }
        });
    });
</script>