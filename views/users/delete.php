<div class="modal fade" id="user<?= $user['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo __('delete_user'); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php echo __('confirm_delete_user'); ?>
            </div>
            <div class="modal-footer">
                <form action="/users/delete?id=<?= $user['id'] ?>" method="POST">
                    <button type="submit" class="btn btn-danger"><?php echo __('delete'); ?></button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?php echo __('discard'); ?></button>
                </form>
            </div>
        </div>
    </div>
</div>