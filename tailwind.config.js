module.exports = {
  presets: [
    require('@marshallu/marsha-tailwind')
  ],
	purge: {
		content: [
			'./source/css/*.css',
			'./source/css/*/*.css',
			'./*.php',
		],
	},
	variants: {
		extend: {
			gradientColorStops: ['active', 'group-hover'],
		},
	},
}
