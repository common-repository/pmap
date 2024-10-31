#!/bin/bash

    lessc ./assets/styles/main.less > ./assets/styles/main.css
    yui-compressor ./assets/styles/main.css -o ./assets/styles/main.min.css

    yui-compressor ./assets/js/main-admin.js -o ./assets/js/main-admin.min.js
    yui-compressor ./assets/js/main-user.js -o ./assets/js/main-user.min.js

echo "compile ready"