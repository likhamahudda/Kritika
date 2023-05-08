function prepareDoc(options) {
    var getVal = $("#zoomSelect").val();
  
    // Default Values
    (this.defaults = {
      site_url: SITE_URL,
      sel_show: "role_name",
      div_name_diff: 20,
      div_min_h: 40,
      div_min_w: 30,
      div_max_h: 500,
      div_max_w: 500,
      checkbox_img: THEME_BASE + "/img/icons/checkbox_icon.png",
      conatiner_margin: 0,
      doc_width: 680 * getVal,
      doc_height: 880 * getVal,
    }),
      (this.defaults = $.extend(this.defaults, options));
  
    var _this = this; // _this is very effective when running under some function
    this.tool_comp_c = 1;
    this.curr_tl_comp = "";
    this.template_data = "";
    this.current_select = "";
    this.data_arr = [];
    this.date = "";
    this.saved_pre_doc = [];
    this.total_rows = 0;
    this.request = request;
    this.user_key = user_key;
    this.called_datac = 0;
    this.total_req = 0;
    this.current_label = "";
    this.defaults.sign_url = this.defaults.site_url + "uploads/signature/";
    this.copy_sign_all_place = "";
    var sign_types = {
      title: "",
      advance: 0,
      name: "",
      inside_text: "",
      width: 0,
      resize: 1,
      set_min_w: _this.defaults.div_min_w,
      set_min_h: _this.defaults.div_min_h,
      set_max_w: _this.defaults.div_max_w,
      set_max_h: _this.defaults.div_max_h,
    };
    var baseDocObj = new doc_basic_fn();
    this.timestamp = function () {
      return new Date().getTime();
    };
  
    this.init = function (set_docdata) {
      //this.tem_type this set from the click on the thumbnails of me, me & others & others;
      this.set_docdata = set_docdata;
      var date = new Date();
      this.date =
        date.getMonth() + 1 + " / " + date.getDate() + " / " + date.getFullYear();
  
      if (typeof user_date_format != "undefined") {
        if (user_date_format == "DD / MM /YYYY")
          this.date =
            date.getDate() +
            " / " +
            (date.getMonth() + 1) +
            " / " +
            date.getFullYear();
        else
          this.date =
            date.getMonth() +
            1 +
            " / " +
            date.getDate() +
            " / " +
            date.getFullYear();
      }
      this.sign_type = "";
      this.loader();
      this.draw();
    };
  
    this.progress_bar = function () {
      var total = $(".sign_tl_icon").length;
      var total_done = $(".sign_tl_icon.req_done").length;
  
      var width = (total_done / this.total_req) * 100;
  
      if (width == 100) $(".agree-info-bubble").removeClass("hide");
      else $(".agree-info-bubble").addClass("hide");
      $(".sign-progress-bar .progress-count").html(
        total_done + "/" + this.total_req
      );
  
      $(".sign-progress-bar .progress-bar-success").css({ width: width + "%" });
    };
  
    this.loader = function () {
      var load_count = 0;
      $(".doc_container").hide();
      var tot_l = $(".doc_page_img").length;
      $(".image-load-progress .load-sr-only").html("0/" + tot_l);
      $(".image-load-progress .progress-bar").css({ width: "0%" });
      $("#doc_loader").removeClass("displaynonehard");
      $(".doc_page_img").each(function () {
        var src = $(this).attr("data-src");
        $(this).attr("src", src);
      });
      $(".doc_page_img").each(function () {
        $(this).load(function () {
          load_count++;
          var width = (load_count / tot_l) * 100;
          $("#doc_loader .load-sr-only").html(
            load_count + "/" + tot_l + " pages"
          );
          $("#doc_loader .progress-bar").css({ width: width + "%" });
          if (load_count == tot_l) {
            $(".doc_container").show();
            $("#doc_loader").addClass("displaynonehard");
            $("#iAgreeButton").removeAttr("disabled");
            $("#iDecline").removeAttr("disabled");
            //---status update at the end of page load----//
            $.ajax({
              url: SITE_URL + "request/updateSigningRequestStatus",
              type: "post",
              dataType: "json",
              data: { request: request },
              success: function (result) {
                //console.log('succeeded')
              },
            });
          }
          //                else
          //                {
          //                    $("#doc_loader").removeClass("displaynonehard");
          //                }
        });
      }); 
    };
  
    this.draw = function (x, y) {
      var appender = ".main_doc_container";
      // console.log("called");
      for (var i in this.set_docdata) {
        var thisObjS = this.set_docdata[i];
  
        $(".sign-progress-bar .progress-count").html("0/" + thisObjS.length);
        var tot_req = 0;
        for (var j in thisObjS) {
          var thisObj = thisObjS[j];
          //if (thisObj.dropdown_value == "me_now") continue;
          this.tool_comp_c = thisObj.div_id;
          this.curr_tl_comp = "tl_" + thisObj.field_type;
          this.current_label = thisObj.field_label;
          this.ct_signature();
          this.template_data.appendTo(appender);
  
          var cords = [{ x: thisObj.position.left, y: thisObj.position.top }];
          sessionStorage.setItem(j, JSON.stringify(cords));
  
          $("#tool_component_" + this.tool_comp_c + " .remove").remove();
          $("#tool_component_" + this.tool_comp_c).css({
            left: thisObj.position.left,
            top: thisObj.position.top,
            width: thisObj.extra_options.width,
            height: thisObj.extra_options.height,
          });
          $("#tool_component_" + this.tool_comp_c + " .description").css({
            width: thisObj.extra_options.width,
            height: thisObj.extra_options.height,
            "line-height": thisObj.extra_options.height + "px",
          });
          $("#tool_component_" + this.tool_comp_c + " .handle-wrapper").attr(
            "classname",
            sign_types.name
          );
          $("#tool_component_" + this.tool_comp_c).addClass(sign_types.name);
          var arr_id = thisObj.div_id.split("_")[1];
  
          this.data_arr[arr_id] = [];
          this.data_arr[arr_id].extra_options = thisObj.extra_options;
          this.data_arr[arr_id].position = thisObj.position;
          this.data_arr[arr_id].required = thisObj.required;
          if (thisObj.required == 1) {
            tot_req++;
            $("#tool_component_" + this.tool_comp_c).addClass(
              "component_sign_req required ui-draggable"
            );
          }
        }
        this.total_req = tot_req;
        $(".sign-progress-bar .progress-count").html("0/" + tot_req);
      }
    };
  
    this.ct_signature = function () {
      this.get_types_build(this.curr_tl_comp);
      var templateData = {
        compCount: this.tool_comp_c,
        compTitle: sign_types.title,
        compText: sign_types.text,
        compAdvOpt: "",
      };
      this.template_data = baseDocObj.template("#tool_sign_tmp", templateData, 1);
    };
  
    this.get_types_build = function (current_tool) {
      var getVal = $("#zoomSelect").val();
      switch (current_tool) {
        case "tl_sign":
          sign_types.title = "Click to sign";
          sign_types.text = "Click to sign";
          sign_types.advance = 0;
          sign_types.name = "cc_sign";
          sign_types.inside_text = "signature";
          sign_types.width = 100;
          sign_types.resize = 1;
          sign_types.set_min_w = _this.defaults.div_min_w;
          sign_types.set_min_h = _this.defaults.div_min_h;
          sign_types.set_max_w = _this.defaults.div_max_w;
          sign_types.set_max_h = _this.defaults.div_max_h;
          break;
  
        case "tl_stamp":
          $("#rightSideBar").show();
          // $("#tooltipbox").val('Stamp');
          $("#rightSidebarHeading").html(
            '<svg width="16" height="16" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve"><path d="M54.614,39H39v-6h-4v-6.242c0-2.138,0.922-4.146,2.466-5.374C40.348,19.095,42,15.674,42,12c0-6.616-5.383-12-12-12 c-0.466 0-0.938,0.027-1.406,0.081c-5.353,0.609-9.761,4.922-10.481,10.254c-0.57,4.224,1.057,8.333,4.353,10.993 C24.052,22.609,25,24.708,25,26.941V33h-4v6H5.386C4.07,39,3,40.07,3,41.386v11.229C3,53.93,4.07,55,5.386,55H6.09 c0.478,2.833,2.942,5,5.91,5h36c2.967,0,5.431-2.167,5.91-5h0.705C55.93,55,57,53.93,57,52.614V41.386C57,40.07,55.93,39,54.614,39z M23,35h2h10h2v4H23V35z M48,58H12c-1.86,0-3.429-1.276-3.873-3h43.746C51.429,56.724,49.86,58,48,58z"/><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g></svg> Stamp'
          );
          sign_types.title = "Who stamp here?";
          sign_types.advance = 0;
          sign_types.name = "cc_stamp";
          sign_types.inside_text = "Stamp";
          sign_types.width = 100 * getVal;
          sign_types.resize = 1;
          sign_types.is_dynamic = 0;
          sign_types.set_min_w = _this.defaults.div_min_w * getVal;
          sign_types.set_min_h = _this.defaults.div_min_h * 2 * getVal;
          sign_types.set_max_w = _this.defaults.div_max_w * getVal;
          sign_types.set_max_h = _this.defaults.div_max_h * 2 * getVal;
  
          break;
  
        case "tl_initials":
          sign_types.title = "Click to initial";
          sign_types.text = "Click to initial";
          sign_types.advance = 0;
          sign_types.name = "cc_initials";
          sign_types.inside_text = "initial";
          sign_types.width = 100;
          sign_types.resize = 1;
          sign_types.set_min_w = _this.defaults.div_min_w;
          sign_types.set_min_h = _this.defaults.div_min_h;
          sign_types.set_max_w = _this.defaults.div_max_w;
          sign_types.set_max_h = _this.defaults.div_max_h;
          break;
  
        case "tl_textbox":
          var text_title = "Write text here";
          if (
            typeof this.current_label != "undefined" &&
            this.current_label !== ""
          ) {
            text_title = this.current_label;
          }
          sign_types.title = text_title;
          sign_types.text = text_title;
          sign_types.advance = 1;
          sign_types.name = "cc_textbox";
          sign_types.inside_text = "Text";
          sign_types.width = 100;
          sign_types.resize = 1;
          sign_types.set_min_w = _this.defaults.div_min_w;
          sign_types.set_min_h = _this.defaults.div_min_h;
          sign_types.set_max_w = _this.defaults.div_max_w;
          sign_types.set_max_h = _this.defaults.div_max_h;
          break;
  
        case "tl_checkbox":
          sign_types.title = "Click to mark this check";
          sign_types.text = "";
          sign_types.advance = 1;
          sign_types.name = "cc_checkbox";
          sign_types.inside_text = "";
          sign_types.width = 30;
          sign_types.resize = 0;
          sign_types.set_min_w = 30;
          sign_types.set_min_h = 30;
          sign_types.set_max_w = 30;
          sign_types.set_max_h = 30;
          break;
  
        case "tl_signdate":
          sign_types.title = "Enter date";
          sign_types.text = "Enter date";
          sign_types.advance = 1;
          sign_types.name = "cc_signdate";
          sign_types.inside_text = "DD / MM / YYYY";
          sign_types.width = 110;
          sign_types.resize = 0;
          sign_types.set_min_w = 120;
          sign_types.set_min_h = 30;
          sign_types.set_max_w = 120;
          sign_types.set_max_h = 30;
          break;
      }
    };
  
    this.set_opt_now = function (thisid) {
      var thisclass = $("#tool_component_" + thisid + " .handle-wrapper").attr(
        "classname"
      );
      // console.log(thisclass)
      var stampVal = 0;
      if (
        thisclass == "cc_initials" ||
        thisclass == "cc_sign" ||
        thisclass == "cc_stamp"
      ) {
        var set_ini = 1;
        if (thisclass == "cc_initials") set_ini = 2;
        if (thisclass == "cc_stamp") {
          stampVal = 1;
          set_ini = 3;
        }
        //var url = this.defaults.site_url +"signature/create_sign_iframe/sign_initial/"+set_ini +"/set_back/1/is_stamp/"+stampVal+"/"+user_key+"/"+this.user_key;  // comment by Kartik
        var url = this.defaults.site_url +"signature/create_sign_iframe/sign_initial/"+set_ini +"/set_back/1";   // added by kartik
        console.log(url);
        $.colorbox({
          href: url,
          iframe: true,
          width: "80%",
          height: "470px",
          opacity: "0.5",
          escKey: false, //escape key will not close
          overlayClose: false, //clicking background will not close
        });
      }
      if (thisclass == "cc_checkbox") {
        var img =
          '<img class="description_rep_img" data-c="' +
          this.current_select +
          '" src="' +
          this.defaults.checkbox_img +
          '">' +
          this.set_remove_icon();
        if (
          $("#tool_component_" + this.current_select + " .description").length ==
          1
        )
          $(
            "#tool_component_" + this.current_select + " .description"
          ).replaceWith(img);
        this.success_show(this.current_select);
      }
      if (thisclass == "cc_textbox") {
        $("#tool_component_" + this.current_select + " .description").replaceWith(
          '<input type="text" data-c="' +
          this.current_select +
          '" class="description_rep_input" value="" />' +
          this.set_remove_icon()
        );
        $(
          "#tool_component_" + this.current_select + " .description_rep_input"
        ).focus();
        this.autoGrowText();
      }
      if (thisclass == "cc_signdate") {
        $("#tool_component_" + this.current_select + " .description").replaceWith(
          '<input type="text" data-c="' +
          this.current_select +
          '" class="description_rep_input" value = "' +
          this.date +
          '" />' +
          this.set_remove_icon()
        );
        var dateval = $.trim(
          $(
            "#tool_component_" + this.current_select + " .description_rep_input"
          ).val()
        );
        if (dateval.length > 0) this.success_show(this.current_select);
        $(
          "#tool_component_" + this.current_select + " .description_rep_input"
        ).focus();
        this.dateSpaceTrim();
      }
    };
  
    this.signature_frame_return = function (file, width, height) {
      
      var src = this.defaults.sign_url +file +"/user_key/" +user_key +"/?t=" +this.timestamp();  // comment by kp
      var src = this.defaults.sign_url +file;   // added by kartik
      var img =
        '<img title="Click to edit" data-key="' +
        file +
        '" class="description_rep_img" data-c="' +
        this.current_select +
        '" src="' +
        src +
        '">' +
        this.set_remove_icon();
      if (
        $("#tool_component_" + this.current_select + " .description").length == 1
      ) {
        $("#tool_component_" + this.current_select + " .description").replaceWith(
          img
        );
      } else {
        $(
          "#tool_component_" + this.current_select + " .description_rep_img"
        ).attr("src", src);
        $(
          "#tool_component_" + this.current_select + " .description_rep_img"
        ).attr("data-key", file);
      }
  
      var cur_w = $("#tool_component_" + this.current_select).width();
      var cur_h = $("#tool_component_" + this.current_select).height();
      var this_rat = width / height;
      var new_w = width;
      var new_h = height;
  
      if (new_w >= new_h) {
        new_w = cur_w;
        new_h = cur_w / this_rat;
        if (new_h > cur_h) {
          new_h = cur_h;
          new_w = cur_h * this_rat;
        }
      } else {
        new_h = cur_h;
        new_w = cur_h * this_rat;
        if (new_w > cur_w) {
          new_w = cur_w;
          new_h = cur_w * this_rat;
        }
      }
      $("#tool_component_" + this.current_select + " .description_rep_img").css({
        width: new_w,
        height: new_h,
      });
      $("#tool_component_" + this.current_select + " .wrapper.interactive")
        .addClass("comp-signed")
        .attr("title", "Click to edit");
      this.success_show(this.current_select);
      $(
        "#tool_component_" +
        this.current_select +
        " .assignment-select option:first"
      ).attr("selected", "selected");
  
      // replace other sign position with same image if allow
      if (this.copy_sign_all_place) {
        var _upthis = this;
        if ($("#tool_component_" + this.current_select).hasClass("cc_sign")) {
          var sign_positions = $(".main_doc_container div.cc_sign");
        } else if (
          $("#tool_component_" + this.current_select).hasClass("cc_initials")
        ) {
          var sign_positions = $(".main_doc_container div.cc_initials");
        }
        if (sign_positions) {
          sign_positions.each(function () {
            _upthis.current_select = this.id.substring(15);
            img =
              '<img title="Click to edit" data-key="' +
              file +
              '" class="description_rep_img" data-c="' +
              _upthis.current_select +
              '" src="' +
              src +
              '">' +
              _upthis.set_remove_icon();
            if (
              $("#tool_component_" + _upthis.current_select + " .description")
                .length == 1
            ) {
              $(
                "#tool_component_" + _upthis.current_select + " .description"
              ).replaceWith(img);
            } else {
              $(
                "#tool_component_" +
                _upthis.current_select +
                " .description_rep_img"
              ).attr("src", src);
              $(
                "#tool_component_" +
                _upthis.current_select +
                " .description_rep_img"
              ).attr("data-key", file);
            }
            $(
              "#tool_component_" +
              _upthis.current_select +
              " .description_rep_img"
            ).css({ width: new_w, height: new_h });
            $(
              "#tool_component_" +
              _upthis.current_select +
              " .wrapper.interactive"
            )
              .addClass("comp-signed")
              .attr("title", "Click to edit");
            _upthis.success_show(_upthis.current_select);
            $(
              "#tool_component_" +
              _upthis.current_select +
              " .assignment-select option:first"
            ).attr("selected", "selected");
          });
        }
      }
    };
  
    this.error_show = function (thisid) {
      var arr_id = thisid.split("_")[1];
      if (_this.data_arr[arr_id].required != 1) return;
  
      //$("#tool_component_"+thisid +" .wrapper").css("cssText",'border-color:#FF0000 !important');
      //$("#sign_visual_"+thisid).css("cssText",'border:2px solid #FF0000 !important');
      $("#tool_component_" + thisid + " .wrapper").css(
        "cssText",
        "border-color:#FF0000 !important"
      );
      $("#sign_visual_" + thisid).addClass("show-vis-error");
      $("#sign_visual_" + thisid).removeClass("show-vis-success");
      $("#sign_visual_" + thisid).removeClass("req_done");
      this.progress_bar();
    };
    this.success_show = function (thisid) {
      var arr_id = thisid.split("_")[1];
      if (_this.data_arr[arr_id].required != 1) return;
  
      $("#sign_visual_" + thisid).removeClass("show-vis-error");
      $("#sign_visual_" + thisid).addClass("show-vis-success");
      //$("#sign_visual_"+thisid).css("cssText",'border:2px solid #16DE27 !important');
      $("#tool_component_" + thisid + " .wrapper").css(
        "cssText",
        "border-color:#16DE27 !important"
      );
      $("#sign_visual_" + thisid).addClass("req_done");
      this.progress_bar();
    };
    this.set_remove_icon = function () {
      $("#tool_component_" + this.current_select).addClass("component-fade");
      return (
        '<div data-count="' + this.current_select + '" class="remove"></div>'
      );
    };
  
    this.sign_frame_close = function () { };
  
    this.reset_opt_now = function (id) {
      var thisclass = $("#tool_component_" + id + " .handle-wrapper").attr(
        "classname"
      );
      var thisclassn = "tl_" + thisclass.split("_")[1];
  
      this.get_types_build(thisclassn);
  
      if (
        thisclass == "cc_initials" ||
        thisclass == "cc_sign" ||
        thisclass == "cc_checkbox"
      ) {
        $("#tool_component_" + id + " .description_rep_img").replaceWith(
          '<p class="description text-center" title="' +
          sign_types.title +
          '">' +
          sign_types.text +
          "</p>"
        );
      }
      if (thisclass == "cc_textbox" || thisclass == "cc_signdate") {
        $("#tool_component_" + id + " .description_rep_input").replaceWith(
          '<p class="description text-center" title="' +
          sign_types.title +
          '">' +
          sign_types.text +
          "</p>"
        );
      }
      $("#tool_component_" + id + " .remove").remove();
      var arr_id = id.split("_")[1];
      $("#tool_component_" + id).css({
        width: this.data_arr[arr_id].extra_options.width,
        height: this.data_arr[arr_id].extra_options.height,
      });
      $("#tool_component_" + id + " .description").css({
        width: this.data_arr[arr_id].extra_options.width,
        height: this.data_arr[arr_id].extra_options.height,
        "line-height": this.data_arr[arr_id].extra_options.height + "px",
      });
  
      this.error_show(id);
  
      $("#tool_component_" + id + " .wrapper.interactive")
        .removeClass("comp-signed")
        .removeAttr("title");
      $("#tool_component_" + id + " .description").attr("data-c", id);
    };
  
    this.get_type_inside = function (id) {
      var thisclass = $("#tool_component_" + id + " .handle-wrapper").attr(
        "classname"
      );
      var ret_obj = {};
  
      if (thisclass == "cc_initials") {
        ret_obj.type = "cc_initials";
        ret_obj.val = this.check_fill_box(id);
        ret_obj.change = false;
        if (ret_obj.val == false) {
          ret_obj.change = true;
          ret_obj.val = $("#tool_component_" + id + " .description_rep_img").attr(
            "data-key"
          );
        }
      }
      if (thisclass == "cc_sign") {
        ret_obj.type = "cc_sign";
        ret_obj.val = this.check_fill_box(id);
        ret_obj.change = false;
        if (ret_obj.val == false) {
          ret_obj.change = true;
          ret_obj.val = $("#tool_component_" + id + " .description_rep_img").attr(
            "data-key"
          );
        }
      }
      if (thisclass == "cc_stamp") {
        ret_obj.type = "cc_stamp";
        ret_obj.val = this.check_fill_box(id);
        ret_obj.change = false;
        if (ret_obj.val == false) {
          ret_obj.change = true;
          ret_obj.val = $("#tool_component_" + id + " .description_rep_img").attr(
            "data-key"
          );
        }
      }
      if (thisclass == "cc_textbox") {
        ret_obj.type = "cc_textbox";
        ret_obj.val = this.check_fill_box(id);
        ret_obj.change = false;
        if (ret_obj.val == false) {
          ret_obj.change = true;
          ret_obj.val = $(
            "#tool_component_" + id + " .description_rep_input"
          ).val();
        }
      }
      if (thisclass == "cc_checkbox") {
        ret_obj.type = "cc_checkbox";
        ret_obj.val = this.check_fill_box(id);
        ret_obj.change = false;
        if (ret_obj.val == false) {
          ret_obj.change = true;
          ret_obj.val = $("#tool_component_" + id + " .description_rep_img").attr(
            "src"
          );
        }
      }
      if (thisclass == "cc_signdate") {
        ret_obj.type = "cc_signdate";
        ret_obj.val = this.check_fill_box(id);
        ret_obj.change = false;
        if (ret_obj.val == false) {
          ret_obj.change = true;
          ret_obj.val = $(
            "#tool_component_" + id + " .description_rep_input"
          ).val();
        }
      }
      return ret_obj;
    };
  
    this.check_fill_box = function (id) {
      if ($("#tool_component_" + id + " .description").length == 1) {
        return "";
      }
      return false;
    };
  
    this.get_saved_doc = function () {
      var doc_save_obj = [];
      if (this.inputs_validate() == false) {
        bootbox.alert("Please complete all the required fields (red bordered)");
        return false;
      }
      $(".main_doc_container .tool_comp_main").each(function (i) {
        var new_this_obj = {};
        var thisid = this.id.split("_");
        var arr_id = thisid[3];
        thisid = thisid[2] + "_" + thisid[3];
  
        new_this_obj.id = "";
        new_this_obj.div_id = thisid;
        new_this_obj.position = _this.data_arr[arr_id].position;
        new_this_obj.position2 = _this.get_position(
          _this.data_arr[arr_id].position,
          this.id
        );
        new_this_obj.type_inside = _this.get_type_inside(thisid);
        //new_this_obj.width = _this.data_arr[arr_id].extra_options.width;
        new_this_obj.width = $(this).width();
        new_this_obj.height = _this.data_arr[arr_id].extra_options.height;
        new_this_obj.page = _this.data_arr[arr_id].extra_options.page;
        new_this_obj.required = _this.data_arr[arr_id].required;
        new_this_obj.adv_val = "";
        new_this_obj.font_size = "";
        doc_save_obj.push(new_this_obj);
        // debugger;
      });
  
      // console.log(_this.data_arr);
      return JSON.stringify(doc_save_obj);
    };
  
    this.get_position_old = function (position, thisobj) {
      thisobj = "#" + thisobj;
      // checking the bottom side with border
      var hh = $(thisobj).height();
      var this_top = position.top;
      var ui_max = this_top + hh;
      var max_r = _this.defaults.doc_height + 4;
      var pos = this_top;
      if (ui_max <= max_r) pos = this_top;
      else {
        pos = parseInt(this_top / max_r);
        pos = parseInt(ui_max - max_r * pos - hh);
        position.top = pos;
      }
      return position;
    };
  
    this.get_position = function (position, thisobj) {
      thisobj = "#" + thisobj;
      // checking the bottom side with border
      var getVal = parseFloat($("#zoomSelect").val());
      var hh = $(thisobj).height() / getVal;
      var this_top = position.top;
      var ui_max = this_top + hh;
  
      console.log(_this.defaults.doc_height, this_top);
      var max_r = _this.defaults.doc_height + 4 * getVal;
      // var max_r = _this.defaults.doc_height - 8;
      var pos = this_top;
      if (ui_max <= max_r) pos = this_top;
      else {
        pos = parseInt(this_top / max_r);
  
        pos = parseInt(ui_max - max_r * pos - hh);
        position.top = pos / getVal;
        // position.top = position.top / getVal;
      }
      console.log(position);
      position.left = position.left / getVal;
      //debugger;
      return position;
    }; 
  
    this.inputs_validate = function () {
      var ret = true;
      $(".main_doc_container .tool_comp_main .description_rep_input").removeClass(
        "error"
      );
      $(".main_doc_container .tool_comp_main .description_rep_img").removeClass(
        "error"
      );
      $(".main_doc_container .tool_comp_main .description").removeClass("error");
      $(".main_doc_container .tool_comp_main .description_rep_img").each(
        function () {
          var thisid = $(this).attr("data-c");
          var arr_id = thisid.split("_")[1];
          var top_pos = $("#tool_component_" + thisid).position().top;
          if (_this.data_arr[arr_id].required == 1) {
            var this_val = $.trim($(this).attr("data-c"));
            if (typeof this_val !== "undefined") {
              if (this_val.length == 0) {
                $(this).addClass("error").focus();
                $(".fixed_doc_container").animate(
                  { scrollTop: top_pos - 20 },
                  1000
                );
                ret = false;
                return ret;
              }
            } else {
              $(this).addClass("error").focus();
              $(".fixed_doc_container").animate(
                { scrollTop: top_pos - 20 },
                1000
              );
              ret = false;
              return ret;
            }
          }
        }
      );
      $(".main_doc_container .tool_comp_main .description_rep_input").each(
        function () {
          var thisid = $(this).attr("data-c");
          var arr_id = thisid.split("_")[1];
          var top_pos = $("#tool_component_" + thisid).position().top;
          if (_this.data_arr[arr_id].required == 1) {
            var this_val = $.trim(this.value);
            if (this_val.length == 0) {
              $(this).addClass("error").focus();
              $(".fixed_doc_container").animate(
                { scrollTop: top_pos - 20 },
                1000
              );
              ret = false;
              return ret;
            }
          }
        }
      );
  
      $(".main_doc_container .tool_comp_main .description").each(function () {
        var thisid = $(this).attr("data-c");
        var arr_id = thisid.split("_")[1];
        var top_pos = $("#tool_component_" + thisid).position().top;
  
        if (_this.data_arr[arr_id].required == 1) {
          $(".fixed_doc_container").animate({ scrollTop: top_pos - 20 }, 1000);
          ret = false;
          return ret;
        }
      });
      return ret;
    };
  
    this.scroll_to_obj = function (datac) {
      var call_sh = 0;
  
      var top_pos = $("#tool_component_" + datac).position().top;
      $(".tool_comp_main").css("zIndex", 0);
      $("#tool_component_" + datac).css("zIndex", 100);
      $("#tool_component_" + this.called_datac).stop();
      $("#tool_component_" + this.called_datac).css({ boxShadow: "none" });
      call_shadow();
      this.called_datac = datac;
      function call_shadow() {
        $("#tool_component_" + datac).animate(
          { boxShadow: "0 0 30px #333" },
          600,
          function () {
            $(this).animate({ boxShadow: "none" }, 600, function () {
              call_sh++;
              if (call_sh < 4) {
                setTimeout(function () {
                  call_shadow();
                }, 500);
              } else call_sh = 0;
            });
          }
        );
      }
  
      $(".fixed_doc_container").animate({ scrollTop: top_pos - 100 }, 1000);
    };
  
    this.dateSpaceTrim = function () {
      $(".tool_comp_main.cc_signdate .description_rep_input").each(function () {
        var input = $(this);
        input.bind("blur", function (e) {
          input.val($.trim(input.val()));
        });
      });
    };
  
    this.autoGrowText = function () {
      o = {
        maxWidth: 670,
        minWidth: 100,
        comfortZone: 10,
      };
  
      $(".tool_comp_main.cc_textbox .description_rep_input").each(function () {
        var minWidth = $(this).width(),
          val = "",
          input = $(this),
          testSubject = $("<tester/>").css({
            position: "absolute",
            top: -9999,
            left: -9999,
            width: "auto",
            fontSize: input.css("fontSize"),
            fontFamily: input.css("fontFamily"),
            fontWeight: input.css("fontWeight"),
            letterSpacing: input.css("letterSpacing"),
            whiteSpace: "nowrap",
          }),
          check = function (e) {
            if (val === (val = input.val())) {
              return;
            }
  
            // Enter new content into testSubject
            var escaped = val
              .replace(/&/g, "&amp;")
              .replace(/\s/g, " ")
              .replace(/</g, "&lt;")
              .replace(/>/g, "&gt;");
            testSubject.html(escaped);
  
            // Calculate new width + whether to change
            var testerWidth = testSubject.width(),
              newWidth =
                testerWidth + o.comfortZone >= minWidth
                  ? testerWidth + o.comfortZone
                  : minWidth,
              currentWidth = input.width(),
              isValidWidthChange =
                (newWidth < currentWidth && newWidth >= minWidth) ||
                (newWidth > minWidth && newWidth < o.maxWidth);
  
            if (
              !isValidWidthChange &&
              newWidth > minWidth &&
              newWidth > o.maxWidth
            ) {
              newWidth = o.maxWidth;
              isValidWidthChange = true;
            }
  
            // Animate width
            if (isValidWidthChange) {
              var inpid = input.attr("data-c");
              input.width(newWidth);
              var padd_l = input.css("padding-left");
              var padd_r = input.css("padding-right");
              $("#tool_component_" + inpid).width(
                parseInt(newWidth + 3) + parseInt(padd_l) + parseInt(padd_r)
              );
            }
          };
  
        testSubject.insertAfter(input);
  
        $(this).bind("keydown keyup update", function (e) {
          var thisw = $(this).width();
          var thisid = $(this).attr("data-c");
          var code = e.keyCode || e.which || null;
          if (code === 8 || code === 46) {
            check(e);
          } else {
            // checking the right side
  
            var position = $("#tool_component_" + thisid).position();
            var ui_max = position.left + thisw;
  
            var max_r =
              _this.defaults.doc_width + _this.defaults.conatiner_margin - 1;
            if (ui_max > max_r) {
              e.preventDefault();
              check(e);
            } else {
              check(e);
            }
          }
        });
  
        // Auto-size when page first loads
        check(null);
  
        $(this).bind("blur", function (e) {
          var thisVal = $(this).val();
          $(this).val($.trim(thisVal));
        });
      });
    };
  
    this.checkRemovedSign = function (this_id) {
      $(".description_rep_img").each(function () {
        var thiskey = $(this).attr("data-key");
        var thisc = $(this).attr("data-c");
  
        if (thiskey == this_id) {
          _this.reset_opt_now(thisc);
        }
      });
    };
  }