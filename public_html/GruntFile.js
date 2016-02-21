
module.exports = function(grunt) {

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		compass: {
			dist: {
				options: {
					sassDir: 'admin-files/master/sass',
					cssDir: 'admin-files/assets/css'
				},
			}
		},

		watch: {
			options: {
				livereload:1337
			},
			//html: { files:['admin/**/*.html'] },
			css: {
				files: ['admin-files/master/sass/**/*.scss'],
				tasks: ['compass'],
				options: {
					livereload:1337
				}
			}
		}
	});
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-compass');

	grunt.registerTask('default', ['watch']);
};
