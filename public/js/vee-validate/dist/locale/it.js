!function(e,n){"object"==typeof exports&&"undefined"!=typeof module?module.exports=n():"function"==typeof define&&define.amd?define(n):(e.__vee_validate_locale__it=e.__vee_validate_locale__it||{},e.__vee_validate_locale__it.js=n())}(this,function(){"use strict";var e={name:"it",messages:{after:function(e,n){return"Il "+e+" deve essere dopo "+n[0]+"."},alpha_dash:function(e){return"Il campo "+e+" può contenere caratteri alfa-numerici così come lineette e trattini di sottolineatura."},alpha_num:function(e){return"Il campo "+e+" può contenere solo caratteri alfanumerici."},alpha:function(e){return"Il campo "+e+" può contenere solo caratteri alfabetici."},before:function(e,n){return"Il campo "+e+" deve essere prima di "+n[0]+"."},between:function(e,n){return"Il campo "+e+" deve essere compreso tra "+n[0]+" e "+n[1]+"."},confirmed:function(e,n){return"Il campo "+e+" non corrisponde con "+n[0]+"."},date_between:function(e,n){return"La "+e+" deve essere compresa tra "+n[0]+" e "+n[1]+"."},date_format:function(e,n){return"La "+e+" deve essere nel formato "+n[0]+"."},decimal:function(e,n){void 0===n&&(n=[]);var r=n[0];return void 0===r&&(r="*"),"Il campo "+e+" deve essere numerico e può contenere  "+("*"===r?"cm":r)+" punti decimali."},digits:function(e,n){return"Il campo "+e+" deve essere numerico e contenere esattamente "+n[0]+" cifre."},dimensions:function(e,n){return"Il campo "+e+" deve essere "+n[0]+" x "+n[1]+"."},email:function(e){return"Il campo "+e+" deve essere un indirizzo email valido."},ext:function(e){return"Il campo "+e+" deve essere un file valido."},image:function(e){return"Il campo "+e+" deve essere un'immagine."},in:function(e){return"Il campo "+e+" deve avere un valore valido."},ip:function(e){return"Il campo "+e+" deve essere un indirizzo IP valido."},max:function(e,n){return"Il campo "+e+" non può essere più lungo di "+n[0]+" caratteri."},mimes:function(e){return"Il campo "+e+" deve avere un tipo di file valido."},min:function(e,n){return"Il campo "+e+" deve avere almeno "+n[0]+" caratteri."},not_in:function(e){return"Il campo "+e+" deve avere un valore valido."},numeric:function(e){return"Il campo "+e+" può contenere solo caratteri numerici."},regex:function(e){return"Il campo "+e+" non ha un formato valido."},required:function(e){return"Il campo "+e+" è richiesto."},size:function(e,n){return"Il campo "+e+" deve essere inferiore a "+function(e){var n=0==(e=1024*Number(e))?0:Math.floor(Math.log(e)/Math.log(1024));return 1*(e/Math.pow(1024,n)).toFixed(2)+" "+["Byte","KB","MB","GB","TB","PB","EB","ZB","YB"][n]}(n[0])+"."},url:function(e){return"Il campo "+e+" non è un URL valido."}},attributes:{}};if("undefined"!=typeof VeeValidate){VeeValidate.Validator.localize((n={},n[e.name]=e,n));var n}return e});