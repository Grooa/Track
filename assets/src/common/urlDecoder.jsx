
/**
 * Found on https://stackoverflow.com/a/901144
 * Parses the url, and extracts the query params
 *
 * @param {string} name Name of query param
 * @param {string|null} url Url to parse
 * @return {string}
 * */
/* eslint-disable */
export function getQueryParamByName(name, url = null) {
  if (!url) url = window.location.href;
  name = name.replace(/[\[\]]/g, "\\$&");
  var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
      results = regex.exec(url);
  if (!results) return null;
  if (!results[2]) return '';
  return decodeURIComponent(results[2].replace(/\+/g, " "));
}
/* eslint-enable */

/* eslint-disable */
export function getUrlSegments(url) {
  var parts = url.split('/'),
    n = parts.length;


  if (url.slice(0,5) == "/ImpressPages") {
    return parts.slice(2,n);
  }

  if (url[0] == '/') {
    return parts.slice(1,n);
  }

  return	parts;
}
/* eslint-enable */
