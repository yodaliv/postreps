import helper from "./helper";
import Agent from "./agent";
import Office from "./office";

let user = {
    init() {
        Office.init();
        Agent.init();
        Office.officesDatatabe();
        Agent.agentsDatatabe();
        this.activeTab();
    },
    activeTab() {
        if (localStorage.user_tab) {
            $("#" + localStorage.user_tab).trigger("click");
        }
        let pill = $(`[data-toggle="pill"]`);
        pill.on("click", (e) => {
            localStorage.setItem("user_tab", e.target.attributes.id.value);
        });
    },
};

$(() => {
    user.init();
});
