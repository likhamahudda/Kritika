var docObj = new prepareDoc({ sel_show: prepare_doc_sel_show });
var baseDocObj = new doc_basic_fn({ request: request });
window.onunload = function () {
  sessionStorage.clear();
};
$(function () {
  console.log($(window).width());
  // if ($(window).width() <= 1366 && $(window).width() > 1024) {
  //   $("#zoomSelect").val(".75");
  //   var resolWidth = $(window).width();
  //   if (resolWidth >= 1024 && resolWidth <= 1365) {
  //     resolWidth = resolWidth * 0.5;
  //   } else if (resolWidth >= 1366 && resolWidth < 1800) {
  //     resolWidth = 807;
  //   } else {
  //     resolWidth = 1076;
  //   }
  //   // console.log(resolWidth);
  //   $(".edtrMain .editorcontainer").attr(
  //     "style",
  //     "max-width:" + resolWidth + "px !important"
  //   );
  // }
  $("footer").parent().remove();

  var height =
    $(window).height() -
    $(".navbar.navbar-inverse").height() -
    $(".footer_content.agree_terms_cont").height() -
    $(".header").height() -
    30;
  if (height < 300) {
    $(".footer_content.agree_terms_cont").css({ position: "relative" });
    height = 300;
  }
  $(".fixed_doc_container").height(height);
  //$(".sign_tool_visual").height(height);

  docObj.curr_tl_comp = "tl_sign";
  docObj.tem_type = 1;
  docObj.request = request;
  docObj.user_key = user_key;
  docObj.init(doc_setdata);

  $(".sign-progress-bar").slideDown(1000);

  $("#iAgreeButton").click(function (e) {
    e.preventDefault();
    var docData = docObj.get_saved_doc();
    if (docData != false) baseDocObj.pushDataToDb(docData);
  });

  //-------- file upload while signing the docs ---------------------------------------
  $("#docs_file").on("change", function () {
    var formData = new FormData($("#form_upl")[0]);
    $("#docs_file").val("");
    baseDocObj.validateFileData(formData);
  });

  $("#user_doc_delete").on("click", function () {
    var image_key = $(this).attr("data-key");
    bootbox.confirm("Are you sure want to delete?", function (result) {
      if (result === true) {
        baseDocObj.deleteUserFileData(image_key);
      }
    });
  });

  $("#iDecline").click(function (e) {
    e.preventDefault();
    var t = $(this).attr("data-key");
    //  $('#iAgreeButton').attr("disabled","disabled");
    $(this).attr("disabled", "disabled");
    baseDocObj.declineDocument(t);
  });

  $(".sign_tl_icon").click(function (e) {
    e.preventDefault();
    var datac = this.id;
    datac = datac.split("_");
    datac = datac[2] + "_" + datac[3];
    var docData = docObj.scroll_to_obj(datac);
    return docData;
  });

  $(".main_doc_container").on("click", ".tool_comp_main .remove", function (e) {
    e.preventDefault();
    var id = $(this).attr("data-count");
    // console.log(id)
    docObj.reset_opt_now(id);
  });

  $(".main_doc_container").on(
    "click",
    ".tool_comp_main .description,.tool_comp_main .description_rep_input",
    function (e) {
      e.preventDefault();
      var id = $(this).attr("data-c");
      console.log(id);
      docObj.current_select = id;
      docObj.set_opt_now(id);
      if (
        $(".description_rep_img", this).length == 0 &&
        $(".description", this).length == 1
      ) {
      } else if ($(".description_rep_img", this).length == 1) {
      }
    }
  );
  $(".main_doc_container").on(
    "click",
    ".tool_comp_main .comp-signed",
    function (e) {
      e.preventDefault();
      var id = $(".description_rep_img", this).attr("data-c");
      docObj.current_select = id;
      docObj.set_opt_now(id);
    }
  );

  $(".main_doc_container").on("keyup", ".description_rep_input", function () {
    var thisval = $.trim(this.value);
    var thisid = $(this).attr("data-c");
    if (thisval.length > 0) {
      docObj.success_show(thisid);
    } else {
      docObj.error_show(thisid);
    }
  });

  $("#btn-proceed").on("click", function () {
    var a1 = $("#a1").val();
    var a2 = $("#a2").length ? $("#a2").val() : "";
    var requestAssignKey = $(this).attr("data-key");
    var requestUserKey = $(this).attr("user-key");
    $.ajax({
      url: SITE_URL + "request/verifydata/",
      type: "post",
      data:
        "a1=" +
        a1 +
        "&a2=" +
        a2 +
        "&requestKey=" +
        requestAssignKey +
        "&user_key=" +
        requestUserKey,
      dataType: "json",
      beforeSend: function () {
        custombox.alert("Verifying. Please wait..");
      },
      success: function (result) {
        //  console.log(result);
        if (result.error == 1) {
          custombox.alert(result.msg, 0, 7000);
        } else {
          custombox.alert(result.msg, 1, 7000);
          setTimeout(function () {
            location.reload();
          }, 1000);
        }
        /*$("#request_loader_modal .progress-bar-success").css("width", "100%");
                $("#request_loader_modal").modal('hide');
                bootbox.alert("Signed Process Completed..", function () {
                    window.location.href = SITE_URL + "site/";
                });*/
      },
      complete: function () {},
    });
  });

  $("#btn-request-otp").on("click", function () {
    var requestAssignKey = $(this).attr("data-key");
    var requestUserKey = $(this).attr("user-key");
    //alert(requestAssignKey+'userkey'+requestUserKey);
    $.ajax({
      url: SITE_URL + "request/sendOtpMail/",
      type: "post",
      data: "&requestKey=" + requestAssignKey + "&user_key=" + requestUserKey,
      dataType: "json",
      beforeSend: function () {
        custombox.alert("Verifying. Please wait..");
      },
      success: function (result) {
        //  console.log(result);
        if (result.error == 1) {
          custombox.alert(result.msg, 0, 7000);
        } else {
          custombox.alert(result.msg, 1, 7000);
        }
      },
      complete: function () {},
    });
  });

  // starting validating of one time password

  $("#btn-proceed-otp").on("click", function () {
    var otp = $("#otp_input").val();
    var requestAssignKey = $(this).attr("data-key");
    var requestUserKey = $(this).attr("user-key");
    $.ajax({
      url: SITE_URL + "request/verifyOtpdata/",
      type: "post",
      data:
        "otp=" +
        otp +
        "&requestKey=" +
        requestAssignKey +
        "&user_key=" +
        requestUserKey,
      dataType: "json",
      beforeSend: function () {
        custombox.alert("Verifying. Please wait..");
      },
      success: function (result) {
        //  console.log(result);
        if (result.error == 1) {
          custombox.alert(result.msg, 0, 7000);
        } else {
          custombox.alert(result.msg, 1, 7000);
          setTimeout(function () {
            location.reload();
          }, 1000);
        }
      },
      complete: function () {},
    });
  });
  // end validation of one time password

  $(".sign_tool_visual .up_btn").click(function (e) {
    e.preventDefault();
    $(".sign-tool-main").animate(
      { scrollTop: $(this).scrollTop() - 5 },
      800,
      "swing"
    );
  });
  $(".sign_tool_visual .down_btn").click(function (e) {
    e.preventDefault();
    $(".sign-tool-main").animate(
      { scrollTop: $(this).position().top + 5 },
      800,
      "swing"
    );
  });

  /*  prepare document events ends here*/
});

function doc_basic_fn(options) {
  // Default Values
  (this.defaults = {
    request: "",
  }),
    (this.defaults = $.extend(this.defaults, options));
  var _this = this;

  this.template = function (template, dataObj, appender) {
    template = $(template).html();
    if (appender === 1) {
      var m_temp = $.tmpl(template, dataObj);
      return m_temp;
    } else $.tmpl(template, dataObj).appendTo(appender);
  };

  this.pushDataToDb = function (docData) {
    var nthis = this;
    width = 0;
    $.ajax({
      url: SITE_URL + "request/processdoc", 
      type: "post",
      data: { docdata: docData, request_id: this.defaults.request }, //"docdata=" + docData + "&request=" + this.defaults.request,
      dataType: "json",
      beforeSend: function () {
        $("#request_loader_modal").modal("show");
        $('<div class="modal-backdrop fade in"></div>').appendTo(
          "#request_loader_modal"
        );
        nthis.process_bar($("#request_loader_modal .progress-bar-success"));
        //$("#step_id").css({'width':'40%'});
      },
      success: function (result) {
        if (result.error == 0) {
          var is_create_sign_apreq = 0;
          var website_widget_id = 0;
          var refreal_url = "";
          if (typeof create_sign_apreq !== "undefined") {
            is_create_sign_apreq = create_sign_apreq;
          }
          if (typeof api_website_widget_id !== "undefined") {
            website_widget_id = api_website_widget_id;
          }
          if (typeof refreal !== "undefined") {
            refreal_url = refreal;
          }
          //alert(refreal_url);

          $.ajax({
            url: SITE_URL + "request/finalizeProcessDoc",
            type: "post",
            data:
              "docdata=" +
              docData +
              "&request=" +
              nthis.defaults.request +
              "&is_create_sign_apreq=" +
              is_create_sign_apreq +
              "&website_widget_id=" +
              website_widget_id +
              "&refreal_url=" +
              refreal_url,
            dataType: "json",
            beforeSend: function () {},
            success: function (result) {
              $("#request_loader_modal .progress-bar-success").css(
                "width",
                "100%"
              );
              $("#request_loader_modal").modal("hide");
              //alert(redirect_link);
              bootbox.alert("Signing Process Completed..", function () {
                if (
                  typeof redirect_link !== "undefined" &&
                  redirect_link.length > 1
                ) {
                  //window.location.href = redirect_link;
                  window.location = "//" + redirect_link;
                  return false;
                }
                window.location.href = SITE_URL + "user";
              });
            },
            complete: function () {},
            error: function (err) {
              setTimeout(function () {
                $("#request_loader_modal .progress-bar-success").css(
                  "width",
                  "100%"
                );
                $("#request_loader_modal").modal("hide");
                bootbox.alert("Your Signing Process Completed..", function () {
                  if (
                    typeof redirect_link !== "undefined" &&
                    redirect_link.length > 1
                  ) {
                    //window.location.href = redirect_link;
                    window.location = "//" + redirect_link;
                    return false;
                  }
                  window.location.href = SITE_URL + "user";
                });
              }, 5000);
            },
          });
        } else if (result.error == 1) {
          if (
            typeof result.error_type != "undefined" &&
            result.error_type == "file"
          ) {
            $("#request_loader_modal").modal("hide");
            bootbox.alert(result.error_mess);
          } else {
            bootbox.alert(result.error_mess, function () {
              window.location.href = SITE_URL + "user";
            });
          }
        }
      },
      complete: function () {},
    });
  };
  //--------------- validate user uploaded file ---------------------//

  this.validateFileData = function (formData) {
    var nthis = this;
    width = 0;
    $.ajax({
      url: SITE_URL + "request/processUserUploadedDoc/",
      type: "post",
      data: formData,
      dataType: "json",
      contentType: false,
      processData: false,
      beforeSend: function () {
        custombox.alert("Processing please wait", 1);
        /*                 $("#request_loader_modal").modal("show");
                 $('<div class="modal-backdrop fade in"></div>').appendTo('#request_loader_modal');
                 nthis.process_bar($("#request_loader_modal .progress-bar-success"));*/
        //$("#step_id").css({'width':'40%'});
      },
      success: function (result) {
        if (result.error == 0) {
          setTimeout(function () {
            /*               		  $("#request_loader_modal .progress-bar-success").css("width", "100%");
                		  $("#request_loader_modal").modal('hide');  */
            custombox.alert(result.mess, 1, 2000);

            $("#upload_doc_div").fadeOut("slow");
            $("#img_info_div").fadeIn("slow");
            $("#user_doc_name").html(result.file_name);
            $("#user_doc_delete").attr("data-key", result.image_key);
          }, 1000);
        } else {
          setTimeout(function () {
            /*                		  $("#request_loader_modal .progress-bar-success").css("width", "100%");
                		  $("#request_loader_modal").modal('hide');*/
            custombox.alert(result.mess, 0, 2000);
          }, 1000);
        }
      },
      complete: function () {},
    });
  };
  //--------------- validate user uploaded file ---------------------//

  this.deleteUserFileData = function (image_key) {
    var nthis = this;
    width = 0;
    $.ajax({
      url: SITE_URL + "request/deleteUserUploadedDoc/",
      type: "post",
      data: { image_key: image_key },
      dataType: "json",
      beforeSend: function () {
        custombox.alert("Processing", 1, 2000);
        //$("#step_id").css({'width':'40%'});
      },
      success: function (result) {
        if (result.error == 0) {
          custombox.alert(result.mess, 1, 2000);
          $("#upload_doc_div").fadeIn("slow");
          $("#img_info_div").fadeOut("slow");
        } else {
          custombox.alert(result.mess, 0, 2000);
        }
      },
      complete: function () {},
    });
  };

  this.declineDocument = function (requestKey) {
    bootbox.prompt(
      "Please mention the reason for declining (500 character max)",
      function (result) {
        if (result === null) {
          //do something here
          $("#iDecline").removeAttr("disabled");
        } else {
          var stringLength = result.length;
          var trimedSentences = $.trim(result);
          if (trimedSentences == "") {
            custombox.alert("Invalid reason.", 0, 5000);
            this.declineDocument(requestKey);
          } else if (stringLength > 500) {
            custombox.alert("Reason exceeds 500 character limit.", 0, 5000);
            this.declineDocument(requestKey);
          }

          var is_create_sign_apreq = 0;
          var website_widget_id = 0;
          var refreal_url = "";
          if (typeof create_sign_apreq !== "undefined") {
            is_create_sign_apreq = create_sign_apreq;
          }
          if (typeof api_website_widget_id !== "undefined") {
            website_widget_id = api_website_widget_id;
          }
          if (typeof refreal !== "undefined") {
            refreal_url = refreal;
          }

          $.ajax({
            url: SITE_URL + "request/declinedoc/",
            type: "post",
            data:
              "request=" +
              requestKey +
              "&reason=" +
              result +
              "&is_create_sign_apreq=" +
              is_create_sign_apreq +
              "&website_widget_id=" +
              website_widget_id +
              "&refreal_url=" +
              refreal_url,
            dataType: "json",
            beforeSend: function () {
              custombox.alert("Please wait..");
              $('<div class="modal-backdrop fade in"></div>').appendTo(
                document.body
              );
            },
            success: function (result) {
              custombox.alert("Declined Processed");
              setTimeout(function () {
                window.location.href = SITE_URL + "site/";
              }, 3000);
            },
          });
        }
      }
    );
  };
  var width = 0;
  this.process_bar = function (thisobj) {
    var nn = this;
    width++;
    thisobj.css({ width: width + "%" });
    if (width <= 100) {
      setTimeout(function () {
        nn.process_bar(thisobj);
      }, 100);
    }
  };
}

$("#zoomSelect").change(function (e) {
  e.preventDefault();

  var getVal = $("#zoomSelect").val();
  var z = 680 * getVal;
  if ($(window).width() <= 1440) {
    var z = 680 * getVal;
    $(".main_doc_container ").css("width", z + "px");
  } else {
    $(".main_doc_container ").css("width", z + "px");
  }

  var fontsize = 14;
  var linheight = 40;
  var arrow_box_left = 120;
  var countBox = $(".ui-draggable").length;
  var tool_component = false;
  var w = 100;
  var h = 40;
  for (let i = 0; i < countBox; i++) {
    var uiDraggable = $(".main_doc_container .ui-draggable")[i];
    var uiDraggableId = $(uiDraggable).attr("id");
    var uiDraggableIdCount = uiDraggableId.split("_");
    var getSession = JSON.parse(
      sessionStorage.getItem(parseInt(uiDraggableIdCount[3]))
    );
    tool_component = $(
      "#tool_component_" +
        uiDraggableIdCount[2] +
        "_" +
        parseInt(uiDraggableIdCount[3])
    );
    if (
      tool_component.hasClass("cc_sign") == true ||
      tool_component.hasClass("cc_initials") == true
    ) {
      w = 100;
      h = 40;
      linheight = 40;
      arrow_box_left = 120;
    } else if (tool_component.hasClass("cc_stamp") == true) {
      w = 100;
      h = 80;
      linheight = 80;
      arrow_box_left = 120;
    } else if (tool_component.hasClass("cc_textbox") == true) {
      w = 100;
      h = 25;
      linheight = 25;
      arrow_box_left = 120;
    } else if (tool_component.hasClass("cc_checkbox") == true) {
      w = 30;
      h = 30;
      arrow_box_left = 50;
    } else if (tool_component.hasClass("cc_signdate") == true) {
      w = 120;
      h = 30;
      arrow_box_left = 140;
    }

    $(
      "#tool_component_" +
        uiDraggableIdCount[2] +
        "_" +
        parseInt(uiDraggableIdCount[3])
    ).css({
      left: getSession[0].x * getVal,
      top: getSession[0].y * getVal,
      width: w * getVal,
      height: h * getVal,
      "font-size": fontsize * getVal,
    });
    $(
      "#tool_component_" +
        uiDraggableIdCount[2] +
        "_" +
        parseInt(uiDraggableIdCount[3]) +
        " .description"
    ).css({
      width: w * getVal,
      height: h * getVal,
      "line-height": linheight * getVal + "px",
    });
    // console.log("arrow_box_left",arrow_box_left,"arrow_box_left * getVal",arrow_box_left * getVal)
    $(".arrow_box").css({
      left: arrow_box_left * getVal + "px",
    });
    // debugger;
  }
});