module.exports = function(grunt) {
  grunt.initConfig({
    less: {
      development: {
        options: {
          compress: true,
          sourceMap: true,
          sourceMapFilename: './static/css/ui.map'
        },
        files: {
          './static/css/ui.css': './static/less/ui.less'
        }
      }
    },

    watch: {
      less: {
        files: [
          './static/less/*.less',
          './static/less/*/*.less',
          './static/less/*/*/*.less'
        ],
        tasks: ['less'],
        options: {
          livereload: true
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-watch');
};