import 'babel-polyfill'; // polyfill for all ES2015+ features, must be imported in root file

import { loadComponents } from 'router';

const currentUri = document.location.pathname;

loadComponents(currentUri);
