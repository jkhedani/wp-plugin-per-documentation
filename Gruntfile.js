/**
 * Grunt configuration file
 *
 * @author    Justin Hedani
 * @authorURI https://github.com/jkhedani
 * @link http://gruntjs.com/configuring-tasks#building-the-files-object-dynamically
 * @todo - ensure sourcemaps are working
 * @todo - configure jshint properly
 * @todo - resolve minification issues
 */
module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    /** @link https://github.com/gruntjs/grunt-contrib-less */
    less: {
      dist: {
        options: {
          compress: true,
        },
        files: [{
          expand: true,
          cwd:    'assets/less/', // From
          src:    ['**/*.less'],
          dest:   'assets/css/', // To
          ext:    '.min.css',
          extDot: 'first',
        }]
      } // development
    },
    /** @link https://github.com/gruntjs/grunt-contrib-uglify */
    uglify: {
      dist: {
        options: {
          sourceMap: true,
          sourceMapName: 'assets/js/maps/sourcemap.map',
          banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
        }
        files: [{
          expand: true,
          cwd:    'assets/js/', // From
          src:    ['**/*.js'],
          dest:   'assets/js/', // To
          ext:    '.min.js',
          extDot: 'first',
        }]
      }
    },
    /** @link https://github.com/gruntjs/grunt-contrib-jshint */
    jshint: {

    },
    /** @link https://github.com/gruntjs/grunt-contrib-concat */
    concat: {
      options: {
        separator: ';'
      },
      dist: {
        // the files to concatenate
        src: ['assets/js/**/*.js'],
        // the location of the resulting JS file
        dest: 'assets/js/<%= pkg.name %>.js'
      }
    },
    /** @link https://github.com/gruntjs/grunt-contrib-watch */
    watch: {
      files: ['assets/**/*.less'],
      tasks: ['less']
    }
    // Hook js hint into watch
  });

  // Load plugin(s)
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Default task(s)
  grunt.registerTask('default',['watch','uglify']);
};
