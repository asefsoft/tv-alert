import './bootstrap';

// changed base on doc: https://livewire.laravel.com/docs/upgrading#including-via-js-bundle
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import focus from '@alpinejs/focus';

Alpine.plugin(focus)

Livewire.start()


// clicked on subscribe
window.subscribeClicked = function subscribeClicked(wire) {
    // not logged-in?
    if(window.isAuthenticated === '0') {
        // display register required modal form.
        Livewire.getByName('modals.registration-required')[0].displayRegisterModal = true;
    }
    else {
        // call subscribe on livewire component
        wire.call('subscribe');
    }
}

window.tvShowClicked = function tvShowClicked(wire, tvshowId) {
    wire.dispatch('tvshow-changed', [tvshowId]);
    // showing full info modal
    Livewire.getByName('modals.full-info-modal')[0].displayTvShowModal = true;
    // this will hide old data of current tv show
    Livewire.getByName('t-v-show-full-info')[0].isLoadingShowInfo = true;
}

window.addEventListener('DOMContentLoaded', (event) => {
    // forcing search result to be open after each search
    Livewire.hook('morph.updated', ({ el, component }) => {
        // is dropdown?
        if(el.getAttribute('id') === 'dropdown') {
            // then dispatch AlpineJs custom event 'open-me' to set open var to true on dropdown component
            el.dispatchEvent(new CustomEvent('open-me', { detail: {}}));
        }
    })
});
