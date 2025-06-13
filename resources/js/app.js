import './bootstrap';
import './vendor/preline.min.js';

import Alpine from 'alpinejs';
import 'preline';
import _ from 'lodash';
import Dropzone from 'dropzone';
import HSFileUpload from 'preline/dist/file-upload.js';

window.Alpine = Alpine;
window._ = _;
window.Dropzone = Dropzone;
window.HSFileUpload = HSFileUpload;

Alpine.start();
