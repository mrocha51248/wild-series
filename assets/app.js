/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

import { Tooltip, Toast, Popover } from 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css';

// start the Stimulus application
import './bootstrap';

import 'Hinclude/hinclude';

document.querySelectorAll(".watchlist-toggle").forEach(function(element) {
    element.addEventListener('click', function(event) {
        event.preventDefault();
        fetch(element.href)
            .then(response => response.json())
            .then(function(json) {
                const watchlistIcon = element.firstElementChild;
                watchlistIcon.classList.remove(json.isInWatchlist ? 'bi-heart' : 'bi-heart-fill');
                watchlistIcon.classList.add(json.isInWatchlist ? 'bi-heart-fill' : 'bi-heart');
            });
    });
});
