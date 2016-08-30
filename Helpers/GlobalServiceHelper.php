<?php

function getVersionImage(){
	return 1;
}

function getVersionScript(){
	return 111;
}
function getVersionCss(){
	return 14;
}

function isProduction(){
   return env('APP_ENV', 'local') == 'production'? true: false;
}

function isDev(){
 return env('APP_ENV', 'local') == 'development'? true: false;
}


