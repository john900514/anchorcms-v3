(function() {
    let api = {}; // use this object to store all of your library functions
    let pixelId = null;
    let clientId = null;
    let clientToken = null;
    let data = {}; // use this object to store the data you're collecting and sending
    let env = '';
    // if your pixel will be used in multiple places, unique pixel ids will be crucial to
    // identify which piece of data came from which place
    api.init = function(pId) {
        clientToken = pId;
        data['token'] = pId;

        console.log('Anchor Pizza, reporting for duty!');
    };

    // include a function for each type of data you want to collect and add it to your data object.
    // if we're trying to collect Shippy's name, company, and position, we'll have the following
    // functions which should take in an object with key and value as argument (this will form your
    // query parameters):
    api.preloadStore = function (m) {
        data['club_id'] = m;
        data['preloaded_club'] = true;
        console.log('Applying ClubID');
    };

    api.preloadPromoCode = function (m) {
        data['promocode'] = m;
        data['preloaded_promo'] = true;
        console.log('Applying promo code');
    };

    api.Conversion = function(p) {
        data['Conversion'] = p;
    };

    api.setPixelId = function(p) {
        pixelId = p;
        data['pixel_id'] = p;
    };

    api.setClientId = function(p) {
        clientId = p;
        data['client_id'] = p;
    };

    api.lead_id = function(p) {
        data['lead_id'] = p;
    };

    api.pageName = function(pn) {
        data['pageName'] = pn;
        console.log('Cape and Bay...away!');
    };

    api.performNext = function() {
        let command = window.pizza.q[0];

        if (command !== undefined) {
            let func = command[0];
            let parameters = command[1];
            if (typeof api[func] === 'function') {
                api[func].call(window, parameters);
                window.pizza.q.shift();

                if (window.pizza.q.length == 0) {
                    api.send();
                } else {
                    api.performNext();
                }

            } else {
                window.pizza.q.shift();
                //console.error("Invalid function specified: " + func);
                if (window.pizza.q.length == 0) {
                    api.send();
                }
                else {
                    api.performNext();
                }
            }
        }
    };

    // include a function to turn all the data you've collected in the data object into query
    // parameters to append to the url for the pixel on your server
    api.toQueryString = function() {
        let s = [];
        Object.keys(data).forEach(function(key) {
            s.push(key + "=" + encodeURIComponent(data[key]));
        });
        return s.join("&");
    };

    // include a function to add the query parameters to your pixel url and to finally append
    // the resulting pixel URL to your document
    api.send = function() {
        console.log('################ Anchor Pizza - sending -', data);

        let pixel = document.createElement("img");
        pixel.setAttribute("class", "cnbpx");
        pixel.setAttribute("style", "width:1px;");
        let queryParams = api.toQueryString();
        let protocol = 'https';
        let subDomain = '';
        let suffix = 'com';
        let env = 'production';
        switch(env) {
            case 'local':
                protocol = 'http';
                subDomain = 'anchor3.';
                suffix = 'test';
            break;

            case 'develop':
            case 'staging':
                subDomain = 'anchor-dev.';
                break;

            default:
                subDomain = 'anchor.';
        }


        let rootUrl = `${protocol}://${subDomain}capeandbay.${suffix}/pizza/${clientId}`;
        pixel.src = rootUrl + "?" + queryParams;
        document.body.appendChild(pixel);
    };

    api.track = (t) => {
        console.log('tracking - '+t);
        data['tracking'] = t;

        if(window.pizza.loaded) {
            if('activity' in data) {
                delete data['activity'];
            }

            if('click' in data) {
                delete data['click'];
            }

            api.send();
        }
    };

    api.collect = (a) => {
        console.log('activity - '+a);
        data['activity'] = a;

        if(window.pizza.loaded) {
            if('tracking' in data) {
                delete data['tracking'];
            }

            if('click' in data) {
                delete data['click'];
            }

            api.send();
        }
    };

    api.click = (ev) => {
        console.log('click event - ', [ev]);

        data['click'] = ev;

        if(window.pizza.loaded) {
            if('tracking' in data) {
                delete data['tracking'];
            }

            if('activity' in data) {
                delete data['activity'];
            }
        }

        api.send();

    };


    window.pizza.pepperoni = (method, value) => {
        if(method in api) {
            console.log(`Calling ${method} in the api with args - `, [value]);
            api[method](value);
        }
    };

    var buttonAttrs = document.querySelectorAll('[data-pizza-topping]');

    for(let x = 0, l = buttonAttrs.length; x < l; x++) {
        buttonAttrs[x].addEventListener('click', ($event) => {
            console.log($event);

            /* @todo - complete this
            // Get the data-pizza-topping (which is an event)
            let topping = ''

            //Curate the Payload
            let payload = {
                click: topping,
                attrs: {}
            };

            // api.click (it will send on its own)
            api.click(payload);
            */
        });
    }

    // pull functions off of the global queue and execute them
    const execute = function() {
        // while the global queue is not empty, remove the first element and execute the
        // function with the parameter it provides
        // (assuming that the queued element is a 2 element list of the form
        // [function, parameters])
        window.pizza.loaded = false;
        let command = window.pizza.q[0];
        if(command !== undefined) {
            let func = command[0];
            let parameters = command[1];
            if (typeof api[func] === 'function') {
                api[func].call(window, parameters);
                window.pizza.q.shift();

                if(window.pizza.q.length == 0) {
                    api.send();
                }
                else {
                    api.performNext();
                }

                window.pizza.loaded = true;
            }
            else {
                window.pizza.q.shift();
                //console.error("Invalid function specified: " + func);
                if(window.pizza.q.length == 0) {
                    api.send();
                }
                else {
                    api.performNext();
                }
                window.pizza.loaded = true;
            }
        }
    };

    execute();
})();
