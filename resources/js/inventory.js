import Accessory from './accessory';
import Panel from './Panel';
import Post from "./post";
const Inventory = {
    init() {
        console.log("inventory jss");
        Post.init();
        Panel.init();
        Accessory.init();
        this.activeTab();
    },
    activeTab() {
        if (localStorage.inventory_tab) {
            $("#" + localStorage.inventory_tab).trigger("click");
        }
        let pill = $(`[data-toggle="pill"]`);
        pill.on("click", (e) => {
            localStorage.setItem("inventory_tab", e.target.attributes.id.value);
        });
    },
};

$(() => Inventory.init());
