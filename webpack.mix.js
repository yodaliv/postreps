const mix = require("laravel-mix");

mix.sass("resources/sass/app.scss", "public/css")
    .js("resources/js/app.js", "public/js")
    .js("resources/js/region.js", "public/js")
    .js("resources/js/dashboard.js", "public/js")
    .js("resources/js/public.js", "public/js")
    .js("resources/js/user.js", "public/js")
    .js("resources/js/register.js", "public/js")
    .js("resources/js/inventory.js", "public/js")
    .js("resources/js/service.js", "public/js")
    .js('resources/js/repair-order.js', "public/js")
    .version()
    .browserSync({
        proxy: "postreps.local",
        files: [
            // "app/**/*",
            // "routes/**/*",
            "resources/**/*"
        ],
    })
    .extract();
