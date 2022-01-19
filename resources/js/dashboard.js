import InstallPost from "./install_post";
import Order from "./order";
import helper from './helper';

let dashboard = {
    init() {
        if (window.location.href.indexOf('/dashboard') != -1) {
            console.log("map start");
            InstallPost.init();
            Order.init();            
        }

        $('.order-repair').on('click', () => {
            window.location.href = `${helper.getSiteUrl()}/repair`;
        });
    },

};


$(() => {
    dashboard.init();
});
