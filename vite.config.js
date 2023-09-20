import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import livewire from '@defstudio/vite-livewire-plugin';

export default defineConfig({
	plugins: [
		laravel({
			input: [
				'resources/scss/app.scss',
				'resources/js/app.js',
			],
			refresh: false,
		}),
		livewire({  // <-- add livewire plugin
			refresh: ['resources/css/app.css'],  // <-- will refresh css (tailwind ) as well
		}),
	],
	resolve: {
		alias: {
			"uikit-util": path.resolve(
				__dirname,
				"node_modules/uikit/src/js/util/"
			),
		}
	},
});