<?php 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `users` where id = '{$_GET['id']}'");
    if($qry->num_rows > 0 ){
        foreach($qry->fetch_array() as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<style>
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<section class="py-5">
    <div class="container">
        <h2 class="fw-bolder text-center"><b><?= isset($id) ? "Edit User" : "Tambah Penjual Baru" ?></b></h2>
        <hr>
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12 col-xs-12">
                <form action="" id="user-form" class="py-3">
                    <input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">
                    <div class="input-group mb-3 input-group-dynamic <?= isset($firstname) ? 'is-filled' : '' ?>">
                        <label for="firstname" class="form-label">Nama Depan <span class="text-primary">*</span></label>
                        <input type="text" id="firstname" name="firstname" autofocus value="<?= isset($firstname) ? $firstname : "" ?>" class="form-control">
                    </div>
                    <div class="input-group mb-3 input-group-dynamic <?= isset($middlename) ? 'is-filled' : '' ?>">
                        <label for="middlename" class="form-label">Nama Tengah</label>
                        <input type="text" id="middlename" name="middlename" value="<?= isset($middlename) ? $middlename : "" ?>" class="form-control">
                    </div>
                    <div class="input-group mb-3 input-group-dynamic <?= isset($lastname) ? 'is-filled' : '' ?>">
                        <label for="lastname" class="form-label">Nama Akhir <span class="text-primary">*</span></label>
                        <input type="text" id="lastname" name="lastname" value="<?= isset($lastname) ? $lastname : "" ?>" class="form-control">
                    </div>
                    <div class="input-group mb-3 input-group-dynamic <?= isset($username) ? 'is-filled' : '' ?>">
                        <label for="username" class="form-label">Username <span class="text-primary">*</span></label>
                        <input type="text" id="username" name="username" value="<?= isset($username) ? $username : "" ?>" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="type" class="form-label">Tipe <span class="text-primary">*</span></label>
                        <select name="type" id="type" class="form-select rounded-0" required>
                            <option class="px-2 py-2" value="1" <?= isset($type) && $type == 1 ? 'selected': '' ?>>Admin</option>
                            <option class="px-2 py-2" value="2" <?= isset($type) && $type == 2 ? 'selected': '' ?>>Penjual</option>
                        </select>
                    </div>
                    <div class="form-group mb-3 input-group input-group-dynamic is-filled">
                        <label for="" class="form-label mb-2">Foto Profil</label>
                        <input type="file" class="px-2" id="customFile" name="img" onchange="displayImg(this,$(this))">
                    </div>
                    <div class="form-group mb-3 d-flex justify-content-center">
                        <img src="<?php echo validate_image(isset($avatar) ? $avatar : '') ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn bg-primary bg-gradient btn-sm text-light w-25"><span class="material-icons">save</span> Simpan</button>
                            <a href="./?page=users" class="btn bg-deafult border bg-gradient btn-sm w-25"><span class="material-icons">keyboard_arrow_left</span> Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    var fuser_ajax;
    function displayImg(input,_this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#cimg').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }else{
            $('#cimg').attr('src', '<?php echo validate_image(isset($avatar) ? $avatar : '') ?>');
        }
    }
    $(function(){
        $('#club_id').select2({
            placeholder:"Please Select Here",
            width:"100%",
        })
        $('#type').change(function(){
            var type = $(this).val()
            if(type == 1){
                $('#club-field').addClass('d-none')
                $('#club_id').removeAttr('required')
            }else{
                $('#club-field').removeClass('d-none')
                $('#club_id').attr('required',true)
            }
        })
        
       
        $('#user-form').submit(function(e){
            e.preventDefault()
            $('.pop-alert').remove()
            var _this = $(this)
            var el = $('<div>')
            el.addClass("pop-alert alert alert-danger text-light")
            el.hide()
            if($('[name="to_user"]').val() == ''){
                el.text('Recepient is required.')
                _this.prepend(el)
                el.show('slow')
                $('html, body').scrollTop(_this.offset().top - '150')
                return false;
            }
            start_loader()
            $.ajax({
                url:'../classes/Master.php?f=save_user',
                type:'POST',
                method:'POST',
                cache:false,
                contentType:false,
                processData:false,
                data:new FormData(_this[0]),
                dataType:'json',
                error:err=>{
                    console.error(err)
                    el.text("An error occured while saving data")
                    _this.prepend(el)
                    el.show('slow')
                    $('html, body').scrollTop(_this.offset().top - '150')
                    end_loader()
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        location.href= './?page=users/view_details&id='+resp.uid;
                    }else if(!!resp.msg){
                        el.text(resp.msg)
                        _this.prepend(el)
                        el.show('slow')
                        $('html, body').scrollTop(_this.offset().top - '150')
                    }else{
                        el.text("An error occured while saving data")
                        _this.prepend(el)
                        el.show('slow')
                        $('html, body').scrollTop(_this.offset().top - '150')
                    }
                    end_loader()
                    console

                }
            })
        })

    })
</script>