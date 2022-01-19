import helper from "./helper";
import Zone from "./zone";
const Service = {
    init() {
        console.log("service");
        Zone.init();

        this.globalSettings();
    },
    globalSettings() {
        let settingsContainer = $(`#service-global-settings`);

        settingsContainer.find(`input`).on("change", (e) => {
            e = $(e.target);
            let column = e.attr("name");
            let value = e.val();
            $.get(`/update/column/service/settings/${column}/${value}`);
        });
    },
};

$(() => Service.init());
