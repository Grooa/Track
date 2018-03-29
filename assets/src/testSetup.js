import { configure } from 'enzyme'; // eslint-disable-line import/no-extraneous-dependencies
import Adapter from 'enzyme-adapter-react-16'; // eslint-disable-line import/no-extraneous-dependencies
import React from 'react';

/**
 * Tells Enzyme to use React 16's adapter,
 * when rendering components
 * */
configure({ adapter: new Adapter() });

// Calling Jest directly will cause tests to fail because `React` is not defined,
// therefore we must add it to the global test-namespace.
global.React = React;
