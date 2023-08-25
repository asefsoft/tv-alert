import './bootstrap';

// changed base on doc: https://livewire.laravel.com/docs/upgrading#including-via-js-bundle
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import focus from '@alpinejs/focus';

Alpine.plugin(focus)

Livewire.start()
