//Gruntfile
module.exports = function(grunt) {
	require('jit-grunt')(grunt);

	grunt.initConfig({
	    less: {
	      	publish: {
	        	options: {
	          		compress: true,
	          		yuicompress: true,
	          		optimization: 2
	        	},
	        	files: {
	        		// destination file and source file
	          		'public/css/frontend.css': 'resources/assets/less/frontend.less', 
	          		'public/css/backend.css': 'resources/assets/less/backend.less'
	        	}
	      	},
	      	theme: {
	        	options: {
	          		compress: true,
	          		yuicompress: true,
	          		optimization: 2
	        	},
	        	files: {
	        		// destination file and source file
	          		'app/Modules/DefaultTheme/Resources/Assets/css/frontend.css': 'resources/assets/less/frontend.less', 
	          		'app/Modules/DefaultTheme/Resources/Assets/css/backend.css': 'resources/assets/less/backend.less'
	        	}
	      	}
	    },
	    watch: {
			styles: {
				files: ['resources/assets/less/frontend.less', 'resources/assets/less/frontend.less'], // files to watch
				tasks: ['less'], // tasks to run
				options: {
					nospawn: true
				}
			}
	    }
	});

	grunt.registerTask('default', ['less:publish']); // compile the less files of the cms to the public css css folder
	grunt.registerTask('theme', ['less:theme']); // compile the less files of the cms to the default theme css folder
	grunt.registerTask('watch', ['less:publish', 'watch']);	
};