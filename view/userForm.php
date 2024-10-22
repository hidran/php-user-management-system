<?php
?>
<form action="controller/updateRecord.php" method="post">
    <div class="row mb-3">
        <label for="username" class="col-form-label text-end form-label col-sm-4">User name </label>
        <div class="col-sm-8">
            <input id="username" class="form-control" name="username">
        </div>
    </div>
    <div class="row mb-3">
        <label for="email" class="col-form-label text-end form-label col-sm-4">Email </label>
        <div class="col-sm-8">
            <input id="email" type="email" class="form-control" name="email">
        </div>
    </div>
    <div class="row mb-3">
        <label for="fiscalcode" class="col-form-label form-label text-end  col-sm-4">Fiscal code </label>
        <div class="col-sm-8">
            <input id="fiscalcode" class=" form-control" name="fiscalcode">
        </div>
    </div>
    <div class="row  mb-3">
        <label for="age" class="col-form-label text-end form-label col-sm-4">Age </label>
        <div class="col-sm-8">
            <input id="age" class="form-control" name="age">
        </div>
    </div>
    </div>
    <div class="row mt-5 d-flex justify-content-center align-items-sm-center">
        <div class="col-sm-3"></div>
        <div class="col-sm-6 offset-sm-4">
            <button type="submit" class="btn btn-primary">UPDATE</button>

            <a href="index.php" class="btn btn-secondary">Back to users</a>
            <a href="controller/updateUser.php?action=delete&id=<?= $user['id'] ?>"
                class="btn btn-danger" onclick="return confirm('Are you sure?')">Elimina</a>

        </div>
        <div class="col-sm-3"></div>
    </div>
</form>