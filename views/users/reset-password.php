<!-- Reset Password Modal -->
<div class="modal fade" id="resetPassword<?= $user['id'] ?>" tabindex="-1" aria-labelledby="resetPasswordLabel<?= $user['id'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordLabel<?= $user['id'] ?>">Change Password for <?= htmlspecialchars($user['name']) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/resetpassword?id=<?= $user['id'] ?>" method="POST" id="resetForm<?= $user['id'] ?>" class="needs-validation reset-password-form" novalidate>
                <div class="modal-body">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">
                    <div class="form-outline mb-3 position-relative">
                        <label class="form-label" for="password">New Password <span class="star">*</span></label>
                        <i class="fa fa-eye position-absolute toggle-password" id="togglePassword" style="cursor: pointer; right: 10px; transform: translateY(-50%);"></i>
                        <input type="password" name="password" id="password" class="form-control password-field"
                            placeholder="Create a password" required minlength="8" />
                        <div class="invalid-feedback">
                            Password must be at least 8 characters long.
                        </div>
                    </div>
                    <div class="form-outline mb-3 position-relative">
                        <label class="form-label" for="confirm_password">Confirm Password <span class="star">*</span></label>
                        <i class="fa fa-eye position-absolute toggle-password" id="toggleConfirmPassword" style="cursor: pointer; right: 10px; transform: translateY(-50%);"></i>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control confirm-password-field"
                            placeholder="Confirm your password" required />
                        <div class="invalid-feedback">
                            Passwords do not match.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>