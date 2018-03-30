import { render } from 'react-dom';

import { getUrlSegments } from './common/urlDecoder';

import ViewCourse from './ViewCourse';
import DisplayVideo from './DisplayVideo';

/**
 * react-dom's `render` function give some cryptic error message
 * if it cannot find the DOM element.
 *
 * This function can be called to check if the DOM-element exist,
 * and throws a more fitting error message.
 * @param {string} id The HTML ID
 * @throws ReferenceError
 * */
// eslint-disable-next-line no-unused-vars
function requireDomElementExists(id) {
  if (document.getElementById(id) == null) {
    throw new ReferenceError(`Cannot find React DOM root: ${id}`);
  }
}

/**
 * As we don't route with react-router
 * and only use react on some modules,
 * we will instead use some manual routing by looking at the
 * URI-segments.
 *
 * NOTE!  This is an intermediate solution to help mitigate the technical debt
 *        from our legacy JavaScript,
 *        before we fully separate the front-end and back-end.
 *
 *        The legacy JS uses an ancient AngularJS (type Release Candidate),
 *        and an older version of JQuery.
 *        There is also no form of tests or use of ES2015+ syntax.
 *        As a consequence we have a painfully slow velocity when working with front-end code.
 *
 * @param {string} uri The page's URI
 * @throws ReferenceError Thrown if the function cannot find the React DOM roots
 * */
// eslint-disable-next-line import/prefer-default-export
export function loadComponents(uri) {
  const segments = getUrlSegments(uri);
  const firstSegment = segments[0];

  segments.shift(); // Remove the first URI segment

  if (firstSegment === 'c') {
    requireDomElementExists('viewCoursePage');

    render(
      <ViewCourse courseLabel={segments[0]} />,
      document.getElementById('viewCoursePage'),
    );
    // eslint-disable-next-line no-restricted-globals
  } else if (firstSegment === 'online-courses' && !isNaN(segments[0]) && segments[1] === 'v') {
    requireDomElementExists('displayVideo');

    render(
      <DisplayVideo
        moduleId={parseInt(segments[0], 10)}
        videoId={parseInt(segments[2], 10)} />,
      document.getElementById('displayVideo'),
    );
  }
}

/**
 * Expects an ordered list of routes,
 * where each element is structured as follows.
 *
 * {
 *   matcher: (uriSegments: String[]) => boolean, // Predicate
 *   handler: (uriSegments: String[]) => void // The route handler
 * }
 *
 * @param {object[]} routes
 * */
// eslint-disable-next-line no-unused-vars
function routeMatch(routes) {
  const segments = [];

  for (let i = 0; i < routes.length; i += 1) {
    const route = routes[i];

    if (route.matcher(segments)) {
      route.handler(segments);
      break;
    }
  }
}
