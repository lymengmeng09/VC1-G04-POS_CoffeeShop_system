<!-- Reset Password Modal -->
<div class="modal fade" id="resetPassword<?= $user['id'] ?>" tabindex="-1" aria-labelledby="resetPasswordLabel<?= $user['id'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordLabel<?= $user['id'] ?>"><?php echo __('change_password_for'); ?> <?= htmlspecialchars($user['name']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/resetpassword?id=<?= $user['id'] ?>" method="POST" id="resetForm<?= $user['id'] ?>" class="needs-validation reset-password-form" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
                    <div class="form-outline mb-3 position-relative">
                        <label class="form-label" for="password"><?php echo __('new_password'); ?> <span class="star">*</span></label>
                        <i class="bi-eye-fill position-absolute toggle-password" style="cursor: pointer; right: 10px;"></i>
                        <input type="password" name="password" id="password" class="form-control password-field"
                            placeholder="<?php echo __('create_password_placeholder'); ?>" required minlength="8" />
                        <div class="invalid-feedback">
                            <?php echo __('password_length'); ?>
                        </div>
                    </div>
                    <div class="form-outline mb-3 position-relative">
                        <label class="form-label" for="confirm_password"><?php echo __('confirm_password_label'); ?> <span class="star">*</span></label>
                        <i class="bi-eye-fill position-absolute toggle-password" style="cursor: pointer; right: 10px;"></i>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control confirm-password-field"
                            placeholder="<?php echo __('confirm_password_placeholder'); ?>" required />
                        <div class="invalid-feedback">
                            <?php echo __('passwords_not_match'); ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?php echo __('cancel'); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo __('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
