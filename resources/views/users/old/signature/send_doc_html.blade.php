<style>
	.rec_input {
		margin-left: 9px !important
	}

	.recp_first {
		max-height: 25px !important;
	}

	.rec_width {
		width: 28.077%;
	}

	.row-fluid .span3 {
		width: 28%;
	}

	.roles-recep-new-row .form-control {
		width: 61.892% !important;
	}

	.roles-recep-new-row .roles-recep-new-link {
		right: unset;
	}

	.btn.btn-primary.btn_send_attach_resp {
		margin-left: 10px;
	}
</style>
<div class="clearfix"></div>
<form name="add_recipes" id="edit_recipes">
	<div id="recep_controls">
		<div id="recep_div" class="recep-roles">
			<div class="controls rows-disp OrderingField">
				<div class="span7 rec_input rec-field">
					<input type="text" class="form-control recep_email_txt recp_first" id="recep_txt_email_2" name="recep_email_txt[]" placeholder="Recipient`s Email" value="">
					<a href="#" id="add_recep1" class="btn action-btn"><i class="icon-white icon-plus-sign"></i>Add More Recipient</a>
				</div>
			</div>
		</div>
	</div>
</form>
<div class="clearfix"></div>
<div class="btn-right text-right">
	<a href="#" class="btn action-btn btn_send_attach_resp btn-primary" id="{{$assign_id}}">Submit</a>
</div>



<div id="rolecount" class="custom_template">
    <div id="recep_inputbox_${totRecepCount}" class="controls rows-disp hide">
        <input type="text" class="form-control span4 ${roleclassname}" id="role_txt_name_${totRecepCount}" name="role_txt[]" placeholder="pholderManagerOrClientEtc" value="">
        <a title="Remove" class="link ct_recep_remove" href="#" data-id="${totRecepCount}">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
			<g opacity="0.8" clip-path="url(#clip0_241_3568)">
			<path d="M10 0C15.5142 0 20 4.48578 20 10C20 15.5142 15.5142 20 10 20C4.48578 20 0 15.5142 0 10C0 4.48578 4.48578 0 10 0Z" fill="#F44336"/>
			<path d="M6.31731 12.5041C5.99154 12.8301 5.99154 13.3566 6.31731 13.6826C6.47982 13.8451 6.69314 13.9267 6.90661 13.9267C7.11993 13.9267 7.33324 13.8451 7.49575 13.6826L9.99987 11.1783L12.504 13.6826C12.6665 13.8451 12.8798 13.9267 13.0931 13.9267C13.3066 13.9267 13.5199 13.8451 13.6824 13.6826C14.0082 13.3566 14.0082 12.8301 13.6824 12.5041L11.1782 10L13.6824 7.49589C14.0082 7.16996 14.0082 6.64338 13.6824 6.31745C13.3565 5.99168 12.8299 5.99168 12.504 6.31745L9.99987 8.82172L7.49575 6.31745C7.16982 5.99168 6.64324 5.99168 6.31731 6.31745C5.99154 6.64338 5.99154 7.16996 6.31731 7.49589L8.82159 10L6.31731 12.5041Z" fill="#FAFAFA"/>
			</g>
			<defs>
			<clipPath id="clip0_241_3568">
			<rect width="20" height="20" fill="white" transform="matrix(-1 0 0 1 20 0)"/>
			</clipPath>
			</defs>
			</svg>
        </a>
    </div>
</div>

<div id="recepcount" class="custom_template">

    <div id="recep_inputbox_${totRecepCount}" class="controls rows-disp hide roles-recep-new-row OrderingField">
        <input type="text" class="form-control span4 ${recepclassemail}" id="recep_txt_email_${totRecepCount}" name="recep_email_txt[]" placeholder="Recipient`s Email" value="">
        <a title="Remove" class="link ct_recep_remove roles-recep-new-link" href="#" data-id="${totRecepCount}">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
				<g opacity="0.8" clip-path="url(#clip0_241_3568)">
				<path d="M10 0C15.5142 0 20 4.48578 20 10C20 15.5142 15.5142 20 10 20C4.48578 20 0 15.5142 0 10C0 4.48578 4.48578 0 10 0Z" fill="#F44336"/>
				<path d="M6.31731 12.5041C5.99154 12.8301 5.99154 13.3566 6.31731 13.6826C6.47982 13.8451 6.69314 13.9267 6.90661 13.9267C7.11993 13.9267 7.33324 13.8451 7.49575 13.6826L9.99987 11.1783L12.504 13.6826C12.6665 13.8451 12.8798 13.9267 13.0931 13.9267C13.3066 13.9267 13.5199 13.8451 13.6824 13.6826C14.0082 13.3566 14.0082 12.8301 13.6824 12.5041L11.1782 10L13.6824 7.49589C14.0082 7.16996 14.0082 6.64338 13.6824 6.31745C13.3565 5.99168 12.8299 5.99168 12.504 6.31745L9.99987 8.82172L7.49575 6.31745C7.16982 5.99168 6.64324 5.99168 6.31731 6.31745C5.99154 6.64338 5.99154 7.16996 6.31731 7.49589L8.82159 10L6.31731 12.5041Z" fill="#FAFAFA"/>
				</g>
				<defs>
				<clipPath id="clip0_241_3568">
				<rect width="20" height="20" fill="white" transform="matrix(-1 0 0 1 20 0)"/>
				</clipPath>
				</defs>
				</svg>

        </a>

    </div>

</div><script>
	$("#add_recep1").click(function(e) {
		//alert('here in add');
		var limit = '2';
		e.preventDefault();
		baseDocObj.addReceps(limit);
	});
	$(".btn_send_attach_resp").click(function(e) {
		var t = $(this).attr("id");
		// alert(t);
		e.preventDefault();
		baseDocObj.sendAttachment(t);
	});
</script>