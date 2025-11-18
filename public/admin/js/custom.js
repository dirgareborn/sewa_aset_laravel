$(document).ready(function(){
    $("#current_pwd").keyup(function(){
        var current_pwd = $("#current_pwd").val();
        // alert(current_pwd);
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
            url:'/admin/check-current-password',
            data:{current_pwd:current_pwd},
            success:function(resp){
                if(resp=="false"){
                    $("#verifyCurrentPwd").html("Current Password is Incorrect !");
                }else if(resp=="true"){
                    $("#verifyCurrentPwd").html("Current Password is Correct !");
                }
            },error:function(){
                alert("Error");
            }
        })
    });
    //update status mitra
    $(document).on("click",".updateMitraStatus", function(){
        var status = $(this).children("i").attr("status");
        var mitra_id = $(this).attr("mitra_id");
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
            url:'/admin/update-mitra-status',
            data:{status:status,mitra_id:mitra_id},
            success:function(resp){
                 if(resp['status']==0){
                // Ubah icon
                $("#mitra-"+mitra_id).html("<i class='fas fa-toggle-off text-secondary' status='Inactive'></i>");
                // Ubah badge
                $("#status-"+mitra_id).removeClass('bg-success').addClass('bg-secondary').text('Nonaktif');
            }else if(resp['status']==1){
                // Ubah icon
                $("#mitra-"+mitra_id).html("<i class='fas fa-toggle-on text-success' status='Active'></i>");
                // Ubah badge
                $("#status-"+mitra_id).removeClass('bg-secondary').addClass('bg-success').text('Aktif');
            }
        },
            error:function(){
                alert("Error");
            }
        })
    });

    //update status cms page
    $(document).on("click",".updateCmsPageStatus", function(){
        var status = $(this).children("i").attr("status");
        var page_id = $(this).attr("page_id");
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
            url:'/admin/update-cms-page-status',
            data:{status:status,page_id:page_id},
            success:function(resp){
                if(resp['status']==0){
                    $("#page-"+page_id).html("<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>" )
                }else if(resp['status']==1){
                    $("#page-"+page_id).html("<i class='fas fa-toggle-on' style='color:#007bff' status='Active'></i>" )
                }
            },error:function(){
                alert("Error");
            }
        })
    });
    //update status admin
    $(document).on("click",".updateSubAdminStatus", function(){
        var status = $(this).children("i").attr("status");
        var subadmin_id = $(this).attr("subadmin_id");
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
            url:'/admin/update-subadmin-status',
            data:{status:status,subadmin_id:subadmin_id},
            success:function(resp){
                if(resp['status']==0){
                    $("#subadmin-"+subadmin_id).html("<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>" )
                }else if(resp['status']==1){
                    $("#subadmin-"+subadmin_id).html("<i class='fas fa-toggle-on' style='color:#007bff' status='Active'></i>" )
                }
            },error:function(){
                alert("Error");
            }
        })
    });

    //update status category
    $(document).on("click",".updateCategoryStatus", function(){
        var status = $(this).children("i").attr("status");
        var category_id = $(this).attr("category_id");
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
            url:'/admin/update-category-status',
            data:{status:status,category_id:category_id},
            success:function(resp){
                if(resp['status']==0){
                    $("#category-"+category_id).html("<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>" )
                }else if(resp['status']==1){
                    $("#category-"+category_id).html("<i class='fas fa-toggle-on' style='color:#007bff' status='Active'></i>" )
                }
            },error:function(){
                alert("Error");
            }
        })
    });

    //update status product
    $(document).on("click",".updateProductStatus", function(){
        var status = $(this).children("i").attr("status");
        var product_id = $(this).attr("product_id");
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
            url:'/admin/update-product-status',
            data:{status:status,product_id:product_id},
            success:function(resp){
                if(resp['status']==0){
                    $("#product-"+product_id).html("<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>" )
                }else if(resp['status']==1){
                    $("#product-"+product_id).html("<i class='fas fa-toggle-on' style='color:#007bff' status='Active'></i>" )
                }
            },error:function(){
                alert("Error");
            }
        })
    });

    //update status banner
    $(document).on("click",".updateBannerStatus", function(){
        var status = $(this).children("i").attr("status");
        var banner_id = $(this).attr("banner_id");
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
            url:'/admin/update-banner-status',
            data:{status:status,banner_id:banner_id},
            success:function(resp){
                if(resp['status']==0){
                    $("#banner-"+banner_id).html("<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>" )
                }else if(resp['status']==1){
                    $("#banner-"+banner_id).html("<i class='fas fa-toggle-on' style='color:#007bff' status='Active'></i>" )
                }
            },error:function(){
                alert("Error");
            }
        })
    });
	
	//update akun bank banner
    $(document).on("click",".updateAccountBankStatus", function(){
        var status = $(this).children("i").attr("status");
        var bank_id = $(this).attr("bank_id");
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
            url:'/admin/update-account-bank-status',
            data:{status:status,bank_id:bank_id},
            success:function(resp){
                if(resp['status']==0){
                    $("#bank-"+bank_id).html("<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>" )
                }else if(resp['status']==1){
                    $("#bank-"+bank_id).html("<i class='fas fa-toggle-on' style='color:#007bff' status='Active'></i>" )
                }
            },error:function(){
                alert("Error");
            }
        })
    });
	
	//update status coupon
    $(document).on("click",".updateCouponStatus", function(){
        var status = $(this).children("i").attr("status");
        var coupon_id = $(this).attr("coupon_id");
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type:'post',
            url:'/admin/update-coupon-status',
            data:{status:status,coupon_id:coupon_id},
            success:function(resp){
                if(resp['status']==0){
                    $("#coupon-"+coupon_id).html("<i class='fas fa-toggle-off' style='color:grey' status='Inactive'></i>" )
                }else if(resp['status']==1){
                    $("#coupon-"+coupon_id).html("<i class='fas fa-toggle-on' style='color:#007bff' status='Active'></i>" )
                }
            },error:function(){
                alert("Error");
            }
        })
    });
	// Coupon Option Show/Hide
	$("#ManualCoupon").click(function(){
		$("#couponField").show();
	});
	$("#AutomaticCoupon").click(function(){
		$("#couponField").hide();
	});
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    $(function () {
        // Summernote
        $('#summernote').summernote();
		$('#product_description').summernote();
		
    
        // CodeMirror
        CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
        mode: "htmlmixed",
        theme: "monokai"
        });
    });
    
});
