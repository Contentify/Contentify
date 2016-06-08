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
                    'public/css/backend.css': 'resources/assets/less/backend.less'
                }
            }
        },
        watch: {
            styles: {
                files: ['resources/assets/less/backend.less'], // files to watch
                tasks: ['less'], // tasks to run
                options: {
                    nospawn: true
                }
            }
        }
    });

    grunt.registerTask('default', ['less:publish']); // compile the less files of the cms to the public css folder
    grunt.registerTask('watch', ['less:publish', 'watch']); 
};