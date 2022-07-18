<!-- Section category title -->
<section class="block bg-lighter p-b0 pt-30 mt-40">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h2 class="block-title block-title-secondary heading-black m-b-0 m-t-20">
          <span class="title-angle-shap"> <?= $contactsetting[0]->contact_title; ?></span>
        </h2>
      </div>
    </div><!-- row end -->
  </div>
</section>
<!-- section end -->

<!-- Section category article -->
<section class="block">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-7 col-md-7 align-self-top">
        <div class="row">
          <div class="col-md-12 m-b-30">
            <?php if (isset($contactsetting[0]->contact_desc) && strlen(trim($contactsetting[0]->contact_desc)) > 0) { ?>
              <p class="subscribe-text">
                <?= nl2br($contactsetting[0]->contact_desc); ?>
              </p>
            <?php } ?>

            <?= $this->session->flashdata('message'); ?>

            <?= form_open('contact/submitContact/' . $contactsetting[0]->id_contactsetting, array('class' => 'form-hosirzontal', 'id' => 'contact-form')); ?>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Nama:</label>
                    <input class="form-control form-control-name" name="name" id="name" placeholder="" type="text" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Email:</label>
                    <input class="form-control form-control-email" name="email" id="email" placeholder="" type="email" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Nomor kontak:</label>
                    <input class="form-control form-control" name="phone" placeholder="" type="text">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label>Pesan:</label>
                    <textarea class="form-control form-control-message" name="message" id="message" placeholder="" rows="5" required></textarea>
                  </div>
                </div>
              </div>

            <?= $this->recaptcha->render(); ?>
            <div class="newsletter-area mt-20">
              <div class="email-form-group">
                <button class="newsletter-submit pt-0" type="submit">Kirim pesan</button>
              </div>
            </div>
            <?= form_close(); ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- section end -->