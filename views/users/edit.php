<div class="card">
    <div class="card-body">
        <div class="container">
        <form method="POST" action="/update-user?id=<?= $user['id'] ?>">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="role_id" class="form-label">Role:</label>
                    <select name="role_id" class="form-control">
                        <option value="">Select a role</option>
                        <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id'] ?>" <?= $role['id'] == $user['role_id'] ? 'selected' : '' ?>>
                                <?= $role['role_name'] ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Save</button>
                <a href="/list-users" class="btn btn-secondary mt-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
