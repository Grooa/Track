import { render } from 'react-dom';

import { getUrlSegments } from './common/urlDecoder';

import ViewCourse from './ViewCourse';

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
  switch (firstSegment) {
    case 'c':
      requireDomElementExists('viewCoursePage');

      render(
        <ViewCourse />,
        document.getElementById('viewCoursePage'),
      );
      break;

    case 'kontakt':

      break;

    default:
    // Add action for route '' here
  }
}
