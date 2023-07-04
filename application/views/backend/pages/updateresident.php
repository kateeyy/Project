

<!-- Main Content -->
<div id="content">
<div class="container">
    <form method="post" action="<?=base_url('index.php/dashboard/update-resident/'.$resident_id)?>">
    <div class="form-group">
        <label> First Name </label>
        <input type="text" value="<?=$resident->first_name?>" name="firstname" class="form-control"/>
        <span><?= form_error('firstname') ?></span>
</div>

<div class="form-group">
        <label> Last Name </label>
        <input type="text" value="<?=$resident->last_name?>" name="lastname" class="form-control"/>
        <span><?= form_error('lastname') ?></span>
</div>

<div class="form-group">
        <label> Birth Date </label>
        <input type="date" value="<?=$resident->birth_date?>" name="birth_date" class="form-control"/>
        <span><?= form_error('birth_date') ?></span>
</div>
<div class="form-group">
    <button class="btn btn-primary"> Update Resident </button>
</div>
</form>
</div>
</div>