const mix = require('laravel-mix');

/** Compile SAAA Assets **/
mix.sass('resources/themes/saaa/assets/sass/app.scss', 'public/assets/themes/saaa/css')
	.js('resources/themes/saaa/assets/js/app.js', 'public/assets/themes/saaa/js');


/** Compile Bu Assets **/
mix.sass('resources/themes/bu/assets/sass/app.scss', 'public/assets/themes/bu/css')
	.js('resources/themes/bu/assets/js/app.js', 'public/assets/themes/bu/js');	

/** Compile Tax Faculty Assets **/
mix.sass('resources/themes/taxfaculty/assets/sass/app.scss', 'public/assets/themes/taxfaculty/css')
	.js('resources/themes/taxfaculty/assets/js/app.js', 'public/assets/themes/taxfaculty/js');