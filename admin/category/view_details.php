<?php 
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `category_list` where id = '{$_GET['id']}' and delete_flag = 0 ");
    if($qry->num_rows > 0 ){
        foreach($qry->fetch_array() as $k => $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<section class="py-4">
    <div class="container">
        <dl>
            <dt>Nama Kategori</dt>
            <dd class="ps-4"><?= isset($name) ? $name : "" ?></dd>
            <dt>Deskripsi Singkat</dt>
            <dd class="ps-4"><?= isset($description) ? $description : "" ?></dd>
            <dt>Status</dt>
            <dd class="ps-4">
                <?php 
                if(isset($status)):
                    if($status == 1):
                        echo '<span class="badge bg-primary bg-gradient px-3 rounded-pill">Aktif</span>' ;
                    else:
                        echo '<span class="badge bg-secondary bg-gradient px-3 rounded-pill">Non Aktif</span>' ;
                    endif;
                endif;
                ?>    
            </dd>
        </dl>
        <div class="text-end pt-3">
            <a href=".?page=category/manage_category&id=<?= isset($id) ? $id : '' ?>" class="btn btn-primary bg-gradient btn-sm"><span class="material-icons">edit</span> Rubah</a>
            <a href="javascript:void(0)" class="btn btn-danger bg-gradient btn-sm" id="delete_data"><span class="material-icons">delete</span> Hapus</a>
            <a href="./?page=category" class="btn btn-light border btn-sm"><span class="material-icons">arrow_back_ios</span> Kembali Ke List</a>
        </div>
    </div>
</section>
<script>
    $(function(){
        $('#delete_data').click(function(){
            _conf("Apakah kamu yakin ingin menghapus kategori?","delete_category",['<?= isset($id) ? $id : '' ?>'])
        })
    })
    function delete_category($id){
        start_loader();
        var _this = $(this)
        $('.err-msg').remove();
        var el = $('<div>')
        el.addClass("alert alert-danger err-msg")
        el.hide()
        $.ajax({
            url: '../classes/Master.php?f=delete_category',
            method: 'POST',
            data: {
                id: $id
            },
            dataType: 'json',
            error: err => {
                console.log(err)
                el.text('An error occurred.')
                el.show('slow')
                end_loader()
            },
            success: function(resp) {
                if (resp.status == 'success') {
                    location.replace('./?page=category')
                } else if (!!resp.msg) {
                    el.text('An error occurred.')
                    el.show('slow')
                } else {
                    el.text('An error occurred.')
                    el.show('slow')
                }
                end_loader()
            }
        })
    }
</script>