<?php
$action = 'store';
$buttonName = 'SAVE';
$formTile = 'INSERT USER';
if ($user && $user['id']) {
    $action = 'update';
    $buttonName = 'UPDATE';
    $formTile = 'UPDATE USER';
}
foreach ($user as &$value) {

    $value = htmlspecialchars($value ?? '');
}
?>

<form enctype="multipart/form-data" class="mt-4" action="controller/updateRecord.php" method="post">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">
    <input type="hidden" name="action" value="<?= $action ?>">
    <h2><?= $formTile ?></h2>
    <div class="row mb-3">
        <label for="username" class="col-form-label text-end form-label col-sm-4">User name </label>
        <div class="col-sm-8">
            <input id="username" value="<?= $user['username'] ?>" class="form-control" name="username">
        </div>
    </div>
    <div class="row mb-3">
        <label for="email" class="col-form-label text-end form-label col-sm-4">Email </label>
        <div class="col-sm-8">
            <input id="email" type="email" value="<?= $user['email'] ?>" class="form-control" name="email">
        </div>
    </div>
    <div class="row mb-3">
        <label for="fiscalcode" class="col-form-label form-label text-end  col-sm-4">Fiscal code </label>
        <div class="col-sm-8">
            <input id="fiscalcode" class="form-control" value=" <?= $user['fiscalcode'] ?>" name="fiscalcode">
        </div>
    </div>
    <div class="row  mb-3">
        <label for="age" class="col-form-label text-end form-label col-sm-4">Age </label>
        <div class="col-sm-8">
            <input id="age" class="form-control" value="<?= $user['age'] ?>" name="age">
        </div>
    </div>
    <div class="row  mb-3">
        <label for="avatar" class="col-form-label text-end form-label col-sm-4">Avatar </label>
        <div class="col-sm-8">
            <input type="hidden" name="oldAvatar" value="<?= $user['avatar'] ?>">

            <input type="hidden" name="MAX_FILE_SIZE" value="<?= getConfig('maxFileSize') ?>">
            <input type="file" accept="<?= implode(',', getConfig('mimeTypes')) ?>" id="avatar" class="form-control" value="<?= $user['avatar'] ?>" name="avatar">
        </div>

        <div class="row alert alert-info col-sm-8 offset-md-4 mt-3">
            <p>Image types : <?= implode(',', getConfig('mimeTypes')) ?>,
                Max file size: <?= formatBytes(getConfig('maxFileSize')) ?></p>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-8 offset-sm-4">
            <?php

            $fileData = getImgThumbNail($user['avatar'], 'm');
            $avatar = $fileData['avatar'];

            ?>
            <img id="preview" src="<?= $fileData['avatar'] ? htmlspecialchars($fileData['avatar']) : '' ?>"
                style="width:<?= $fileData['width'] ?>px;<?= $fileData['avatar'] ? '' : 'display:none' ?>">

        </div>
    </div>
    <div class="row mt-5 d-flex justify-content-center align-items-sm-center">
        <div class="col-sm-3"></div>
        <div class="col-sm-6 offset-sm-4">
            <button type="submit" class="btn btn-primary"><?= $buttonName ?></button>

            <a href="index.php" class="btn btn-secondary">Back to users</a>
            <?php if ($action === 'update') { ?>
                <a href="controller/updateRecord.php?action=delete&id=<?= $user['id'] ?>"
                    class="btn btn-danger" onclick="return confirm('Are you sure?')">DELETE</a>
            <?php } ?>

        </div>
        <div class="col-sm-3"></div>
    </div>
</form>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const avatar = '<?= $avatar ?>';
        const avatarInput = document.getElementById('avatar');
        const preview = document.getElementById('preview');
        avatarInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = '';
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = avatar;
            }
        });
    });
</script>