/**
 * Grunt configuration file
 *
 * @author    Justin Hedani
 * @authorURI https://github.com/jkhedani
 * @link http://gruntjs.com/configuring-tasks#building-the-files-object-dynamically
 * @todo - ensure sourcemaps are working
 * @todo - configure jshint properly
 */
module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    /**
     * Compile all less files into css
     * @link https://github.com/gruntjs/grunt-contrib-less
     */
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

    /**
     * Concat all js files and place is js/
     * @link https://github.com/gruntjs/grunt-contrib-concat
     */
    concat: {
      options: {
        separator: ';',
        banner: '/*! concat <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
      },
      prod: {
        src: ['assets/js/src/*.js'], // the files to concatenate
        dest: 'assets/js/<%= pkg.name %>.js' // the location of the resulting JS file
      }
    },

    /**
     * Uglify only the concatenated files
     * @link https://github.com/gruntjs/grunt-contrib-uglify
     */
    uglify: {
      options: {
        sourceMap: true,
        sourceMapName: 'assets/js/maps/sourcemap.map',
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("dd-mm-yyyy") %> */\n'
      },
      prod: {
        files: [{
          expand: true,
          cwd:    'assets/js/', // src directory
          src:   ['*.js', '!*.min.js'], // src files relative to cwd
          dest:   'assets/js/', // To
          ext:    '.min.js',
          extDot: 'first',
        }]
      }
    },

    /** @link https://github.com/gruntjs/grunt-contrib-watch */
    watch: {
      concat: {
        files: ['assets/js/src/*.js'],
        tasks: ['concat']
      },
      // uglify: {
      //   files: ['assets/js/*.js'],
      //   tasks: ['uglify']
      // },
      styles: {
        files: ['assets/**/*.less'],
        tasks: ['less']
      }
    }
    // Hook js hint into watch
  });

  // Load plugin(s)
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Default task(s)
  grunt.registerTask('default',['watch']);
};
