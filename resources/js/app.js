import "./bootstrap";

import helper from "./helper";

//Datatable
import "../../node_modules/datatables.net/js/jquery.dataTables.js";
import "../../node_modules/datatables.net-bs4/js/dataTables.bootstrap4.js";
import "../../node_modules/datatables.net-responsive/js/dataTables.responsive.js";
import "../../node_modules/datatables.net-colreorder/js/dataTables.colReorder.js";
import "../../node_modules/datatables.net-select/js/dataTables.select.js";
import "../../node_modules/datatables.net-buttons/js/dataTables.buttons.js";
import "../../node_modules/datatables.net-buttons/js/buttons.html5.js";
import "../../node_modules/datatables.net-buttons/js/buttons.print.js";
import "../../node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.js";

//set error mode
$.fn.dataTable.ext.errMode = "throw"; // show errors in console

//Fontawesome
import "../../node_modules/@fortawesome/fontawesome-free/js/brands";
import "../../node_modules/@fortawesome/fontawesome-free/js/solid";
import "../../node_modules/@fortawesome/fontawesome-free/js/fontawesome";
import "../../node_modules/@fortawesome/fontawesome-free/js/regular";

//moment js
import moment from "moment";
window.moment = moment;

//jquery ui
import "../../node_modules/jqueryui/jquery-ui";

//select 2
import '../../node_modules/select2/dist/js/select2.full';


//Mask Input
import "../../node_modules/inputmask/dist/jquery.inputmask";
$(".phones").inputmask("(999) 999-9999");
$(".zipcode").inputmask("99999-9999");
$(".zipcode2").inputmask("99999")
$(".date-input").inputmask({
    alias: "datetime",
    placeholder: "mm/dd/yyyy",
    inputFormat: "mm/dd/yyyy",
});
$(".date-input-card").inputmask({
    alias: "datetime",
    placeholder: "mm/dd",
    inputFormat: "mm/dd",
})
$(".date-input-year").inputmask({
    alias: "datetime",
    placeholder: "YYYY",
    inputFormat: "yyyy",
})
$(".date-input-month").inputmask({
    alias: "datetime",
    placeholder: "MM",
    inputFormat: "mm",
})


//Disable enter key for form submit but still allow in textarea
$("form").on("keypress", (e) => {
    if (e.key == "Enter") {
        var tagName = e.target.tagName.toLowerCase();
        if (tagName !== "textarea") {
            return false;
        }
    }
});

if (helper.isMobilePhone()) {
    $(".mobile-view").show();
    $(".desktop-view").hide();
} else {
    $(".mobile-view").hide();
    $(".desktop-view").show();
}

$(window).on("resize", () => {
    if (helper.isMobilePhone()) {
        $(".mobile-view").show();
        $(".desktop-view").hide();
    } else {
        $(".mobile-view").hide();
        $(".desktop-view").show();
    }
});

$("#mobileAccountResourcesMenu").on("click", () => {
    $(".navbar-toggler").trigger("click");
});

Document.prototype.getAll = (query) => document.querySelectorAll(query);
Document.prototype.get = (query) => document.querySelector(query);

if (window.e == undefined) {
    window.e = (tagName, attrs, childrens) => {
        //attrs as text
        let textAttrs = "";
        //get htmlContent from attrs then rmove it from attrs
        let htmlContent = "";
        if (attrs.htmlContent) htmlContent = attrs.htmlContent;
        delete attrs.htmlContent;
        //get attrs as text
        for (let k in attrs) {
            textAttrs += k;
            textAttrs += "=";
            textAttrs += '"' + attrs[k] + '"';
        }
        //
        if (!childrens) childrens = () => "";
        //return eleemnt as text
        return `<${tagName} ${textAttrs}>${htmlContent}${childrens()}</${tagName}>`;
    };
}

$(() => {
    document.getAll(`[data-dismiss-modal]`).forEach((e) => {
        e.onclick = () => {
            let self = $(e);
            let modalId = self.attr("data-dismiss-modal");
            let modal = $(modalId);
            if (modal.length) {
                modal.modal("hide");
            }
        };
    });
    document.getAll(`[data-open-modal]`).forEach((e) => {
        e.onclick = () => {
            let self = $(e);
            let modalId = self.attr("data-open-modal");
            let modal = $(modalId);
            if (modal.length) {
                modal.modal("show");
            }
        };
    });
});
$('.modal').attr("data-backdrop", "static").attr("data-keyboard", "false")

