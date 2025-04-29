<section class="h-100 gradient-form" style="background-color: #eee;">
  <div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-xl-10">
        <div class="card rounded-3 text-black">
          <div class="row g-0">
            <div class="col-lg-6">
              <div class="card-body p-md-5 mx-md-4">
                <div class="text-center"> 
                  <img src="/views/assets/images/logo.png" style="width: 185px;" alt="logo"> 
                </div>
                <form method="POST" id="loginForm" class="needs-validation" novalidate>
                  <p class="text-center"><?php echo __('please_login'); ?></p>
                  <div class="form-outline mb-2"> 
                    <label class="form-label" for="form2Example11"><?php echo __('email'); ?> <span style="color: red;">*</span></label> 
                    <input 
                      type="email" 
                      id="form2Example11" 
                      class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" 
                      name="email" 
                      placeholder="<?php echo __('email_placeholder'); ?>" 
                      value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                      required 
                    />
                    <div class="invalid-feedback">
                      <?php echo isset($errors['email']) ? __($errors['email']) : __('email_required'); ?>
                    </div>
                  </div>
                  <div class="form-outline mb-2 position-relative"> 
                    <label class="form-label" for="form2Example22"><?php echo __('password'); ?><span style="color: red;">*</span></label> 
                    <input 
                      type="password" 
                      name="password" 
                      id="password" 
                      class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" 
                      placeholder="<?php echo __('password_placeholder'); ?>" 
                      required 
                    />
                    <!-- Eye icon for toggling password visibility -->
                    <i class="fa fa-eye position-absolute toggle-password" id="togglePassword" style="cursor: pointer; right: 10px;  z-index: 10;"></i>
                    <div class="invalid-feedback">
                      <?php echo isset($errors['password']) ? __($errors['password']) : __('password_required'); ?>
                    </div>
                  </div>
                  <div class="text-center pt-1 mb-4 pb-1"> 
                    <button class="btn text-light fa-lg gradient-custom-2 mb-2" type="submit"><?php echo __('login_button'); ?></button><br> 
                    <a class="text-muted" href="#!"><?php echo __('forgot_password'); ?></a> 
                  </div>
                  <!-- <p class="text-center"><a href="/login/register"><?php echo __('register_prompt'); ?></a></p> -->
                </form>
              </div>
            </div>
            <div class="col-lg-6 d-flex align-items-center gradient-custom-2" style="background-image: url(views/assets/images/cofe.png)">
              <div class="text-white px-3 py-4 p-md-5 mx-md-4" style="background: #421f128d;">
                <h4 class="mb-4"><?php echo __('login_heading'); ?></h4>
                <p class="small mb-0"><?php echo __('login_description'); ?></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Add validation messages for JavaScript -->
<script>
// Create a global validation messages object
window.validationMessages = {
    'email_required': '<?php echo __('email_required'); ?>',
    'password_required': '<?php echo __('password_required'); ?>',
    'field_required': '<?php echo __('field_required'); ?>',
    'valid_email': '<?php echo __('valid_email'); ?>',
    'name_required': '<?php echo __('name_required'); ?>',
    'role_required': '<?php echo __('role_required'); ?>',
    'password_length': '<?php echo __('password_length'); ?>',
    'confirm_password': '<?php echo __('confirm_password'); ?>',
    'passwords_not_match': '<?php echo __('passwords_not_match'); ?>'
};
</script>

<!-- Include the validation script -->
<script src="/views/assets/js/fp.js"></script>