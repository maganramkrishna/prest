#!/usr/bin/env bash

PHP_MAJOR=`$(phpenv which php-config) --version | cut -d '.' -f 1,2`

LOCAL_SRC_DIR=${HOME}/.cache/cphalcon/src
LOCAL_LIB_DIR=${HOME}/.local/lib
LOCAL_PHALCON=${LOCAL_LIB_DIR}/phalcon-php-${PHP_MAJOR}.so

EXTENSION_DIR=`$(phpenv which php-config) --extension-dir`

if [ ! -f ${LOCAL_PHALCON} ]; then
    mkdir -p ${LOCAL_SRC_DIR}
    mkdir -p ${LOCAL_LIB_DIR}

    rm -rf ${LOCAL_SRC_DIR}/*
    git clone --depth=1 -v https://github.com/phalcon/cphalcon ${LOCAL_SRC_DIR}

    cd ${LOCAL_SRC_DIR}/build

    bash ./install --phpize $(phpenv which phpize) --php-config $(phpenv which php-config)

    if [ ! -f "${EXTENSION_DIR}/phalcon.so" ]; then
        echo "Unable to locate installed phalcon.so"
        exit 1
    fi

    cp "${EXTENSION_DIR}/phalcon.so" ${LOCAL_PHALCON}
fi

echo "extension=${LOCAL_PHALCON}" > ${HOME}/.phpenv/versions/$(phpenv version-name)/etc/conf.d/phalcon.ini
