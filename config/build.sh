#!/bin/bash
# @author: Aurélien MANCA
# @description: Install the sandbox

./symfony propel:build-model
./symfony propel:build-sql
./symfony propel:insert-sql --no-confirmation
./symfony propel:build-forms
./symfony propel:build-filters
./symfony propel:data-load
./symfony cc
./symfony asset:create-root