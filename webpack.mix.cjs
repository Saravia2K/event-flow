const mix = require("laravel-mix");
const fs = require("fs");

const getFiles = (dir) =>
    fs
        .readdirSync(dir)
        .filter((file) => fs.statSync(`${dir}/${file}`).isFile());

getFiles("resources/scss").forEach(function (filepath) {
    mix.sass("resources/scss/" + filepath, "css");
});
