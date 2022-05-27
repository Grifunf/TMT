/**
 * Makes a request and handle response asynchronously
 * @param {String} type Request type
 * @param {String} url Endpoint the handle the request
 * @param {Object} params Parameters to be send with the request
 * @param {Function} callback Function to handle the response
 */
function AsyncRequest(type, url, params, callback)
{
    type = type.toLowerCase();
    let xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function()
    {
        if(this.readyState === 4 && this.status == 200)
            callback(JSON.parse(this.response));
    };

    if(type !== 'get')
    {
        xhttp.open(type, url, true);
        xhttp.setRequestHeader('Content-Type', 'application/json');
        xhttp.send(JSON.stringify(params));
    }
    else
    {
        const fullurl = join(url, params);
        xhttp.open('get', fullurl, true);
        xhttp.send();
    }
}

/**
 * Join the given url and arguments in new get like url
 * @param {string} url The url of the endpoint that will handle the request
 * @param {object} args Object containing the extra arguments to be appended on the url
 * @returns {string} The newly created get like url with the arguments appended to it
 */
function join(url, args)
{
    let first = true;
    let fullurl = url;
    for(const prop in args)
    {
        if(args[prop] === '')
            continue;
        fullurl += (first ? '?' : '&') + `${prop}=${args[prop]}`; 
        if(first) first = false;
    }
    return fullurl;
}

/**
 * Redirects us to the specified url
 * @param {String} url url to be redirected into
 */
function redirect(url) { window.location.replace(url); }