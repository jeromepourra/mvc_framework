<?php

ini_set('display_errors', true);
ini_set('error_reporting', Config()->get('error_reporting'));
ini_set('error_log', null);
ini_set('session.save_path', Config()->get('session.save_path'));
ini_set('xdebug.var_display_max_depth', Config()->get('xdebug.var_display_max_depth'));
ini_set('xdebug.var_display_max_children', Config()->get('xdebug.var_display_max_children'));
ini_set('xdebug.var_display_max_data', Config()->get('xdebug.var_display_max_data'));