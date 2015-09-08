//Gruntfile
module.exports = function(grunt) {
	require('jit-grunt')(grunt);

	grunt.initConfig({
	    less: {
	      	development: {
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

	grunt.registerTask('default', ['less']);
	grunt.registerTask('dev', ['less', 'watch']);
};